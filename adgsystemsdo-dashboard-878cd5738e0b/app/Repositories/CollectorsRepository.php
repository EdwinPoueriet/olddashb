<?php
/**
 * Created by PhpStorm.
 * User: nelson
 * Date: 20/05/17
 * Time: 12:11 PM
 */

namespace App\Repositories;


class CollectorsRepository extends Repository
{

    public function collectorsList($company)
    {
        return $this->clientCon->query(
            "SELECT 
			collector_id, 
			collector_code, 
			collector_name
			FROM collectors 
			WHERE company_id = :company_id ORDER BY collector_code ASC"
            , ['company_id' => $company]);
    }
}