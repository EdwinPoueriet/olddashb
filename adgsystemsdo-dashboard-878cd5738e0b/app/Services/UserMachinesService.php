<?php

namespace App\Services;
use Manager\Common\Database\DB;

class UserMachinesService extends DB
{

    /**
     * @var \Manager\Common\Database\Core\DBClass
     */
    public $con;

    public function __construct()
    {
        $this->con = DB::getConnection();
    }

    public function registerAuthorization ($user, $fingerprint,$data) {
        $token = uniqid();
        $this->con->query('INSERT INTO adgsoft_maps.machine_authorizations(user_id, fingerprint,token,data) VALUES (:uid,:fg,:token,:data)',[
            'uid' => $user,
            'fg' => $fingerprint,
            'token' => $token,
            'data' => $data
        ]);
        return $token;
    }

    public function completeMachineAuthorization ($token)
    {
        $authorization = $this->findAuthorization($token);
        if ($authorization) {
            $this->con->query('INSERT INTO adgsoft_maps.user_machines(user_id, fingerprint, data) VALUES(:uid,:fg,:data)',[
                'uid' => $authorization['user_id'],
                'fg' => $authorization['fingerprint'],
                'data' => $authorization['data']
            ]);
            $this->markAsAuthorized($authorization['authorization_id']);
        }else return false;
        return true;
    }

    public function findAuthorization ($token) {
        return $this->con->row('SELECT * FROM adgsoft_maps.machine_authorizations WHERE token = :token',
            ['token' => $token]);
    }

    public function markAsAuthorized ($authorization_id) {
        return $this->con->query('UPDATE adgsoft_maps.machine_authorizations SET authorized = 1 WHERE authorization_id = :auid',
            ['auid' => $authorization_id]);
    }

    /**
     * @param $user
     * @param $fingerprint
     *
     */
    public function machineIsValid ($user, $fingerprint)
    {
        $result = $this->con->row('SELECT * FROM adgsoft_maps.user_machines WHERE user_id = :uid and fingerprint = :fg',[
           'uid' => $user,
            'fg' => $fingerprint
        ]);

       return $result;
    }

    /**
     * @param $fingerprint
     * @param $data
     */
    public function registerMachine ($user,$fingerprint, $data) {
        return $this->con->query('INSERT INTO adgsoft_maps.user_machines(user_id, fingerprint, data) VALUES (:uid,:fg,:data)',[
           'uid' => $user,
            'fg' => $fingerprint,
            'data' => $data
        ]);
    }


}