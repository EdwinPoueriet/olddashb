<?php

namespace App\Legacy;

use Manager\Repositories\SettingsRepository;
use App\Common\OutputsTemplates;
class Session extends Database
{
  
    use OutputsTemplates;
    public static
        $document_companies,
        $user_name, $user_id, $user_database, $default_company, $catalogue_company,
        $user_first_name, $company_cxc, $company_details, $con, $isAdmin, $user_email,$client_details,$client_settings, $module_by_company;

    protected static $connection = null;

    public static function documentCompaniesString () {
        return implode(',',self::$document_companies);
    }
    public function getAllParameters () {
        return [
            'user_name' => self::$user_name,
            'user_id' =>  self::$user_id,
            'user_first_name' => self::$user_first_name,
            'catalogue_company'   => self::$catalogue_company ,
            'user_database' => self::$user_database,
            'default_company' =>  self::$default_company ,
            'company_details' =>  self::$company_details,
            'client_details' => self::$client_details,
            'client_settings' => self::$client_settings,
            'isAdmin' => self::$isAdmin,
            'user_email' => self::$user_email,
            'document_companies' => self::$document_companies,
            'modules'=>self::$module_by_company
        ];
    }

    function __construct()
    {
        self::$con = $this::getDbInstance();
        $this->ValidateSession();

    }

    public function ValidateSession()
    {
        try {
            if (self::$user_name == null || self::$user_id == null || self::$catalogue_company == null ||
                self::$user_database == null || self::$default_company == null || self::$company_details == null ||
                self::$client_settings == null ) {
                $SQL = "
                SELECT user_company, user_id, user_name, user_email, user_first_name
                FROM adg_users WHERE user_id = :user_id
                ";

                try {

                    $user_info_sql = self::$con->prepare($SQL);

                    $user_info_sql->bindParam(':user_id', $_SESSION['user_id']);

                    $user_info_sql->execute();

                    $user_info_sql = $user_info_sql->fetch(\PDO::FETCH_ASSOC);

                } catch (\Exception $e) {

                    throw $e;

                }

                self::$user_id = $user_info_sql['user_id'];
                self::$user_first_name = $user_info_sql['user_first_name'];
                self::$user_name = $user_info_sql['user_name'];
                self::$user_email = $user_info_sql['user_email'];
                self::$default_company = $user_info_sql['user_company'];

                $client = self::$con->prepare('SELECT * FROM adgsoft_maps.clients WHERE client_id = :cid');

                $client->bindParam(':cid', $_SESSION['client_id']);

                $client->execute();

                $res = $client->fetch();



                self::$client_settings = (new SettingsRepository())->getClientSettings($_SESSION['client_id']);
                self::$user_database = $res['client_database'];
//

                self::$client_details = $res;
                self::$isAdmin =
                    self::$user_name == 'master' ?
                        true :
                        ($res['admin_user_id'] == self::$user_id);

                $companydetails = self::$con->prepare("SELECT * FROM ".self::$user_database.".companies WHERE company_id = :cid");
                $module = self::$con->prepare('SELECT module_code FROM '.self::$user_database.'.modules_by_company where visible = 1');
                $module->execute();

                $r = $module->fetchAll(\PDO::FETCH_ASSOC);

                $test = [];
                foreach ($r as $a){
//                    array_push($test, $a['module_code']);
                    $test[$a['module_code']] = 1;
                }
                self::$module_by_company =$test;
                $companydetails->bindParam(':cid', self::$default_company);

                $companydetails->execute();

                $companydetails = $companydetails->fetchAll(\PDO::FETCH_ASSOC);

                self::$company_details = $companydetails[0];

                self::$company_cxc = $companydetails[0]['company_cxc'];

                $a = self::$con->prepare("SELECT company_id FROM ".self::$user_database.".companies WHERE company_cxc = :cid");
                $a->bindParam(':cid', self::$company_cxc);
                $a->execute();

                self::$document_companies = $a->fetchAll(\PDO::FETCH_COLUMN);

                self::$catalogue_company = $companydetails[0]['company_catalog_id'];
            }
        }catch (\Exception $e) {
            session_destroy();
           $this->view('generalPurposeMessage',['message' => 'Un error ha ocurrido al obtener los datos de la session.']);
           exit();

        }
    }

}
