<?php
namespace App\Common;

use App\Common\Database\ClientDB;
use App\Legacy\Session;

abstract class CompatibilityChecker
{

    private static $columnExistsCache = [];
    private static $tableExistsCache = [];

    public static function PuedeVerReportesDeVisitasEfectivas() {
        return self::tableExists(['visits','visit_types', 'visit_details']);
    }

    public static function PuedeVerModulosPorUsuario()
    {
        return self::tableExists(['modules','modules_by_user', 'modules_by_company']);
    }

    private static function getCon(){
        static $con;
        if (is_null($con))
            $con = ClientDB::getConnection(Session::$client_details['client_database']);
        return $con;
    }

    public static function columnExists($field, $table)
    {
        if (!isset(self::$columnExistsCache[$field.'/'.$table])) {
            $con = self::getCon();
           $res =  count($con->query('SHOW COLUMNS FROM `'.$table.'` LIKE "'.$field.'"')) > 0 ;
            self::$columnExistsCache[$field.'/'.$table] = $res;
        }
        return  self::$columnExistsCache[$field.'/'.$table];
    }

    public static function tableExists($table)
    {
        if (is_array($table) && count($table) > 0) {
            foreach ($table as $t) {
                if (self::checkSingleTableExists($t) == false) {
                    return false;
                }
            }
            return true;
        }
        return self::checkSingleTableExists($table);
    }

    private static function checkSingleTableExists ($table) {
        if (!isset(self::$tableExistsCache[$table])) {
            $con = self::getCon();
            $res = count($con->query('SHOW TABLES LIKE "'.$table.'" ')) > 0 ;
            self::$tableExistsCache[$table] = $res;
        }
        return  self::$tableExistsCache[$table];
    }

}