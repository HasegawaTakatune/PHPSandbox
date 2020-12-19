<?php
require_once 'config.php';
require_once 'Debug.php';

define('DATE_FROM',1);
define('DATE_TO',2);
define('DATE_FROM_TO',3);

// define('JUDG_EQUALS',0);        // = (イコール)
// define('JUDG_GREATER_THAN',1);  // > (大なり)
// define('JUDG_LESS_THAN',2);     // < (小なり)

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
    public static function getCustomer($id = "", $name = "", $age = "", $gender_code = "", $active = ACTIVE_DEACTIVE){
        
        if(is_null(self::$connection))return null;

        $data = null;
        $query = "";
        $judg = "";

        try{
            $query .= " SELECT * FROM CUSTOMER_INFO ";

            self::AddJudg($id,"id",$judg);

            if($name !== ""){
                if($judg !== "")$judg .= " AND ";
                $judg .= " last_name LIKE '%${name}%' OR first_name LIKE '%${name}%' ";
            }

            self::AddJudg($age,"age",$judg);
            self::AddJudg($gender_code,"gender_code",$judg);
            
            switch($active){
                case ACTIVE: if($judg !== "")$judg .= " AND "; $judg .= " active = true "; break;
                case DEACTIVE: if($judg !== "")$judg .= " AND "; $judg .= " active = false "; break;
                case ACTIVE_DEACTIVE: break;
                default: break;
            }

            if($judg != "")$query .= "WHERE ${judg}";

            $data = self::$connection->query($query);
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $data;
    }

    // 支店情報取得
    public static function getBranch($id = "", $name = "", $match_type = PART, $active = ACTIVE_DEACTIVE){
        
        if(is_null(self::$connection))return null;

        $data = null;
        $query = "";
        $judg = "";

        try{
            $query .= " SELECT * FROM BRANCH_MASTER ";

            self::AddJudg($id,"id",$judg);

            if($name !== ""){
                if($judg !== "")$judg .= " AND ";

                switch($match_type){
                    case PART: $judg .= " name LIKE '%${name}%' OR abbreviation LIKE '%${name}%' "; break;
                    case PERFECT: $judg .= " name = '${name}' OR abbreviation = '${name}' "; break;
                    default: $judg .= " 1 = 1 "; break;
                }
            }     
            
            switch($active){
                case ACTIVE: if($judg !== "")$judg .= " AND "; $judg .= " active = true "; break;
                case DEACTIVE: if($judg !== "")$judg .= " AND "; $judg .= " active = false "; break;
                case ACTIVE_DEACTIVE: break;
                default: break;
            }

            if($judg !== "")$query .= "WHERE ${judg}";

            $data = self::$connection->query($query);
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $data;
    }

    // 支店情報更新
    public static function updBranch($id, $name, $abbreviation){

        if(is_null(self::$connection))return -1;

        $result = false;

        try{
            $stmt = self::$connection->prepare('UPDATE BRANCH_MASTER SET name = :name, abbreviation = :abbreviation WHERE id = :id');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':abbreviation', $abbreviation, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $result;
    }

    // 支店情報削除
    public static function dltBranch($id){
        
        if(is_null(self::$connection))return -1;

        $result = false;

        try{
            $stmt = self::$connection->prepare('UPDATE BRANCH_MASTER SET active = false WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $result;
    }

    // 支店情報新規登録
    public static function instBranch($name, $abbreviation){
        
        if(is_null(self::$connection))return -1;

        $data = null;
        $result = false;

        try{
            $stmt = self::$connection->prepare('INSERT INTO BRANCH_MASTER (name, abbreviation, active) VALUES(:name, :abbreviation, true)');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':abbreviation', $abbreviation, PDO::PARAM_STR);
            $result = $stmt->execute();

            if($result)
                $data = self::$connection->query('SELECT * FROM BRANCH_MASTER ORDER BY id DESC LIMIT 1');
        }catch(Exception $e){
            error_log($e->getMessage());
        }
        return $data;
    }

    // 注文情報取得
    public static function getOrder($id = "", $branch_id = "", $customer_id = "", $transport_id = "", $order_date_from = "", $order_date_to = "", $order_state = array()){
        
        if(is_null(self::$connection))return null;

        $data = null;
        $query = "";
        $judg = "";

        try{
            $query .= " SELECT ORDER_INFO.*, COMMON_MASTER.name FROM ORDER_INFO ";

            self::AddJudg($id,"id",$judg);
            self::AddJudg($branch_id,"branch_id",$judg);
            self::AddJudg($customer_id,"customer_id",$judg);
            self::AddJudg($transport_id,"transport_id",$judg);
            self::AddJudgBetween($order_date_from,$order_date_to,"order_date",$judg);

            $judg_state = "";
            foreach($order_state as $item){
                if($judg_state !== "")$judg_state .= " OR ";
                $judg_state .= " order_state = ${item} ";
            }
            if($judg_state !== ""){
                if($judg !== "") $judg .= " AND ";
                $judg .= " (${judg_state}) ";
            }

            $query .= " LEFT JOIN COMMON_MASTER ON COMMON_MASTER.major_items = 'ORDER_STATE' AND COMMON_MASTER.sub_items = order_state ";

            if($judg !== "")$query .= "WHERE ${judg}";
            $query .= "ORDER BY id ASC";

            Debug::debug_to_console_query($query);
            $data = self::$connection->query($query);
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $data;
    }

    // 注文明細取得
    public static function getOrderDetails($id){
        
        if(is_null(self::$connection))return -1;

        $stmt = null;
        try{
            $stmt = self::$connection->prepare('SELECT 
            info.id, info.order_date, info.deposit_amount, state.name AS state,
            detail.detail_id, detail.discount_rate,
            customer.last_name, customer.first_name, customer.email, customer.tell,
            product.name AS product, product.price, category.name AS category
            
            FROM order_info AS  info
            
            LEFT JOIN order_details AS detail
            ON detail.order_id = info.id
            
            LEFT JOIN common_master AS state
            ON state.major_items = "ORDER_STATE"
            AND state.sub_items = info.order_state
            
            LEFT JOIN customer_info AS customer
            ON customer.id = info.customer_id
            
            LEFT JOIN product_info AS product
            ON product.id = detail.product_id
            
            LEFT JOIN common_master AS category
            ON category.major_items = "CATEGORY"
            AND category.sub_items = product.category_code
            
            WHERE info.id = :id
            ORDER BY info.id, detail.detail_id');

            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $stmt;
    }

    // 注文基本情報取得
    public static function getOrderBaseInfo($id){
        
        if(is_null(self::$connection))return -1;

        $stmt = null;
        try{
            $stmt = self::$connection->prepare('SELECT 
            info.id, info.order_date, info.deposit_amount, state.name AS state,
            customer.last_name, customer.first_name, customer.email, customer.tell,
            SUM(product.price) AS total
                        
            FROM order_info AS  info

            LEFT JOIN customer_info AS customer
            ON customer.id = info.customer_id

            LEFT JOIN order_details AS detail
            ON detail.order_id = info.id
            
            LEFT JOIN common_master AS state
            ON state.major_items = "ORDER_STATE"
            AND state.sub_items = info.order_state
                        
            LEFT JOIN product_info AS product
            ON product.id = detail.product_id
                                    
            WHERE info.id = :id
            ORDER BY info.id');

            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $stmt;
    }

    // 共通データ取得    
    public static function getCommon($category){
        
        if(is_null(self::$connection))return -1;

        $stmt = null;
        try{
            $stmt = self::$connection->prepare('SELECT sub_items, name FROM COMMON_MASTER WHERE major_items = :category');
            $stmt->bindValue(':category', $category, PDO::PARAM_STR);
            $stmt->execute();
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $stmt;
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

    // 判定式クエリ生成
    private static function AddJudg($item, $column, &$query){
        if($item !== ""){
            if($query !== "")$query .= " AND ";
               $query .= "${column} = '${item}'";
        }
    }

    // 範囲指定クエリ生成
    private static function AddJudgBetween($from,$to,$column,&$query){

        $date_type = 0;
        if($from !== "")$date_type += DATE_FROM;
        if($to !== "")$date_type += DATE_TO;
        
        switch($date_type){
            case DATE_FROM:
                if($query !== "")$query .= " AND ";
                $query .= " '${from}' < ${column} ";
                break;

            case DATE_TO:
                if($query !== "")$query .= " AND ";
                $query .= " ${column} < '${to}' ";
                break;

            case DATE_FROM_TO:
                if($query !== "")$query .= " AND ";
                $query .= " ${column} BETWEEN '${from}' AND '${to}' ";
                break;

            default: break;
        }
    }
}