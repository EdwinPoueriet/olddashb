<?php
/**
 * Created by PhpStorm.
 * User: nelson
 * Date: 08/05/17
 * Time: 11:14 AM
 */

namespace App\Repositories;


class SellersRepository extends Repository
{

    public function sellersList($company)
    {
        return $this->clientCon->query(
            "SELECT 
			seller_id, 
			seller_code, 
			seller_name, 
			seller_phone 
			FROM sellers 
			WHERE company_id = :company_id ORDER BY seller_code ASC"
        , ['company_id' => $company]);
    }
}