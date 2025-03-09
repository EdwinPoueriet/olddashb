<?php

namespace Manager\Controllers;


use App\Http\Request;
use Manager\Repositories\CompanyRepository;
use Manager\Repositories\UserRepository;
use Manager\Repositories\CredentialsRepository;
class CredentialsController extends BaseController
{

    /**
     * @var $userRepository UserRepository
     */
    public $userRepository;
    public $credentialsRepository;
    public $companiesRepository;

    public function __construct()
    {
        parent::__construct();
        $this->credentialsRepository = new CredentialsRepository();
        $this->userRepository = new UserRepository();
        $this->companiesRepository = new CompanyRepository();
    }

    public function saveCredentials()
    {
        try {
            $credentials = [];
            foreach ($this->credentialsRepository->getCredentialsNames() as $credentialname) {
                $val = Request::body()->get($credentialname);
                $credentials[$credentialname] = $val ? '1' : '0';
            }
            $result = $this->credentialsRepository->saveCredentials($credentials, Request::body()->get('credential_id'));
            return $this->baseView(['success'=> 'Cambios guardados satisfactoriamente.']);
        } catch (\Exception $e) {
            return $this->baseView(['errors'=> 'No se pudo completar la solicitud.']);
        }

    }
    public function index () {
        try {
            $user = Request::query()->get('user_id');
            $company = Request::query()->get('company_id');
            $companies = $user ?json_decode(json_encode($this->companiesRepository->companiesByUser($user))): null;
            $credentials = null;
            if (!is_null($user) && !is_null($company)) {
                $credentials =  $this->credentialsRepository->getCredentials($user,$company);
            }
            return $this->baseView(
                [
                    'companies' => $companies,
                    'selected_user' => $user,
                    'selected_company' => $company,
                    'credentials' => $credentials
                ]);
        }catch (\Exception $e) {
            return $this->baseView(['errors'=> 'No se pudo completar la solicitud.']);
        }

    }

    public function baseView ($params = []) {
        return $this->view('pages.credentials', array_merge([
            'adg_users' => json_decode(json_encode($this->userRepository->getAllUsers()))
        ],$params)) ;
    }

}