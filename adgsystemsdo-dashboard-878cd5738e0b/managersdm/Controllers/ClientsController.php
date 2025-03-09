<?php

namespace Manager\Controllers;

use App\Http\Request;
use Manager\Repositories\ClientRepository;
use Manager\Repositories\UserRepository;
use Manager\Repositories\SettingsRepository;
class ClientsController extends BaseController
{

    /**
     * @var $clientRepository ClientRepository
     * @var $userRepository UserRepository
     */
    private $clientRepository;
    private $userRepository;
    private $settingsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->settingsRepository = new SettingsRepository();
        $this->clientRepository = new ClientRepository();
        $this->userRepository = new UserRepository();
    }
    public function index()
    {
       return $this->baseView();
    }

    public function createClient()
    {

        $name = Request::body()->get('client_name');
        $host = Request::body()->get('client_host');
        $database = Request::body()->get('client_database');
        $api = Request::body()->get('api_key');
        $devices = Request::body()->get('devices_amount');

        if (!is_null($name) && !is_null($host) && !is_null($database)  && !is_null($api)) {
            try {
                $res = $this->clientRepository->createClient($name,$host,$database,$api,$devices);

                if (is_numeric($res) ) {
                   $set =  $this->settingsRepository->createDefaultSettings($res);
                    if ($set) {
                        return $this->baseView(['success' => 'Cliente creado satisfactoriamente.']);
                    } else {
                        return $this->baseView(['errors' => 'Error al crear settings de cliente']);
                    }
                }
            }catch (\Exception $e) {
                return $this->baseView(['errors' => $e->getMessage()]);
            }

        }

        return $this->baseView(['errors' => 'Campos incompletos']);
    }

    public function associateClient()
    {
        $client  = Request::body()->get('client_id');
        $user  = Request::body()->get('user_id');

        if (!is_null($user) && !is_null($client)) {
            if ($this->clientRepository->associateClientAndAdminUser($client,$user)) {
                return $this->baseView(['success' => 'Asociacion creada satisfactoriamente.']);
            }
        }

        return $this->baseView(['errors' => 'No se pudo crear la asociacion.']);

    }

    public function baseView ($params = []) {
        return $this->view('clients.index', array_merge([
            'adg_clients' => json_decode(json_encode($this->clientRepository->getAllClients())) ,
            'adg_users' => json_decode(json_encode($this->userRepository->getAllUsers())),
            'client_list' => json_decode(json_encode($this->clientRepository->getClientsTable()))
        ],$params)) ;
    }
}