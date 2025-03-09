<?php
namespace Manager\Controllers;


use App\Http\Request;
use Manager\Repositories\ClientRepository;
use Manager\Repositories\UserRepository;

class UsersController extends BaseController
{

    public $userRepository;
    public $clientsRepository;

    public function __construct()
    {
        parent::__construct();
        $this->clientsRepository = new ClientRepository();
        $this->userRepository = new UserRepository();
    }

    public function index()
    {
        return $this->baseView();
    }


    public function getUserData () {
        $id = Request::query()->get('edit_user');
        if (!is_null($id)) {
            return $this->baseView(['userData' => json_decode(json_encode($this->userRepository->loadAdgUserData($id)))]);
        }

        return $this->baseView(['errors' => 'Usuario no existente.']);
    }

    public function editUser () {
        $udata = Request::body()->all();
        try {
            $this->userRepository->editUser($udata);
            return $this->baseView(['success' => 'Usuario editado satisfactoriamente']);
        }catch (\Exception $e) {

            return $this->baseView(['errors' => 'No se pudo editar el usuario.']);
        }
    }

    public function deleteUser () {
        $id = Request::body()->get('id');

        if (!is_null($id)) {
            $this->userRepository->deleteUser($id);
            return $this->baseView(['success' => 'Usuario eliminado satisfactoriamente']);
        }

        return $this->baseView(['errors' => 'No se pudo eliminar el usuario.']);
    }
    public function createUser()
    {
        $udata = Request::body()->all();
        $udata['user_type'] = 'normal';
        $udata['user_company'] = '0001';
        $udata['user_devices'] = 0;
        $udata['user_profile_picture'] = '';
        try {
            $this->userRepository->createUser($udata);
            return $this->baseView(['success' => 'Usuario creado satisfactoriamente']);
        }catch (\Exception $e) {
        }
        return $this->baseView(['errors' => 'No se pudo crear el usuario.']);
    }

    public function baseView ($params = []) {
        return  $this->view('users.index', array_merge($params,
            [
                'clients' => json_decode(json_encode($this->clientsRepository->getAllClients())),
                'users' => json_decode(json_encode($this->userRepository->getAllUsers())) ]));
    }
}