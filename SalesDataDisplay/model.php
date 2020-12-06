<?php
require_once 'config.php';

class Model{

    // 接続インスタンス
    private static $connection = null;

    // 接続開始
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

    // 接続終了
    public static function CloseConnect(){
        self::$connection = null;
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

    // 顧客情報取得
    public static function getCustomer($customer_id = "", $name = "", $age = "", $gender_code = ""){
        $data = null;
        $query = "";
        $judg = "";
        try{
            if(is_null(self::$connection))return;

            $query .= " SELECT * FROM CUSTOMER_INFO ";

            if($customer_id != "")
                $judg .= " customer_id LIKE '%${customer_id}%' ";

            if($name != ""){
                if($judg != "")$judg .= " AND ";
                $judg .= " last_name LIKE '%${name}%' OR first_name LIKE '%${name}%' ";
            }

            if($age != ""){
                if($judg != "")$judg .= " AND ";
                $judg .= " age = ${age} ";
            }

            if($gender_code != ""){
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

    // 支店情報取得
    public static function getBranch($branch_id = "", $branch_name = "", $match_type = PART){
        $data = null;
        $query = "";
        $judg = "";
        try{
            if(is_null(self::$connection))return;

            $query .= " SELECT * FROM BRANCH_MASTER ";

            if($branch_id != "")
                $judg .= " branch_id = '${branch_id}' ";

            if($branch_name != ""){
                if($judg != "")$judg .= " AND ";

                switch($match_type){
                    case PART: $judg .= " name LIKE '%${branch_name}%' OR abbreviation LIKE '%${branch_name}%' "; break;
                    case PERFECT: $judg .= " name = '${branch_name}' OR abbreviation = '${branch_name}' "; break;
                    default: $judg .= " 1 = 1 "; break;
                }
            }           

            if($judg != "")$query .= "WHERE ${judg}";

            $data = self::$connection->query($query);
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $data;
    }

    // 通知情報取得
    public static function getNotice(){
        $data = null;
        try{
            if(is_null(self::$connection))return;

            $data = self::$connection->query('SELECT * FROM NOTICE_MASTER');
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $data;
    }

}

?>