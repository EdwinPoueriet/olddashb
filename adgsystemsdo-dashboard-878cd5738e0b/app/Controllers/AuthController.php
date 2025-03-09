<?php
namespace App\Controllers;

use App\Common\SendsResponses;
use App\Http\Request;
use App\Services\Mailer;
use App\Services\UserMachinesService;
use Manager\Common\Database\DB;
use Manager\Repositories\ClientRepository;
use Manager\Repositories\SettingsRepository;
use Manager\Repositories\UserRepository;
use PragmaRX\Google2FA\Google2FA;
use App\Common\OutputsTemplates;
use Sinergi\BrowserDetector\Browser;
class AuthController {

    use OutputsTemplates;
    use SendsResponses;

    public $GoogleTf;
    public $con;
    public $userRepository;
    public $settingsRepository;
    public $machinesService;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->machinesService = new UserMachinesService();
        $this->settingsRepository = new SettingsRepository();
        $this->con = DB::getConnection();
        $this->GoogleTf = new Google2FA();
    }


    public function usernameAndPasswordLogin()
    {
        $error = null;
        $username = Request::body()->get('username');
        $password =  Request::body()->get('password');
        $fingerprint =  Request::body()->get('fingerprint');
        $fingerprint_data =  Request::body()->get('fingerprint_data');

        if (is_null($username) || is_null($password)) {
            return $this->view('auth.login',['errors' => 'No puede dejar campos en blanco.']);
        }

        $user_validation_result = $this->ValidateUser($username,$password);

        if ($user_validation_result) {
            $clientid = $user_validation_result["client_id"];
            $client_settings = $this->settingsRepository->getClientSettings($clientid);
            $machine_verification_required = array_key_exists('machine_authorization', $client_settings) && $client_settings['machine_authorization'] == 'false' ;

            if ($username === 'master') {
                $_SESSION["user_id"] = $user_validation_result["user_id"];
                $_SESSION["client_id"] = $clientid;
                return $this->masterSelectClient();
            }

            if (is_null($user_validation_result['user_email'])) {
                return $this->view('generalPurposeMessage',['title' => 'Mensaje informativo',
                    'message' => 'Dado a mejoras en la seguridad del sistema, necesitamos asociar
                una cuenta de correo a su cuenta del SDM Dashboard. Comuníquese con nosotros indicando que correo de contacto desea utilizar.']);
            }

            if (!$machine_verification_required && !$this->machinesService->machineIsValid($user_validation_result['user_id'], $fingerprint)) {

                try {
                  $this->con->beginTransaction();
                    $token = $this->machinesService->registerAuthorization($user_validation_result['user_id'], $fingerprint, $fingerprint_data);
                    $this->sendMachineValidateMail($user_validation_result['user_email'], $fingerprint_data,$token);
                    $this->con->executeTransaction();
                    return $this->view('auth.login',['info' =>
                        'Este equipo no esta autorizado para acceder al Dashboard. Hemos enviado un email de verificación a <b>'.
                        $user_validation_result['user_email'].'</b>. Por favor verifique. '
                    ]);
                 }  catch ( \Exception $e) {
                    $this->con->rollBack();
                    return $this->view('auth.login',['errors' => 'ERROR:reg_machine_mail_failed - Ocurrio un error al acceder. 
                    Informenos de este error llamando a nuestra oficina.']);
                }

            } else {
                if (array_key_exists('two_factor_enabled', $client_settings)
                    && $client_settings['two_factor_enabled'] == 'true') {

                    $_SESSION['attempt_user_id'] = $user_validation_result["user_id"];
                    $_SESSION["attempt_client_id"] = $clientid;
                    $_SESSION['attempt_two_factor_secret'] = $user_validation_result["two_factor_secret"];
                    return $this->redirect('/securelogin');

                } else {
                    $_SESSION["user_id"] = $user_validation_result["user_id"];
                    $_SESSION["client_id"] = $user_validation_result["client_id"];
                    $_SESSION["user_company"] = $user_validation_result["user_company"];
                    return $this->redirect('/dashboard');
                }
            }
        } else {

            $error = "Usuario o clave incorrecta";

        }

        return $this->view('auth.login',['errors' => $error]);
    }

    public function selectClient () {
        $client = Request::body()->get('client');

        $sql =  $this->con->row("SELECT
        user_company
        FROM adgsoft_maps.adg_users
        WHERE  client_id = ".$client." ");

        $_SESSION["user_company"] = $sql['user_company'];
        if ($client) {
            $_SESSION["client_id"] = $client;
            (new UserRepository())->changeUserClient($_SESSION['user_id'], $client);
        }
        return $this->redirect('/dashboard');
    }

    function masterSelectClient () {
        return $this->view('auth.selectClient',
            ['clients' => (new ClientRepository())->getAllClients()]);
    }

    function validateToken () {
        $token = Request::query()->get('token');
        if (is_null($token)) {
            return $this->view('generalPurposeMessage',['message' => 'No se pudo verificar el equipo. Contáctenos y notifique este error.']);
        }
        $res = $this->machinesService->completeMachineAuthorization($token);
        if ($res) {
            return $this->view('generalPurposeMessage',['message' => 'Equipo autorizado satisfactoriamente.']);
        }
        return $this->view('generalPurposeMessage',['message' => 'La solicitud de autorización fue creada pero no pudo ser completada. 
         Contáctenos y notifique este error.']);
    }

    function sendMachineValidateMail ($to, $fingerprintdata,$token ) {
        $mailer = new Mailer();
        $template = $this->renderedView('mails.machineNotif',
            [
                'button' => true,
                'data' => $this->parseFingerprint($fingerprintdata),
                'token' => $token
            ])->render();

        try {
            $mailer->send($to,'Autorice este equipo',$template);
            return true;
        }catch (\Exception $e) {
            return false;
        }

    }

    function parseFingerprint ($fg) {
        $data    = json_decode($fg);
        $result = [];
        $result['plataforma'] = $data->navigator_platform;
        $result['pais'] = $data->country;
        $result['ip'] = $data->ip;
        $result['navegador'] = (new Browser($data->user_agent))->getName();
        return $result;


    }

    function ValidateUser($username, $password)
    {
        return  $this->con->row("SELECT
        user_id, client_id,two_factor_secret, user_email, user_company
        FROM adgsoft_maps.adg_users
        WHERE (user_name = :user_name OR user_email = :user_name2)
        AND user_password = :user_password",[
              'user_name' => $username,
              'user_name2' => $username,
              'user_password' => $password
          ]);
    }


    public function secureLogin () {

        if (!isset($_SESSION['attempt_user_id'])) {
            return $this->redirect('/login');
        }

        if (isset($_SESSION['attempt_two_factor_secret']) && !is_null($_SESSION['attempt_two_factor_secret'])) {
            return $this->view('auth.secureLogin',['firstTime' => false]);
        } else {
            $secret  = $this->GoogleTf->generateSecretKey();
            $qr = $google2fa_url = $this->GoogleTf->getQRCodeInline(
                'SDM Dashboard',
                $_SESSION['attempt_user_id'],
                $secret
            );
            return $this->view('auth.secureLogin',['firstTime' => true , 'QrCode' => $qr, 'key' => $secret]);
        }

    }

    public function validateKey () {
        if (isset($_SESSION['attempt_user_id'])) {
            $key = Request::body()->get('key');

            if ($key) { //It was a first time verify. $_SESSION['attempt_two_factor_secret'] <= EMPTY
                //Save the token
                $this->userRepository->saveUserTfKey($_SESSION['attempt_user_id'], $key);
            } else {
                $key =  $_SESSION['attempt_two_factor_secret'];
            }
            $user_key = Request::body()->get('user_key');
            if ($this->GoogleTf->verifyKey($key, $user_key)) {
                $_SESSION['user_id'] = $_SESSION['attempt_user_id'];
                $_SESSION['client_id'] = $_SESSION['attempt_client_id'];
                unset($_SESSION['attempt_two_factor_secret']);
                unset($_SESSION['attempt_user_id']);
                return $this->redirect('/dashboard');
            } else {
                return $this->view('auth.secureLogin',['firstTime' => false , 'errors' => 'Código inválido, inténtelo de nuevo.']);
            }
        } else {
            return $this->redirect('/login');
        }

    }

}
