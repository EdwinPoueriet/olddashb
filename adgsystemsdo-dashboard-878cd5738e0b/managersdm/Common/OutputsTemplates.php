<?php

namespace Manager\Common;

trait OutputsTemplates {

    public  static $globalVariables = [];

    public function view ($view, $data = []) {
        try {
            $views = __DIR__ . '/../Views';
            $cache = __DIR__ . '/../cache';

            $this->createIfNotExists($cache);

            $blade = new \Philo\Blade\Blade([$views], $cache);

            $this->blade = $blade;

            echo $blade->view()->make($view,array_merge($data, self::$globalVariables));

        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        return true;
    }

    public function setGlobals ($array = []) {
        self::$globalVariables = array_merge($array, self::$globalVariables);
    }

    private function createIfNotExists ($dir) {
        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }
    }

}