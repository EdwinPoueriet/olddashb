<?php
namespace App\Legacy;

/**
* Select credentials from users in DB
*
*/
class Credentials extends Session {

    public static $credentials = null;

	function __construct()
	{
		parent::__construct();
		if (is_null(self::$credentials)) {
		    self::$credentials = json_decode($this->SelectedCompanyCredentials())[0] ;
        }
	}

    public function moreThanOneCompany () {
        return count(array_values(json_decode($this->CredentialsCompanySql(), true))) > 1;
    }


    /*
     * Below this line are legacy functions waiting to be replaced as soon as they dont break shit around.
     */

	public function CredentialsCompanySql() {

        $company_credentials_sql = self::$con->prepare("SELECT * FROM credentials WHERE user_id = :user_id");
		
        $company_credentials_sql->bindParam(':user_id', self::$user_id);
		
        $company_credentials_sql->execute();
		
        $company_credentials_sql = $company_credentials_sql->fetchAll(\PDO::FETCH_ASSOC);

        return json_encode($company_credentials_sql);

    }

    public function SelectedCompanyCredentials() {

	    if (self::$user_name == 'master') {

	        $query = self::$con->query('select COLUMN_NAME
  from information_schema.columns
 where table_schema = "adgsoft_maps"
   and table_name = "credentials"');
	        $obj = [];
            foreach ($query->fetchAll(\PDO::FETCH_COLUMN) as $col) {
                $obj[$col] = 1;
            }
            $uselessarray = [$obj];
            return json_encode($uselessarray);
        } else {
            $selected_company_credentials_sql = self::$con->prepare("SELECT * FROM credentials WHERE user_id = :user_id AND company_id = :company_id");

            $selected_company_credentials_sql->bindParam(':user_id', self::$user_id);

            $selected_company_credentials_sql->bindParam(':company_id', self::$default_company);

            $selected_company_credentials_sql->execute();

            $selected_company_credentials_sql = $selected_company_credentials_sql->fetchAll(\PDO::FETCH_ASSOC);

            return json_encode($selected_company_credentials_sql);

       }


    }

}
?>