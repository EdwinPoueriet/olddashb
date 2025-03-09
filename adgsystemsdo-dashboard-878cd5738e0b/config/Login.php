<?php
namespace App\Legacy;

use Manager\Repositories\SettingsRepository;

class Login extends Database

{
  static $error;
  private $con;

  public $settingsRepository;

  function __construct()
  {
      $this->settingsRepository = new SettingsRepository();
      $this->con = $this::getDbInstance();
  }

  function ValidateUser($username, $password)
  {
    try {


      $user_validation = $this->con->prepare("SELECT
        user_id, client_id,two_factor_secret
        FROM adg_users
        WHERE user_name = :user_name 
        AND user_password = :user_password");
      
      $user_validation->bindParam(':user_name', $username);
      $user_validation->bindParam(':user_password', $password);
      $user_validation->execute();
      $user_validation_result = $user_validation->fetch(\PDO::FETCH_ASSOC);

        if ($user_validation_result)
        {
            $settings = $this->settingsRepository->getClientSettings($user_validation_result["client_id"]);

            if ($settings['two_factor_enabled'] == 'true') {
                $_SESSION['attempt_user_id'] = $user_validation_result["user_id"];
                $_SESSION["attempt_client_id"] = $user_validation_result["client_id"];
                $_SESSION['attempt_two_factor_secret'] = $user_validation_result["two_factor_secret"];
                header('Location: securelogin');

            } else {
                $_SESSION["user_id"] = $user_validation_result["user_id"];
                $_SESSION["client_id"] = $user_validation_result["client_id"];
                header('Location: home');

            }


        } else {

            self::$error = "Usuario o clave incorrecta";

        }



    } catch (\Exception $e) {
          
      throw $e;

    }

  }

}

?>
