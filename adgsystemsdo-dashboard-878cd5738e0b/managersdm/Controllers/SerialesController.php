<?php
namespace Manager\Controllers;

use App\Http\Request;
use Manager\Repositories\ClientRepository;

class SerialesController extends BaseController
{

    private $clientRepository;

    public function __construct()
    {
        parent::__construct();
        $this->clientRepository = new ClientRepository();
    }

    public function index() {
        $cliente = Request::query()->get("cliente");
        $client_row = null;
        $seriales = null;
        if (!is_null($cliente)) {
            $client_row = json_decode(json_encode($this->clientRepository->getClientById($cliente)));
            $seriales = $this->toObject($this->clientRepository->getClientSerialNumbers($cliente));
        }
        return $this->view('seriales.index',[
            'clientes' => json_decode(json_encode($this->clientRepository->getAllClients())),
            'cliente' => $client_row,
            'seriales' => $seriales]
        );
    }

    public function generar () {
        $client = Request::body()->get('cliente');
        $this->clientRepository->createSerialNumber($client);
        return $this->redirect("/manager/seriales?cliente=$client");
    }

    public function borrar() {
        $client = Request::body()->get('cliente');
        $serial = Request::body()->get('serial_number');
        $this->clientRepository->deleteSerialNumber($client, $serial);
        return $this->redirect("/manager/seriales?cliente=$client");
    }


}