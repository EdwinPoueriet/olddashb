<?php
namespace App\Common;

trait LocationFunctions {
    public function checkWasInLocation ($sale_lat, $sale_lng, $customer_lat, $customer_lng, $meters) {

        if (!$this->notNull($sale_lat) || !$this->notNull($sale_lng) || !$this->notNull($customer_lat) || !$this->notNull($customer_lng)) {
            return null;
        }

        return $this->distanceBetween($sale_lat,$sale_lng,$customer_lat,$customer_lng) <= $meters;
    }

    private function notNull ($val){
        return $val && $val !== "" && $val !== "0";
    }

    public function distanceBetween( $latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000)
    {
        // convert from degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
                cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
        return $angle * $earthRadius;
    }
}