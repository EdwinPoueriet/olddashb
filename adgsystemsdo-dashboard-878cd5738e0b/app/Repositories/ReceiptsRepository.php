<?php

namespace App\Repositories;

class ReceiptsRepository extends Repository
{

    public function getReceipt ($id) {
        return $this->clientCon->row('
        SELECT rh.*, bank_name, co.collector_code, collector_name
        from receivables_header rh
        INNER JOIN collectors co on co.collector_code = rh.collector_code
        LEFT JOIN banks ba on ba.bank_code = rh.bank_code
        where receipt_income_code = :id',['id'=>$id]);
    }

}