<template>
  <div>
    <div class="row" >
      <div class="col-lg-12" >
        <div id="content-header"  class="clearfix">
          <div class="row" >
            <div class="col-lg-8">
              <div class="row">
                <div v-show="currentFiltering == 'vendedores'" class="col-lg-5">
                  <multiselect v-model="selectedSellers"
                               :options="sellerMultiOptions"
                               :multiple="true"
                               @close="aplicarFiltros"
                               @remove="removeSeller"
                               :disabled="sellerMultiOptions == null"
                               selectLabel="Seleccionar"
                               :close-on-select="false"
                               :clear-on-select="false"
                               :hide-selected="true"
                               placeholder="Filtrar Vendedores"
                               label="seller_code"
                               track-by="seller_code">

                    <template slot="option" scope="props">
                      <div class="option__desc">
                        <span class="option__title">{{props.option.seller_code}} - {{ props.option.seller_name }}</span>

                      </div>
                    </template>
                  </multiselect>
                  <div v-if="sellersRequireUpdate" style="display: inline-block; cursor: pointer; margin-top: 10px">
                    <span  @click="aplicarSellersClicked" class="label label-success"> Aplicar </span>
                  </div>
                </div>
                <div v-show="currentFiltering == 'vendedores'" class="col-lg-3">
                  <select @change="aplicarFiltros" style="height: 40px;" class="form-control" v-model="filters.sellers_condition">
                    <option value="">Incluir</option>
                    <option value="NOT">Excluir</option>
                  </select>
                </div>

                <div v-show="currentFiltering == 'cobradores'" class="col-lg-5">
                  <multiselect v-model="selectedCollectors"
                               :options="collectorMultiOptions"
                               :multiple="true"
                               @close="aplicarFiltros"
                               @remove="removeCollector"
                               :disabled="collectorMultiOptions == null"
                               selectLabel="Seleccionar"
                               :close-on-select="false"
                               :clear-on-select="false"
                               :hide-selected="true"
                               placeholder="Filtrar Cobradores"
                               label="collector_code"
                               track-by="collector_code">

                    <template slot="option" scope="props">
                      <div class="option__desc">
                        <span class="option__title">{{props.option.collector_code}} - {{ props.option.collector_name }}</span>
                      </div>
                    </template>
                  </multiselect>

                  <div v-if="collectorsRequireUpdate" style="display: inline-block; cursor: pointer; margin-top: 10px">
                    <span  @click="aplicarCollectorsClicked" class="label label-success"> Aplicar </span>
                  </div>

                </div>
                <div v-show="currentFiltering == 'cobradores'" class="col-lg-3">
                  <select @change="aplicarFiltros" style="height: 40px;" class="form-control" v-model="filters.collectors_condition">
                    <option value="">Incluir</option>
                    <option value="NOT">Excluir</option>
                  </select>
                </div>

                <div class="col-lg-3 filter-picker-container" style="text-align: center">
                  <span @click="currentFiltering = 'vendedores'"
                        :class="{'active': currentFiltering == 'vendedores'}" v-if="currentDashboard == 'ventas' || currentDashboard == 'efectividad'" class="filter-picker">Filtrar Vendedores</span>
                  <span @click="currentFiltering = 'cobradores'"
                        :class="{'active': currentFiltering == 'cobradores'}" v-if="currentDashboard == 'ingresos'" class="filter-picker">Filtrar Cobradores</span>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="row">
                <div class=" col-lg-7" style="text-align: center">
                  <div id="rangepicker"  style="vertical-align: middle ;display: inline-block;width: 230px; border-radius: 5px;
                      background: #fff; cursor: pointer; padding: 5px 10px; height: 40px;    border: 1px solid #e8e8e8; ">
                    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
                    <span></span> <b class="caret"></b>
                  </div>
                </div>
                <div class=" col-lg-5" style="text-align: center">
                  <div style="display: inline-block;text-align: center">
                    <div>
                      <span @click="resetFilters" class="likealink">Limpiar Filtros</span>
                    </div>
                    <!--<div>-->
                      <!--<span @click="showFilters = false" class="likealink">Ocultar Filtros</span>-->
                    <!--</div>-->
                  </div>
                </div>
              </div>
              <!--<label style="margin-left: 5px" >Rango de Fecha</label>-->

            </div>
          </div>

          <!--<div class="row" v-show="!showFilters">-->
            <!--<div class="col-xs-6"   >-->
            <!--<span  v-show="!loading"  style="color: #00aced; font-weight: 500">-->
              <!--<template v-if="filters.start_date == filters.end_date">-->
                <!--Mostrando datos del día <b> {{prettyStartDate}}</b>-->
              <!--</template>-->
              <!--<template v-else>-->
                <!--Monstrando datos desde-->
                <!--<span style="color: #8bc34a;">  <b>{{prettyStartDate}}</b></span>-->
                <!--hasta-->
                <!--<span style="color: #8bc34a;"><b> {{prettyEndDate}}</b></span>-->

              <!--</template>-->
              <!--<template v-if="selectedSellers.length> 0">-->
                <!--<b> | </b> Vendedores <span v-if="filters.sellers_condition == 'NOT'"> excluidos</span>:-->
                <!--<span style="color: #8bc34a;">-->
                  <!--<template v-for="(seller,index) in selectedSellers">-->
                    <!--<template v-if="index !== selectedSellers.length-1">-->
                             <!--<b>{{seller.seller_code}}, </b>-->
                    <!--</template>-->
                    <!--<template v-else>-->
                               <!--<b>{{seller.seller_code}} </b>-->
                    <!--</template>-->

                  <!--</template>-->
                <!--</span >-->
              <!--</template>-->
              <!---->
               <!--<template v-if="selectedCollectors.length > 0">-->
                <!--<b> | </b> Cobradores <span v-if="filters.collectors_condition == 'NOT'"> excluidos</span>:-->
                <!--<span style="color: #8bc34a;">-->
                  <!--<template v-for="(collector,index) in selectedCollectors">-->
                    <!--<template v-if="index !== selectedCollectors.length-1">-->
                             <!--<b>{{collector.collector_code}}, </b>-->
                    <!--</template>-->
                    <!--<template v-else>-->
                               <!--<b>{{collector.collector_code}} </b>-->
                    <!--</template>-->

                  <!--</template>-->
                <!--</span >-->
              <!--</template>-->
              <!---->
            <!--</span>-->
            <!--</div>-->
            <!--<div  class="col-xs-6" style="text-align: right;  ">-->
            <!--<span class="likealink" id="showfiltros" @click="showFilters = !showFilters">-->
              <!--<i class="fa fa-filter"></i> Mostrar Filtros</span>-->
            <!--</div>-->
          <!--</div>-->
        </div>
        <div class="dashboard-switcher" v-show="!loading">
          <span @click="showDashboard('ventas')" :class="{'active': currentDashboard == 'ventas'}">Ventas</span>
          <span @click="showDashboard('ingresos')" :class="{'active': currentDashboard == 'ingresos'}">Ingresos</span>
          <span v-if="!baseData.hide.dashboardEfectividad" @click="showDashboard('efectividad')"
                :class="{'active': currentDashboard == 'efectividad'}">Efectividad</span>
          <span v-if="!baseData.hide.dashboardCotizaciones" @click="showDashboard('cotizaciones')"
                :class="{'active': currentDashboard == 'cotizaciones'}">Cotizaciones</span>
        </div>
      </div>
    </div>

    <div v-show="currentDashboard == 'ventas'">
      <dashboardventas
              :ref="'dashboardventas'"
              :baseData="baseData"
              @loading="dashboardLoading"
              @successfullyFetched="succesfullyFetched"
              @errorOnFetching="errorOnFetching"
      >
      </dashboardventas>
    </div>

    <div v-show="currentDashboard == 'cotizaciones'">
      <dashboardcotizaciones
              :ref="'dashboardcotizaciones'"
              :baseData="baseData"
              @loading="dashboardLoading"
              @successfullyFetched="succesfullyFetched"
              @errorOnFetching="errorOnFetching"
      >
      </dashboardcotizaciones>
    </div>

    <div v-show="currentDashboard == 'ingresos'">
      <dashboardingresos
              :ref="'dashboardingresos'"
              :baseData="baseData"
              @loading="dashboardLoading"
              @successfullyFetched="succesfullyFetched"
              @errorOnFetching="errorOnFetching"
      >
      </dashboardingresos>
    </div>

    <div v-show="currentDashboard == 'efectividad'">
      <dashboardefectividad
              :ref="'dashboardefectividad'"
              :baseData="baseData"
              @loading="dashboardLoading"
              @successfullyFetched="succesfullyFetched"
              @errorOnFetching="errorOnFetching"
      >
      </dashboardefectividad>
    </div>


    <template v-if="loading">
      <div style="text-align: center; margin-top: 5em ">
        <i style="font-size: 2em" class="fa fa-spinner fa-spin"></i> <br>
        <span style="font-size: 1.3em">Cargando Datos . . .</span>
      </div>
    </template>
    <template v-if="showError">
      <div style="text-align: center; ">
        <i  style="color:darkred; font-size: 2em" class="fa fa-exclamation-triangle"></i> <br>
        <span style="font-size: 1.3em">Un error ha ocurrido al procesar la solicitud.</span>
      </div>
    </template>

  </div>
</template>
<style>

  .filter-picker {
    font-size: 13px;
    padding: 4px 6px 4px 6px;
    background-color: #e0e0e0;
    border-radius: 5px;
    cursor: pointer;
    margin-right: 13px;
  }
  .filter-picker.active{
    background-color: #03a9f4;
    color: white;
  }

  .filter-picker-container {
    margin-top: 10px;
  }

  .likealink {
    color: #03a9f4;
    cursor: pointer;

  }
  .likealink:hover {
    color: #0385c2;
  }

  .multiselect__tags {
    padding-top: 4px;
  }
  .dashboard-switcher {
    background: #f3f5f6;
    margin: -15px -15px 20px -15px;
    padding: 10px;
    border-bottom: 1px solid #dee4e8;
  }
  .dashboard-switcher span {
    cursor: pointer;
    font-weight: 500;
    padding-right: 10px;
    padding-left: 10px;
    padding-top: 3px;
    padding-bottom: 3px;
  }
  .dashboard-switcher span:hover{
    background-color: #e0e2e3;
  }
  .dashboard-switcher span.active {
    font-weight: 600;
    color: white;
    background-color: #03a9f4 ;
    border-radius: 3px;
  }

</style>
<script>

  import multiselect from 'vue-multiselect'
  import incomegraph from './IngresosGraph.vue'
  import dashboardventas from './DashboardVentas.vue'
  import dashboardingresos from './DashboardIngresos.vue'
  import dashboardefectividad from './DashboardEfectividad.vue'
  import dashboardcotizaciones from './DashboardCotizaciones.vue'
  import {getParameterByName, updateQueryStringParameter} from './functions'
  export default {
    components: { multiselect, incomegraph, dashboardventas, dashboardingresos,dashboardefectividad,dashboardcotizaciones},
    data () {
      return {
        currentDashboard: 'ventas',
        currentFiltering: 'vendedores',
        firstLoad: true,
        loading: true,
        baseData: baseDataFromServer,
        showFilters: false,
        selectedSellers : [],
        selectedCollectors : [],
        sellerMultiOptions:  baseDataFromServer.lists.sellers,
        collectorMultiOptions:  baseDataFromServer.lists.collectors,
        sellersRequireUpdate: false,
        collectorsRequireUpdate: false,
        showError: false,
        filters: {
          start_date: moment().format('YYYY-MM-DD'),
          end_date: moment().format('YYYY-MM-DD'),
          sellers_condition: "",
          collectors_condition: ""
        },
      }
    },
    watch: {
      currentDashboard () {
        if (!this.firstLoad)
          this.fetchDashboardData(this.updateQueryString());
      },
      showFilters () {
        if (!this.firstLoad)
          this.updateQueryString();
      }
    },
    computed: {
      prettyStartDate () {
        return moment(this.filters.start_date).format('DD-MM-YYYY')
      },
      prettyEndDate () {
        return moment(this.filters.end_date).format('DD-MM-YYYY')
      }
    },
    methods: {
      aplicarSellersClicked () {
        this.sellersRequireUpdate = false;
        this.aplicarFiltros();
      },
      aplicarCollectorsClicked () {
        this.collectorsRequireUpdate = false;
        this.aplicarFiltros();
      },
      dashboardLoading (value) {
        this.loading = value;
      },
      removeSeller() {
        this.sellersRequireUpdate = true;
      },
      removeCollector () {
        this.collectorsRequireUpdate = true
      },
      resetFilters () {
        const start = moment().format('YYYY-MM-DD');
        const end = moment().format('YYYY-MM-DD');
        this.filters =  {
          start_date: start,
          end_date: end,
          sellers_condition: "",
          collectors_condition: ""
        }
        const picker = $('#rangepicker');
        picker.data('daterangepicker').setStartDate(start);
        picker.data('daterangepicker').setEndDate(end);
        this.selectedSellers = []
        this.selectedCollectors = []
        window.history.pushState('reset', 'SDM Dashboard', '/dashboard');
        this.fetchDashboardData(null)
        this.showFilters = false
      },

      fetchDashboardData (params) {
        // console.log(JSON.parse(decodeURIComponent(params)))
        if (this.firstLoad && params) {
          const paramObj = JSON.parse(decodeURIComponent(params));
          if (paramObj) {

            this.updateFiltersFromParams(paramObj);

            if (paramObj.current_dashboard) {
              this.currentDashboard = paramObj.current_dashboard;
            }

            if (paramObj.show_filters){
              this.showFilters = paramObj.show_filters;
            }

          }
        }

        if (this.currentDashboard === 'ventas') {
          this.$refs.dashboardventas.fetchVentasData(params);
        } else if(this.currentDashboard === 'ingresos') {
          this.$refs.dashboardingresos.fetchIngresosData(params);
        } else if (this.currentDashboard === 'efectividad') {
          this.$refs.dashboardefectividad.fetchEfectividadData(params);
        } else if (this.currentDashboard === 'cotizaciones') {
          this.$refs.dashboardcotizaciones.fetchCotizacionesData(params);
        }
      },

      succesfullyFetched () {
        this.loading = false;
        this.loadRangePicker();
        if (this.firstLoad) {
          this.firstLoad = false;
          askTour();
          twofactormessage(this.baseData.client.settings.two_factor_enabled === 'true');
        }
      },

      updateFiltersFromParams (params) {
        if (params) {
          const query = params;
          this.filters.start_date = query.start_date;
          this.filters.end_date = query.end_date;
          this.selectedSellers = this.sellerMultiOptions.filter(val => {
            const result = $.grep(query.sellers, function(e){
              return e === val.seller_code });
            return result.length > 0;
          })
          this.selectedCollectors = this.collectorMultiOptions.filter(val => {
            const result = $.grep(query.collectors, function(e){
              return e === val.collector_code });
            return result.length > 0;
          })

          this.filters.sellers_condition = query.sellers_condition;
          this.filters.collectors_condition = query.collectors_condition;
          this.currentDashboard = query.current_dashboard;
        }
      },

      errorOnFetching (xhr) {
        if (xhr.status === 403) {
          window.location.replace("/login");
        } else {
          this.showError = true;
        }
      },

      showDashboard (val) {
        this.selectedSellers = [];
        this.selectedCollectors =[];

        if('ventas' === val || 'efectividad' ===val){
          this.currentFiltering = "vendedores";
        }else {
          this.currentFiltering = "cobradores";
        }
        this.currentDashboard = val;
      },

      loadRangePicker() {
        if (!$('.daterangepicker').length) {
          const range = $('#rangepicker');
          const realstart = moment(this.filters.start_date);
          const realend = moment(this.filters.end_date);
          range.daterangepicker({
                    startDate: realstart,
                    endDate: realend,
                    showDropdowns: true,
                    showWeekNumbers: true,
                    timePicker: false,
                    timePickerIncrement: 1,
                    timePicker12Hour: true,
                    ranges: {
                      'Hoy': [moment(), moment()],
                      'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                      'Ultimos 7 dias':[moment().subtract(6, 'days'), moment()],
                      'Ultimos 30 dias': [moment().subtract(29, 'days'), moment()],
                      'Este mes': [moment().startOf('month'), moment().endOf('month')],
                      'Mes anterior': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    opens: 'left',
                    buttonClasses: ['btn btn-default'],
                    applyClass: 'btn-small btn-primary',
                    cancelClass: 'btn-small',
                    format: 'YYYY-MM-DD',
                    separator: ' a ',
                    locale: {
                      applyLabel: 'Aceptar',
                      cancelLabel: 'Borrar',
                      fromLabel: 'Desde',
                      toLabel: 'Hasta',
                      customRangeLabel: 'Personalizado',
                      daysOfWeek: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
                      monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                      firstDay: 1
                    }
                  }, (start, end) => {
                    $('#rangepicker span').html(start.format('DD-MM-YYYY') + ' - ' + end.format('DD-MM-YYYY'));
                    this.filters.start_date = start.format('YYYY-MM-DD');
                    this.filters.end_date = end.format('YYYY-MM-DD');
                  }
          );
          $('#rangepicker span').html(realstart.format('DD-MM-YYYY') + ' - ' + realend.format('DD-MM-YYYY'));
          range.on('apply.daterangepicker', (ev, picker) =>  {
            this.aplicarFiltros();
          });
        }
      },

      aplicarFiltros () {
        this.fetchDashboardData(this.updateQueryString());
      },

      updateQueryString () {
        const  data = {
          sellers: this.selectedSellers.map (val => val.seller_code),
          collectors: this.selectedCollectors.map (val => val.collector_code),
          start_date: this.filters.start_date,
          end_date: this.filters.end_date,
          sellers_condition: this.filters.sellers_condition,
          collectors_condition: this.filters.collectors_condition,
          current_dashboard: this.currentDashboard,
          show_filters: this.showFilters
        }
        const params = encodeURIComponent(JSON.stringify(data));
        const url =  updateQueryStringParameter(window.location.href.split('#')[0],'get', params);
        window.history.pushState('withparams', 'SDM Dashboard', url);
        return params;
      }
    },

    mounted () {
      const params = getParameterByName('get');

      if (params) {
        this.fetchDashboardData(encodeURIComponent(params));
      } else {
        this.fetchDashboardData(null)
      }
    },

    updated () {
      $('.match-height2').matchHeight();
    }

  }


  function twofactormessage(status) {

    if (!status && store.get('security_hide') !== true) {
      toastr.info('<div style="margin-bottom: 10px">' +
              'Mejore la seguridad de su Dashboard activando autenticación en dos pasos. ADGSystems recomienda ' +
              'utilizar este método de autenticación para poder garantizar la seguridad de sus datos.' +
              '<div  style="margin-top: 15px">' +
              '<a class="btn btn-success" href="/adminsettings">Activar Configuración</a>' +
              '</div><div  style="margin-top: 5px"><strong><a id="authdisable" href="#">No Volver a Mostrar</a></strong> </div> ' +
              '</div>', {timeOut: 12000, closeButton: true})
    }
    $('#authdisable').on('click',function () {
      store.set('security_hide', true);
    })

  }

  function askTour () {
    const madeTour = store.get('made_tour');
    if (!madeTour) {
      toastr.info('<div style="margin-bottom: 10px">' +
              'Bienvenido al SDM Dashboard. Hemos detectado que es la primera vez que accede; ¿Desea un hacer un Tour?' +
              '<div style="margin-top: 15px">' +
              '<button type="button" class="btn btn-success" id="hacerTour">Hacer Tour</button>' +
              '</div> ' +
              '</div>', {timeOut: 14000, closeButton: true})

      $('#hacerTour').on('click',()=> {
        makeTour();
        store.set('made_tour',true);
      })
    }
    store.set('made_tour',true);

  }

  function makeTour() {

    var intro = introJs();
    intro.setOption("nextLabel", " Siguiente ");
    intro.setOption("prevLabel", " Anterior ");
    intro.setOption("skipLabel", " Saltar ");
    intro.setOption("doneLabel", " Listo ! ");
    intro.setOptions({
      steps: [
        {
          intro: 'Por defecto el Dashboard carga con información del dia actual. ' +
                  'Cuando necesite desplegar los filtros del dashboard haga click en este botón',
          element: "#showfiltros"
        },
        {
          intro: "Este bloque muestra la cantidad de clientes y pedidos involucrados en la información que se muestra en el Dashboard",
          element: '#step1'
        },
        {
          intro: "Monto total de ventas realizadas en el periodo especificado",
          element: '#step2'
        },
        {
          intro: "Promedio de ventas por vendedor en el periodo especificado",
          element: '#step3'
        },
        {
          intro: "Monto promedio de cada pedido",
          element: '#step4'
        },
        {
          intro: "Este gráfico presenta, de cada vendedor, la cantidad de pedidos, monto total de ventas e " +
                  " información del pedido con mayor monto realizado por el vendedor. Si desea ocultar alguno de estos renglones, puede" +
                  " hacer click en su leyenda correspondiente en la base del gráfico",
          element: '#step5'
        },
        {
          intro: "Puede cambiar el tipo de gráfico seleccionando una de las opciones preestablecidas",
          element: '#step6'
        },
        {
          intro: "Aquí se presentan los clientes poseen el mayor monto vendido en el período y la cantidad de pedidos realizados",
          element: '#step7',
          position: 'top'
        },
        {
          intro: "Top marcas basadas en monto y cantidad total de productos vendidos en el periodo.",
          element: '#step8',
          position: 'top'
        },
        {
          intro: "Top productos basados en monto y cantidad total vendida en el periodo.",
          element: '#step9',
          position: 'top'
        }
      ]
    });
    intro.start()

  }

</script>