<?php

namespace App\Repositories;

class DevolucionesRepository extends Repository
{
    public function getDevolucion ($id) {
        return $this->clientCon->row(
            'SELECT rh.*, seller_name,  (
                  SELECT SUM(item_price*item_quantity)
                  FROM returns_details rd
                  WHERE rh.return_id = rd.return_id
                ) return_total FROM returns_header rh 
         INNER JOIN sellers s on s.seller_code = rh.seller_code
          WHERE return_id = :id', ['id' => $id]);
    }

}