<?php


namespace Manager\Repositories;


class CompanyRepository extends BaseRepository
{

    public function __construct()
    {
        parent::__construct();

    }

    public function companiesByUser ($user)
    {
        if (is_numeric(intval($user))) {
            $this->con->bind('uid',$user);
            $user = $this->con->row('SELECT user_database FROM adgsoft_maps.adg_users where user_id = :uid');
        }

        return $this->con->query('SELECT * from '. $user['user_database'] .'.companies');

    }
}