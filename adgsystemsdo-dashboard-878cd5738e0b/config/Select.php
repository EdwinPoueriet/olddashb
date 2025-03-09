<?php

namespace App\Legacy;

use Symfony\Component\HttpFoundation\RedirectResponse;

class Select extends Credentials

{

	function SelectShow()

	{
	    if (self::$user_name == 'master') {
            echo '<select id="company" name="company" class="form-control">';
            $companyselectsql = self::$con->query("SELECT company_name,company_id FROM ".self::$user_database.".companies");
            $res = $companyselectsql->fetchAll(\PDO::FETCH_ASSOC);
            foreach ($res as $comp) {
                echo '<option value="'.$comp['company_id'].'">'.$comp['company_id'].' - '.$comp['company_name'].'</option>';
            }
            echo '</select>';
        } else {

            $selectarray = json_decode($this->CredentialsCompanySql());
            echo '<select id="company" name="company" class="form-control">';

            foreach ($selectarray as $row) {


                $companyselectsql = self::$con->prepare("SELECT company_name FROM ".self::$user_database.".companies WHERE company_id = :company_id");

                $companyselectsql->bindParam(':company_id', $row->company_id);

                $companyselectsql->execute();

                $companyselectsql = $companyselectsql->fetchColumn();

                echo '<option value="'.$row->company_id.'" '.General::GetComparison(self::$default_company, $row->company_id).'>' .$row->company_id . ' - ' . $companyselectsql . '</option>';

            }

            echo '</select>';
        }


	}

	function SubmitCompany()

	{

	    $_SESSION["user_company"] = $_POST['company'];

		$submitcompany = self::$con->prepare("UPDATE
		adg_users SET
		user_company = :user_company
		WHERE user_id = :user_id");
		$submitcompany->bindParam(':user_company', $_POST['company']);
		$submitcompany->bindParam(':user_id', self::$user_id);
		$submitcompany->execute();

		return (new \App\Common\BlankRedirectResponse('/dashboard'))->send();

	}

}

if (isset($_POST['company'])){

	$submitcompany = new Select;
	$submitcompany->SubmitCompany();

}


?>