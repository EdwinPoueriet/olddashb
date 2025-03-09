<?php

namespace Manager\Controllers;

use App\Http\Request;
use Manager\Repositories\UserRepository;

class AuthController extends BaseController
{

    private $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function login () {
        $this->view('pages.login');
    }

    public function validate () {
        $username = Request::body()->get('username');
        $password = Request::body()->get('password');

        if (is_null($username) || is_null($password)) {
            $this->view('pages.login',['errors' => 'Complete el campo de usuario y contraseña.']);
        }

        $user = $this->userRepository->loadUserData(null, $username);

        if ($user) {
            if (password_verify($password,$user['password'])) {
                Request::instance()->getSession()->set('user',$user);
                return $this->redirect('/manager/dashboard');
            }
        }
        return $this->view('pages.login',['errors' => 'Credenciales no válidos.']);
    }

}