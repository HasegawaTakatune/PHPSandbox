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
            // DB接続のインスタンス取得
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

    // 顧客情報取得
    public static function getCustomer($id = "", $name = "", $age = "", $gender_code = ""){
        
        if(is_null(self::$connection))return null;

        $data = null;
        $query = "";
        $judg = "";

        try{
            $query .= " SELECT * FROM CUSTOMER_INFO ";

            if($id != "")
                $judg .= " customer_id = '${id}' ";

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
    public static function getBranch($id = "", $name = "", $match_type = PART){
        
        if(is_null(self::$connection))return null;

        $data = null;
        $query = "";
        $judg = "";

        try{
            $query .= " SELECT * FROM BRANCH_MASTER ";

            if($id != "")
                $judg .= " branch_id = '${id}' ";

            if($name != ""){
                if($judg != "")$judg .= " AND ";

                switch($match_type){
                    case PART: $judg .= " name LIKE '%${name}%' OR abbreviation LIKE '%${name}%' "; break;
                    case PERFECT: $judg .= " name = '${name}' OR abbreviation = '${name}' "; break;
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

    // 支店情報取得
    public static function updBranch($id, $name, $abbreviation){

        if(is_null(self::$connection))return -1;

        $result = -1;
        $query = "";

        try{
            $query .= " UPDATE BRANCH_MASTER SET name = ${name}, abbreviation = ${abbreviation} WHERE branch_id = ${id}";
            $result = self::$connection->exec($query);
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $result;
    }

    // 通知情報取得
    public static function getNotice(){

        if(is_null(self::$connection))return null;

        $data = null;

        try{
            $data = self::$connection->query(' SELECT * FROM NOTICE_MASTER');
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $data;
    }

}

?>