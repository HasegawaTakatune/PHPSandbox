<?php

define('DNS','mysql:dbname=sales_system;host=localhost');
define('USER','root');
define('PASSWORD','secret');

class Model{

    public static $connection = null;

    public static function StartConnect(){

        if(!is_null(self::$connection))return true;

        $result = true;
        try{
            self::$connection = new PDO(DNS,USER,PASSWORD,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_BOTH,
                PDO::ATTR_EMULATE_PREPARES => true,
                PDO::ATTR_PERSISTENT => true
            ]);
        }catch(PDOException $e){            
            error_log($e->getMessage(), 1);
            $result = false;
        }

        return $result;
    }

    // public static function getCustomer(){
    //     $data = null;
    //     try{
    //         if(is_null(self::$connection))return;

    //         $data = self::$connection->query('SELECT * FROM CUSTOMER_INFO');
    //     }catch(Exception $e){
    //         error_log($e->getMessage(), 1);
    //     }
    //     return $data;
    // }

    public static function getCustomer($customer_id = "", $name = "", $age = "", $gender_code = ""){
        $data = null;
        $query = "";
        $judg = "";
        try{
            if(is_null(self::$connection))return;

            $query .= " SELECT * FROM CUSTOMER_INFO ";

            if(!is_null($customer_id) && $customer_id != "")
                $judg .= " customer_id LIKE '%${customer_id}%' ";

            if(!is_null($name) && $name != ""){
                if($judg != "")$judg .= " AND ";
                $judg .= " last_name LIKE '%${name}%' AND first_name LIKE '%${name}%' ";
            }

            if(!is_null($age) && $age != ""){
                if($judg != "")$judg .= " AND ";
                $judg .= " age = ${age} ";
            }

            if(!is_null($gender_code) && $gender_code != ""){
                if($judg != "")$judg .= " AND ";
                $judg .= " gender_code = ${gender_code} ";
            }

            if($judg != "")$query .= "WHERE ${judg}";

            $data = self::$connection->query($query);
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $data;
    }

}

?>