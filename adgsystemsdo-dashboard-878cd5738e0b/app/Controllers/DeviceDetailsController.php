<?php

namespace App\Controllers;

use App\Http\Request;
use App\Legacy\Database;
use App\Legacy\Session;
use App\Services\UserDevicesService;
use Manager\Repositories\ClientRepository;
use Managersdm\Common\Database\DB;
use function MongoDB\BSON\toJSON;


class DeviceDetailsController extends BaseController {

    public $UserService;

    public function __construct()
    {
        $this->UserService = new UserDevicesService();
        parent::__construct();
    }

    public function getClientLatestSDMVersion () {
        $parsedUrl = parse_url(self::$client_details['client_host']);
        $host = explode('.', $parsedUrl['host']);
        $subdomain = $host[0];
        $rutajson = __DIR__."/../../../apis/".$subdomain.'/update.json';

        if (is_file($rutajson)) {
            $str = file_get_contents($rutajson);
            $str = trim($str, "\xEF\xBB\xBF");
            $data = json_decode($str,true);

            return $data['url'];
        }


        return null;

    }

    public function getUserDevices () {
        $this->view('userdevices.userDevicesIndex',
            ['users' => $this->UserService->getUsers(),
                'sdmlink' => $this->getClientLatestSDMVersion(),
            ]);
    }

    public function getUserUpdatesDetails($id)
    {
        if ($id)
            return $this->jsonResponse($this->UserService->getUpdates($id));
        return $this->jsonResponse('Usuario no seleccionado', 400);
    }

    public function serials(){
        return $this->jsonResponse($this->UserService->serials(), 200);
    }
    public function serialsUpdate(){
        $request = $_REQUEST;
        return $this->jsonResponse($this->UserService->usersUpdate($request), 200);
    }

    public function getclienthost(){
        $parsedUrl = parse_url(self::$client_details['client_host']);
        $url = "http://".$parsedUrl['host']."/update.json";
        $data = file_get_contents($url);
        return $this->jsonResponse($data, 200);

    }
    public function users(){
        return $this->jsonResponse($this->UserService->users(), 200);
    }


}
