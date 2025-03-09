<?php

namespace App\Controllers;

use App\Common\CompatibilityChecker;
use App\Http\Request;
use App\Repositories\BrandsRepository;
use App\Repositories\CollectorsRepository;
use App\Repositories\CotizacionesWidgetsRepository;
use App\Repositories\CustomerRepository;
use App\Repositories\DashboardGraphsRepository;
use App\Repositories\DashboardWidgetsRepository;
use App\Repositories\EffectivenessRepository;
use App\Repositories\IngresosWidgetsRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\SellersRepository;
use App\Services\DashboardEffectivenessService;
use Carbon\Carbon;

class DashboardController extends BaseController
{

    public $widgetsRepository;
    public $customersRepository;
    public $dashboardGraphsRepository;
    public $sellersRepository;
    public $brandsRepository;
    public $productsRepository;
    public $ingresosWidgetsRepository;
    public $collectorsRepository;
    public function __construct()
    {
        parent::__construct();
    }

    public function index () {
        $this->sellersRepository = new SellersRepository();
        $this->collectorsRepository = new CollectorsRepository();

        return $this->view('dashboard.home', [
            'dashData' => json_encode([
                'lists' => [
                    'sellers' => $this->sellersRepository->sellersList(self::$company_cxc),
                    'collectors' => $this->collectorsRepository->collectorsList(self::$company_cxc),
                ],
                'client' => [
                    'company' => self::$company_details,
                    'settings' => self::$client_settings
                ],
                'hide' => [
                    'dashboardEfectividad' => !CompatibilityChecker::PuedeVerReportesDeVisitasEfectivas(),
                    'dashboardCotizaciones' =>
                        !CompatibilityChecker::tableExists('quotations_header')
                    || self::$credentials->quotations == 0
                ]
            ])
        ]);
    }

    public function fetchIngresosDataAsync() {

        $filters = Request::query()->get('get');
        if (!is_null($filters) ) {
            $filters = json_decode($filters);
        } else {
            $filters = json_decode(json_encode([
                'start_date' => date("Y-m-d"),
                'end_date' => date("Y-m-d"),
                'collectors_condition' => "",
                'collectors' => []
            ])) ;
        }

        $this->ingresosWidgetsRepository = new IngresosWidgetsRepository();
        $this->customersRepository = new CustomerRepository();
        $this->dashboardGraphsRepository = new DashboardGraphsRepository();



        $totalDeRecibos= $this->ingresosWidgetsRepository->totalDeTodosLosRecibos(
            count($filters->collectors) > 0 ? $filters->collectors: null,
            $filters->start_date, $filters->end_date,null,$filters->collectors_condition,"",self::$default_company)
        ['total'];

        $ingresos = $this->dashboardGraphsRepository->ingresosTotalesDelPeriodo(
            count($filters->collectors) >0 ? $filters->collectors: null,
            $filters->start_date, $filters->end_date,null,$filters->collectors_condition,"",self::$default_company
        );

        $montosDeCobrosPorCobrador = $this->dashboardGraphsRepository->montosDeCobrosPorCobrador(
            count($filters->collectors) >0 ? $filters->collectors: null,
            $filters->start_date, $filters->end_date,null,$filters->collectors_condition,"",self::$default_company

        );

        $promedioDeCobros = $this->ingresosWidgetsRepository->promedioDeRecibosDeCobrador($montosDeCobrosPorCobrador);
        $cantidadCobros = $this->ingresosWidgetsRepository->cantidadDeRecibos(
            count($filters->collectors) >0 ? $filters->collectors: null,
            $filters->start_date, $filters->end_date,null,$filters->collectors_condition,"",self::$default_company

        )  ['cantidad'];

        $clientesInvolucrados = $this->customersRepository->cantidadDeClientesRecibos(
            count($filters->collectors) >0 ? $filters->collectors: null,
            $filters->start_date, $filters->end_date,null,$filters->collectors_condition,"",self::$default_company
        )  ['cantidad'];

        return $this->jsonResponse([
            'dashData' => json_encode([
                'widgets' => [
                    'clientCount' => $clientesInvolucrados,
                    'receiptCount' => $cantidadCobros,
                    'receiptAmount' => $totalDeRecibos ? $totalDeRecibos : 0 ,
                    'receiptAverage' => $promedioDeCobros
                ],
                'graphs' => [
                    'ingresosTotalGraph' => $ingresos,
                    'collectorsGraphData' => $montosDeCobrosPorCobrador
                ]
            ])
        ]) ;

    }

    public function fetchCotizacionesDataAsync()
    {
        $filters = Request::query()->get('get');

        if (!is_null($filters) ) {
            $filters = json_decode($filters);
        } else {
            $filters = json_decode(json_encode([
                'start_date' => date("Y-m-d"),
                'end_date' => date("Y-m-d"),
                'sellers_condition' => "",
                'sellers' => []
            ])) ;
        }

        $this->widgetsRepository = new CotizacionesWidgetsRepository();
        $this->customersRepository = new CustomerRepository();
        $this->dashboardGraphsRepository = new DashboardGraphsRepository();
        $this->brandsRepository = new BrandsRepository();
        $this->productsRepository = new ProductsRepository();

        $totalDeCotizaciones = $this->widgetsRepository->totalDeTodasLasCotizaciones(
            count($filters->sellers) > 0 ? $filters->sellers: null,
            $filters->start_date,
            $filters->end_date,
            null,
            $filters->sellers_condition,
            "",
            self::$default_company)
        ['total'];

        $cantidadCotizaciones = $this->widgetsRepository->
        cantidadDeCotizaciones(
            count($filters->sellers) > 0 ? $filters->sellers: null,
            $filters->start_date,
            $filters->end_date,
            null,
            $filters->sellers_condition,
            "",
            self::$default_company)
        ['cantidad'];

        $clientesInvolucrados = $this->customersRepository->cantidadDeClientesCotizaciones(
            count($filters->sellers) >0 ? $filters->sellers: null,
            $filters->start_date, $filters->end_date,
            null,$filters->sellers_condition,"",self::$default_company)
        ['cantidad'];

        $montosDeCotizacionesPorVendedor = $this->dashboardGraphsRepository->montosDeCotizacionesPorVendedor(
            count($filters->sellers) >0 ? $filters->sellers: null,
            $filters->start_date, $filters->end_date,
            null,
            $filters->sellers_condition,
            "",
            self::$default_company
        );

        $promedioDeCotizaciones = $this->widgetsRepository->promediosDeVendedores(
            $montosDeCotizacionesPorVendedor);

        $topMarcas = $this->brandsRepository->cotizacionesDeMarcasTop(
            count($filters->sellers) >0 ? $filters->sellers: null,
            $filters->start_date, $filters->end_date,null,
            $filters->sellers_condition,
            "",
            self::$default_company,
            self::$catalogue_company
        );

        $topProductos = $this->productsRepository->cotizacionesDeproductosTop(
            count($filters->sellers) >0 ? $filters->sellers: null, $filters->start_date, $filters->end_date,null,
            $filters->sellers_condition,"", self::$default_company, self::$catalogue_company
        );

        $topClientes = $this->customersRepository->topClientesCotizaciones(
            count($filters->sellers) >0 ? $filters->sellers: null,
            $filters->start_date, $filters->end_date,null,$filters->sellers_condition,"",self::$default_company
        );

        $cotizacionesTotalesGraph = $this->dashboardGraphsRepository->cotizacionesTotalesDelPeriodo(
            count($filters->sellers) >0 ? $filters->sellers: null,
            $filters->start_date, $filters->end_date,null,$filters->sellers_condition,"",self::$default_company
        );

        return $this->jsonResponse([
            'dashData' => json_encode([
                'widgets' => [
                    'clientCount' => $clientesInvolucrados,
                    'quotationCount' => $cantidadCotizaciones,
                    'quotationsAmount' => $totalDeCotizaciones ? $totalDeCotizaciones : 0 ,
                    'quotationsAverage' => $promedioDeCotizaciones
                ],
                'graphs' => [
                    'quotationsGraphData' => $montosDeCotizacionesPorVendedor,
                    'quotationsTotalesGraph' => $cotizacionesTotalesGraph
                ],
                'top' => [
                    'marcas' => $topMarcas,
                    'productos' => $topProductos,
                    'clientes' => $topClientes
                ]
            ])
        ]) ;

    }

    public function fetchVentasDataAsync()
    {
        $filters = Request::query()->get('get');

        if (!is_null($filters) ) {
            $filters = json_decode($filters);
        } else {
            $filters = json_decode(json_encode([
                'start_date' => date("Y-m-d"),
                'end_date' => date("Y-m-d"),
                'sellers_condition' => "",
                'sellers' => []
            ])) ;
        }

        $this->widgetsRepository = new DashboardWidgetsRepository();
        $this->customersRepository = new CustomerRepository();
        $this->dashboardGraphsRepository = new DashboardGraphsRepository();
        $this->brandsRepository = new BrandsRepository();
        $this->productsRepository = new ProductsRepository();

        $totalDeOrdenes = $this->widgetsRepository->totalDeTodasLasOrdenes(
            count($filters->sellers) > 0 ? $filters->sellers: null,
            $filters->start_date, $filters->end_date,null,$filters->sellers_condition,"",self::$default_company)
        ['total'];



        $cantidadOrdenes = $this->widgetsRepository->
        cantidadDeOrdenes(  count($filters->sellers) > 0 ? $filters->sellers: null,
            $filters->start_date, $filters->end_date,null,$filters->sellers_condition,"",self::$default_company)
        ['cantidad'];


        $clientesInvolucrados = $this->customersRepository->cantidadDeClientesOrdenes(  count($filters->sellers) >0 ? $filters->sellers: null,
            $filters->start_date, $filters->end_date,null,$filters->sellers_condition,"",self::$default_company)
        ['cantidad'];


        $montosDeVentaPorVendedor = $this->dashboardGraphsRepository->montosDeVentaPorVendedor(  count($filters->sellers) >0 ? $filters->sellers: null,
            $filters->start_date, $filters->end_date,null,$filters->sellers_condition,"",self::$default_company
        );

        $promedioDeVentas = $this->widgetsRepository->promedioDeVentas($montosDeVentaPorVendedor);

        if ($cantidadOrdenes !== 0) {
            $dropsize = $totalDeOrdenes / $cantidadOrdenes;
        } else
            $dropsize = 0;

        $topMarcas = $this->brandsRepository->ventasDeMarcasTop(
            count($filters->sellers) >0 ? $filters->sellers: null, $filters->start_date, $filters->end_date,null,
            $filters->sellers_condition,"", self::$default_company, self::$catalogue_company
        );

        $topProductos = $this->productsRepository->ventasDeproductosTop(
            count($filters->sellers) >0 ? $filters->sellers: null, $filters->start_date, $filters->end_date,null,
            $filters->sellers_condition,"", self::$default_company, self::$catalogue_company
        );

        $topClientes = $this->customersRepository->topClientesVentas(
            count($filters->sellers) >0 ? $filters->sellers: null,
            $filters->start_date, $filters->end_date,null,$filters->sellers_condition,"",self::$default_company
        );

        $ventasTotalesGraph = $this->dashboardGraphsRepository->ventasTotalesDelPeriodo(
            count($filters->sellers) >0 ? $filters->sellers: null,
            $filters->start_date, $filters->end_date,null,$filters->sellers_condition,"",self::$default_company
        );
        return $this->jsonResponse([
            'dashData' => json_encode([
                'widgets' => [
                    'clientCount' => $clientesInvolucrados,
                    'orderCount' => $cantidadOrdenes,
                    'salesAmount' => $totalDeOrdenes ? $totalDeOrdenes : 0 ,
                    'salesAverage' => $promedioDeVentas,
                    'dropsize' => $dropsize
                ],
                'graphs' => [
                    'sellersGraphData' => $montosDeVentaPorVendedor,
                    'ventasTotalesGraph' => $ventasTotalesGraph
                ],
                'top' => [
                    'marcas' => $topMarcas,
                    'productos' => $topProductos,
                    'clientes' => $topClientes
                ]
            ])
        ]) ;

    }


    public function fetchEfectividadDataAsync()
    {
        $filters = Request::query()->get('get');

        if (!is_null($filters) ) {
            $filters = json_decode($filters);
        } else {
            $filters = json_decode(json_encode([
                'start_date' => date("Y-m-d"),
                'end_date' => date("Y-m-d"),
                'sellers_condition' => "",
                'sellers' => []
            ]));
        }

        $effectivenessService = new DashboardEffectivenessService();

        $efectividadGlobal =  $effectivenessService->getEffectivenessBySeller(
            count($filters->sellers) > 0 ? $filters->sellers: null,
            $filters->start_date, $filters->end_date,null,$filters->sellers_condition,"",self::$default_company
        );



        $efectividadPeriodos = $effectivenessService->getCurrentAndPreviousPeriodTotalEffectivenes(count($filters->sellers) > 0 ? $filters->sellers: null,
                $filters->start_date, $filters->end_date, null,$filters->sellers_condition,"",self::$default_company);

        return $this->jsonResponse([
            'dashData' => json_encode([
                'efectividadGlobal' => $efectividadGlobal,
                'efectividadPeriodos' => $efectividadPeriodos
            ])
        ]);
    }
}