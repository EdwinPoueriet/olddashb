<?php

namespace App\Legacy;



class Settings extends Session

{

    function __construct()
    {
        parent::__construct();

    }

    public function GetSettings()

    {

        $settings = self::$con->prepare("SELECT *
		FROM ".self::$user_database.".setting_new 
		WHERE company_id = :company_id");

        $settings->bindParam(':company_id', self::$default_company);

        $settings->execute();

        $settings = $settings->fetchAll(\PDO::FETCH_ASSOC);

        return $settings;

    }


    public function getModuleSettings(){
        $settings = self::$con->prepare("SELECT module_code, visible
		FROM ".self::$user_database.".modules_by_company");
        $settings->execute();

        $settings = $settings->fetchAll(\PDO::FETCH_ASSOC);
        return $settings;

    }


    public function SettingsGeneral()

    {

        if ($_POST['password'] != "") {

            $password = md5($_POST['password']);
            $set_stmt = 'password = "'.$password.'",';

        }

        else {

            $set_stmt = "";

        }

        try {

            $defaultZero = [
                'just_seller_invoices',
                'minimum_amount_order',
                'max_discount_on_receipts'
            ];

            foreach ($defaultZero as $k) {
                if (isset($_POST[$k]) && $_POST[$k] == "") {
                    $_POST[$k] = 0;
                }
            }


            $fields = "";
            $columnas = self::$con->query("SHOW COLUMNS FROM ".self::$user_database.".setting_new")
                ->fetchAll(\PDO::FETCH_ASSOC);
            $columnas_nombres = [];
            foreach ($columnas as $col) {
                array_push($columnas_nombres, $col['Field']);
            }

            $tobind = [];

            $checkboxes = [
                "order_boxes",
                "invoice_boxes",
                "allow_price_changes_in_orders",
                "allow_price_changes_on_invoices",
                "tax_is_included",
                "control_existence_orders",
                "control_existence_invoices",
                "allow_discount_on_orders",
                "allow_discount_on_invoices",
                "apply_automatic_discount_on_invoices",
                "apply_automatic_discount_on_orders",
                "show_only_customers_of_the_day",
                "quick_order",
                "quick_bill",
                "remember_client_search",
                "remember_product_search",
                "remember_selected_customer",
                "print_orders",
                "print_invoices",
                "print_invoices_automatically",
                "reprint_invoices",
                "cancel_bills",
                "require_active_gps",
                "geolocation_require_in_order",
                "geolocation_require_invoices",
                "add_orders_with_click",
                "add_product_with_click",
                "control_location_vendor",
                "show_img_products",
                "sync_transaction_completed",
                "show_product_existence",
                "show_product_without_existence",
                "control_minimum_amount_order",
                "control_reprint",
                "compact_product_list",
                "allow_change_order_type",
                "just_seller_invoices",
                "not_allow_delete_orders",
                "print_transaction_completed",
                "advances_classification_by_customer",
                "just_futuristic_check_in_payment",
                "payment_by_oldest_invoice",
                "send_information_periodically",
                "sync_lock",
                "control_receipts_without_deposits",
                "show_customer_balance_in_print",
                "use_default_return_types",
                "order_below_cost",
                "discount_on_receipts",
                "geolocation_require_in_receipts",
                "split_order_by_warehouse",
                "not_print_header_on_receipts",
                "max_amount_by_credit_limited",
                "enable_price_selection",
                "allow_discount_on_products",
                "discount_just_on_not_expired_invoices",
                "not_allow_discount_on_returned_check",
                "apply_automatic_discount_on_receipts",
                "price_one",
                "price_two",
                "price_three",
                "price_four",
                "minimum_price_with_discount",
                "apply_discount_to_exempt_products"
            ];

            foreach ($_POST as $field => $value) {
                if (in_array($field, $columnas_nombres)) {
                    $tobind[':'.$field] = $value;
                    $fields.= $field . ' = "'.$value.'",';
                }
            }

            foreach ($checkboxes as $field) {
                if (!in_array($field, array_keys($_POST))) {
                    $tobind[':'.$field] = 0;
                    $fields.= $field . ' = 0';
                    $fields.= ',';
                }
            }

            if (substr($fields, -1) == ",") {
                $fields = substr_replace($fields, "", -1);
            }

            $query = "UPDATE
	".self::$user_database.".setting_new SET
	".$set_stmt."
	".$fields."
	WHERE company_id = ".self::$default_company."
	";

//            print_r($query);exit();
//            print_r($query);print_r($tobind);exit();

            $stmt = self::$con->exec($query);

//            $res = $stmt->execute(array_merge($tobind,
//                [':company_id' => self::$default_company]));


//        $query = 'UPDATE '.self::$user_database.'.setting_new SET max_discount_on_receipts = :max_discount_on_receipts';
//        $stmt = self::$con->prepare($query);
//        $stmt->execute([
//            ':max_discount_on_receipts' => $_POST['max_discount_on_receipts']
//        ]);

        } catch (\Exception $e) {
            echo $e;
        }

    }

}

if (isset($_POST['setting_submit']) == 1)

{

    $settings = new Settings;
    $settings->SettingsGeneral();

}

?>