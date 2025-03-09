<?php

namespace App\Common;

use Symfony\Component\HttpFoundation\Response;
trait SendsResponses
{
    public function redirect ($url) {
        $r = new \App\Common\BlankRedirectResponse($url);
       return $r->send();
    }

    public function response ($content,$code = Response::HTTP_OK) {
        $response =  new \Symfony\Component\HttpFoundation\Response(
            $content,
            $code,
            array('content-type' => 'text/html'));
        return  $response->send();
    }

    public function jsonResponse ($content,$code = Response::HTTP_OK) {
        if (!$this->isJson($content)) {
            $content = json_encode($content);
        }
        $response = new Response(
            $content,
            $code,
            array('content-type' => 'application/json'));
        return $response->send();
    }

    private function isJson($string) {
        if (is_string($string)) {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        } else
            return false;
    }

}