<?php

namespace Manager\Repositories;

use App\Legacy\Session;
use Exception;
class UserRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct();
    }

    public function saveUserTfKey ($user,$key) {
        return $this->con->query('UPDATE adgsoft_maps.adg_users SET two_factor_secret =  :tf WHERE user_id = :cid',
            ['tf'=> $key, 'cid' => $user]);
    }

    public function deleteUser ($id) {
        return $this->con->query('DELETE FROM adgsoft_maps.adg_users WHERE user_id = :uid',['uid' => $id]);
    }


    public function changeUserClient ($user, $cid) {

        $client_db = $this->con->row('SELECT client_database FROM adgsoft_maps.clients WHERE client_id = :cid',
            ['cid' => $cid])['client_database'];
        return $this->con->query('UPDATE adgsoft_maps.adg_users SET client_id =
 :cid, user_company = :company WHERE user_id = :uid',['cid' => $cid, 'uid' => $user ,
            'company' => $this->getClientFirstValidCompany($client_db)]);
    }

    private function getClientFirstValidCompany ($cdb) {
       return $this->con->row('SELECT company_id from '.$cdb.'.companies limit 1')['company_id'];
    }

    public function editUser($udata) {
        return $this->con->query(
            'UPDATE adgsoft_maps.adg_users
                  SET user_name = :user_name,
                  user_first_name = :user_first_name,
                  user_password = :user_password,
                  user_database = :user_database,
                  user_email = :user_email,
                  client_id = :client_id
                  WHERE user_id = :user_id
                      ', $udata);
    }

    public function loadAdgUserData ($id) {

        $this->con->bind('id',$id);
        $res = $this->con->row('SELECT * FROM adgsoft_maps.adg_users WHERE user_id = :id');

        if (!is_null($res)) {
            return $res;
        }

        throw new Exception('Ocurrio un error obteniendo el usuario.');
    }
    public function createUser ($udata) {
        try {
            $this->con->beginTransaction();
            $this->con->query(
                'INSERT INTO adgsoft_maps.adg_users
                  (user_name,user_email,user_password,user_database,user_type,user_company, user_first_name,
                   user_devices,user_profile_picture,client_id)
                   VALUES (:user_name,:user_email,:user_password,:user_database,:user_type,:user_company, 
                   :user_first_name,:user_devices,:user_profile_picture ,:client_id)',$udata);
            return true;
        }catch (Exception $e) {
            $this->con->rollBack();
            throw $e;
        }

    }

    public function loadUserData ($id, $username = null) {
        if (is_null($username)) {
            $this->con->bind('id',$id);
            $res = $this->con->query('SELECT * FROM users WHERE user_id = :id');
        } else {
            $this->con->bind('uname',$username);
            $res = $this->con->row('SELECT * FROM users WHERE username = :uname');
        }

        if (!is_null($res)) {
            return $res;
        }

        throw new Exception('Ocurrio un error obteniendo el usuario autenticado.');
    }

    public function getAllUsers () {
        return $this->con->query('SELECT a.*, c.client_name ,c.client_id, CASE WHEN EXISTS (
                                                          SELECT 1 FROM adgsoft_maps.clients WHERE c.admin_user_id = a.user_id
                                                            ) THEN "Admin" ELSE "Normal" END user_type 
                                          FROM adgsoft_maps.adg_users  a
                                        LEFT JOIN adgsoft_maps.clients c ON c.client_id = a.client_id
                                        '

            );
    }


}