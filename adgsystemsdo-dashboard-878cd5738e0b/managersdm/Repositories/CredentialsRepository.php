<?php

namespace Manager\Repositories;


class CredentialsRepository extends BaseRepository
{

    public function getCredentials($userid, $company)
    {
        $this->con->bind('userid',$userid);
        $this->con->bind('company',$company);
       return $this->con->row(
            ' SELECT * from adgsoft_maps.credentials where company_id = :company and user_id = :userid');
    }

    public function saveCredentials ($credentials, $id) {
        $string = '';
        foreach ($credentials as $name => $val) {
            $string.= $name.' = '.$val.', ';
        }
        $string = rtrim($string, ', ');
        return $this->con->query(
            'UPDATE adgsoft_maps.credentials 
                   SET '.$string.' WHERE credential_id = :cred', ['cred' => $id ]);
    }

    public function getCredentialsNames () {
      $res = $this->con->query("
        SELECT COLUMN_NAME 
FROM `INFORMATION_SCHEMA`.`COLUMNS` 
WHERE `TABLE_SCHEMA`= 'adgsoft_maps'
        AND `TABLE_NAME`= 'credentials'
        ");
      $columns = [];
      foreach ($res as $r) {
          array_push($columns, $r['COLUMN_NAME']);
      }
        return array_slice($columns,3);
    }

}