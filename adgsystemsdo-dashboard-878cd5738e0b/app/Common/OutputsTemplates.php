<?php

namespace App\Common;
trait OutputsTemplates {

    public static $globalVariables = [];

    public function view ($view, $data = []) {
        try {
           echo $this->renderedView($view,array_merge($data, self::$globalVariables));
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function renderedView ($view, $data = []) {
        $views = __DIR__ . '/../Views';
        $cache = __DIR__ . '/../cache';

        $blade = new \Philo\Blade\Blade([$views], $cache);
        $this->blade = $blade;
        return $blade->view()->make($view, array_merge($data, self::$globalVariables));
    }

    public function setGlobals ($array = []) {
        self::$globalVariables = array_merge($array, self::$globalVariables);
    }

}