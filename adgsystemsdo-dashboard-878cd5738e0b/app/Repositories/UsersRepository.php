<?php
/**
 * Created by PhpStorm.
 * User: nel
 * Date: 26/07/17
 * Time: 11:01 AM
 */

namespace App\Repositories;


class UsersRepository extends Repository
{

    public function getUsersByCompany ($company)
    {
        return $this->clientCon->query('');
    }

}