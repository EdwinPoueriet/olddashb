<?php
namespace App\Controllers;

use App\Http\Request;
use Manager\Repositories\SettingsRepository;

class SettingsController extends BaseController
{

    public $settingsRepository;
    public function __construct()
    {
        $this->settingsRepository = new SettingsRepository();
        parent::__construct();
    }

    public function index()
    {
        return $this->view('settings.adminSettingsIndex', ['settings' => $this->settingsRepository->getClientSettings(
            self::$client_details['client_id'])]);
    }

    public function saveSettings()
    {
        $settings = Request::body();


        foreach (SettingsRepository::$settings as  $setting => $value) {
            $this->settingsRepository->insertSetting(self::$client_details['client_id'], $setting,
                $settings->has($setting) ? 'true' : 'false');
        }

        return $this->view('settings.adminSettingsIndex',
            ['message' => 'Cambios guardados !',
            'settings' => $this->settingsRepository->getClientSettings(
            self::$client_details['client_id'])]);
    }

}