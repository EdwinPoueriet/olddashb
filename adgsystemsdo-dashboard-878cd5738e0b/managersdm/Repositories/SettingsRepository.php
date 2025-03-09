<?php

namespace Manager\Repositories;


class SettingsRepository extends BaseRepository
{

    public  static $settings = [
        'two_factor_enabled' => 'false',
        'machine_authorization' => 'true'
    ];


    public function updateClientSettings ($client)
    {
        $settings = $this->getClientSettings($client);
        $toinsert = array_diff(self::$settings,$settings);
        if (count($toinsert) > 0) {
            foreach ($toinsert as $key => $val) {
                $this->insertSetting($client, $key, $val);
            }
        }

    }

    public function getClientSettings($client)
    {
        $client = $this->con->query("SELECT *
        FROM adgsoft_maps.client_settings
        WHERE client_id = :cid",[
            'cid' => $client
        ]);

        $arrr = [];
        foreach ($client as $val) {

            $arrr[$val['setting_key']] = $val['setting_value'];
        }

        return $arrr;
    }


    public function createDefaultSettings($client_id)
    {
        try {
            $this->con->beginTransaction();

            foreach (self::$settings as $key => $value) {
                $this->insertSetting($client_id, $key, $value);
            }

            $this->con->executeTransaction();
            return true;
        } catch (\Exception $e) {
            $this->con->rollBack();
            return false;
        }
    }


    public function insertSetting($client, $settingkey, $value = "")
    {
        if (array_key_exists($settingkey, self::$settings)) {
            $this->con->bind('cid',$client);
            $this->con->bind('value',$value);
            $this->con->bind('value2',$value);
            $this->con->bind('keyy',$settingkey);
            return $this->con->query('INSERT INTO adgsoft_maps.client_settings(client_id, setting_key,setting_value) 
       VALUES (:cid,:keyy, :value) ON DUPLICATE KEY UPDATE setting_value = :value2');
        }
        return false;
    }

}