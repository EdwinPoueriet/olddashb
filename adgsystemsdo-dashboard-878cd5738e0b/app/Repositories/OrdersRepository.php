<?php
/**
 * Created by PhpStorm.
 * User: nelson
 * Date: 16/05/17
 * Time: 02:36 PM
 */

namespace App\Repositories;


class OrdersRepository extends Repository
{

    public function getOrder ($id) {
        return $this->clientCon->row(
            'SELECT oh.*, seller_name FROM orders_header oh 
INNER JOIN sellers s on s.seller_code = oh.seller_code
          WHERE order_id = :id', ['id' => $id]);
    }

}