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

    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※　データ取得（SELECT）　※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //

    // 顧客情報取得
    public static function getCustomer($id = "", $name = "", $match_type = PART, $active = ACTIVE_DEACTIVE){
        
        if(is_null(self::$connection))return null;

        $data = null;
        $query = "";
        $judg = "";

        try{
            $query .= " SELECT info.id, info.last_name, info.first_name, info.age, gender.name AS gender, gender.sub_items AS gender_code, info.email, info.tell, info.active 
            FROM CUSTOMER_INFO AS info 
            LEFT JOIN COMMON_MASTER AS gender 
            ON gender.major_items = 'GENDER' 
            AND gender.sub_items = info.gender_code ";

            self::AddJudg($id,"id",$judg);

            if($name !== ""){
                switch($match_type){
                    case PART: if($judg !== "")$judg .= " AND "; $judg .= " last_name LIKE '%${name}%' OR first_name LIKE '%${name}%' "; break;
                    case PERFECT: if($judg !== "")$judg .= " AND "; $judg .= " last_name = '${name}' OR first_name = '${name}' "; break;
                    default: break;
                }
            }   
            
            self::AddJudgActive($active,$judg);
     
            if($judg != "")$query .= " WHERE ${judg} ";
            $query .= " ORDER BY id ASC ";

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
                switch($match_type){
                    case PART: if($judg !== "")$judg .= " AND "; $judg .= " name LIKE '%${name}%' OR abbreviation LIKE '%${name}%' "; break;
                    case PERFECT: if($judg !== "")$judg .= " AND "; $judg .= " name = '${name}' OR abbreviation = '${name}' "; break;
                    default: break;
                }
            }     
            
            self::AddJudgActive($active,$judg);

            if($judg !== "")$query .= "WHERE ${judg}";
            $query .= " ORDER BY id ASC";

            $data = self::$connection->query($query);
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
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
            self::AddJudgArray($order_state, "order_state", $judg);

            $query .= " LEFT JOIN COMMON_MASTER ON COMMON_MASTER.major_items = 'ORDER_STATE' AND COMMON_MASTER.sub_items = order_state ";

            if($judg !== "")$query .= "WHERE ${judg}";
            $query .= " ORDER BY id ASC";

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
            
            FROM ORDER_INFO AS  info
            
            LEFT JOIN ORDER_DETAILS AS detail
            ON detail.order_id = info.id
            
            LEFT JOIN COMMON_MASTER AS state
            ON state.major_items = "ORDER_STATE"
            AND state.sub_items = info.order_state
            
            LEFT JOIN CUSTOMER_INFO AS customer
            ON customer.id = info.customer_id
            
            LEFT JOIN PRODUCT_INFO AS product
            ON product.id = detail.product_id
            
            LEFT JOIN COMMON_MASTER AS category
            ON category.major_items = "CATEGORY"
            AND category.sub_items = product.category_code
            
            WHERE info.id = :id
            ORDER BY info.id ASC, detail.detail_id ASC');

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
                        
            FROM ORDER_INFO AS  info

            LEFT JOIN CUSTOMER_INFO AS customer
            ON customer.id = info.customer_id

            LEFT JOIN ORDER_DETAILS AS detail
            ON detail.order_id = info.id
            
            LEFT JOIN COMMON_MASTER AS state
            ON state.major_items = "ORDER_STATE"
            AND state.sub_items = info.order_state
                        
            LEFT JOIN PRODUCT_INFO AS product
            ON product.id = detail.product_id
                                    
            WHERE info.id = :id
            ORDER BY info.id ASC');

            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $stmt;
    }

    // 商品情報取得
    public static function getProduct($id = "", $name = "", $match_type = PART, $category = array(), $active = ACTIVE_DEACTIVE){
        
        if(is_null(self::$connection))return null;

        $data = null;
        $query = "";
        $judg = "";

        try{
            $query .= " SELECT info.id, info.name, info.price, info.active, common.name AS category, info.category_code
            FROM PRODUCT_INFO AS info
            LEFT JOIN COMMON_MASTER AS common
            ON common.major_items = 'CATEGORY'
            AND common.sub_items = info.category_code ";

            self::AddJudg($id,"id",$judg);

            if($name !== ""){
                switch($match_type){
                    case PART: if($judg !== "")$judg .= " AND "; $judg .= " info.name LIKE '%${name}%' "; break;
                    case PERFECT: if($judg !== "")$judg .= " AND "; $judg .= " info.name = '${name}' "; break;
                    default: break;
                }
            }
            
            self::AddJudgArray($category,"category_code",$judg);

            self::AddJudgActive($active,$judg);
     
            if($judg != "")$query .= " WHERE ${judg} ";
            $query .= " ORDER BY id ASC ";

            $data = self::$connection->query($query);
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $data;
    }

    // 共通データ取得    
    public static function getCommon($category){
        
        if(is_null(self::$connection))return -1;

        $stmt = null;
        try{
            $stmt = self::$connection->prepare('SELECT sub_items, name FROM COMMON_MASTER WHERE major_items = :category ORDER BY sub_items ASC');
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



    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※　データ更新（UPDATE）　※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //

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

    // 顧客情報更新
    public static function updCustomer($id, $last_name, $first_name, $age, $gender, $email, $tell){

        if(is_null(self::$connection))return -1;

        $result = false;
        try{
            $stmt = self::$connection->prepare('UPDATE CUSTOMER_INFO SET last_name = :last_name, first_name = :first_name, age = :age, gender = :gender, email = :email, tell = :tell WHERE id = :id');
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindValue(':age', $age, PDO::PARAM_INT);
            $stmt->bindValue(':gender', $gender, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':tell', $tell, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $result;
    }

    // 商品情報更新
    public static function updProduct($id, $name, $category, $price){

        if(is_null(self::$connection))return -1;

        $result = false;
        try{
            $stmt = self::$connection->prepare('UPDATE PRODUCT_INFO SET name = :name, category_code = :category, price = :price WHERE id = :id');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':category', $category, PDO::PARAM_INT);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $result;
    }



    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※　データ追加（INSERT）　※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //

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

    // 顧客情報新規登録
    public static function instCustomer($last_name, $first_name, $age, $gender, $email, $tell){
        
        if(is_null(self::$connection))return -1;

        $data = null;
        $result = false;
        try{
            $stmt = self::$connection->prepare('INSERT INTO CUSTOMER_INFO (last_name, first_name, age, gender, email, tell, active) VALUES(:last_name, :first_name, :age, :gender, :email, :tell, true)');
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindValue(':age', $age, PDO::PARAM_INT);
            $stmt->bindValue(':gender_code', $gender, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':tell', $tell, PDO::PARAM_STR);
            $result = $stmt->execute();

            if($result)
                $data = self::$connection->query('SELECT * FROM CUSTOMER_INFO ORDER BY id DESC LIMIT 1');
        }catch(Exception $e){
            error_log($e->getMessage());
        }
        return $data;
    }

    // 商品情報新規登録
    public static function instProduct($name, $category, $price){
        
        if(is_null(self::$connection))return -1;

        $data = null;
        $result = false;
        try{
            $stmt = self::$connection->prepare('INSERT INTO PRODUCT_INFO (name, category_code, price, active) VALUES(:name, :category, :price, true)');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':category_code', $category, PDO::PARAM_INT);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $result = $stmt->execute();

            if($result)
                $data = self::$connection->query('SELECT * FROM PRODUCT_INFO ORDER BY id DESC LIMIT 1');
        }catch(Exception $e){
            error_log($e->getMessage());
        }
        return $data;
    }



    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※　データ削除（DELETE）　※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //

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

    // 顧客情報削除
    public static function dltCustomer($id){
        
        if(is_null(self::$connection))return -1;

        $result = false;
        try{
            $stmt = self::$connection->prepare('UPDATE CUSTOMER_INFO SET active = false WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $result;
    }

    // 商品情報削除
    public static function dltProduct($id){
        
        if(is_null(self::$connection))return -1;

        $result = false;
        try{
            $stmt = self::$connection->prepare('UPDATE PRODUCT_INFO SET active = false WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        return $result;
    }

    

    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※　共有メソッド　※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //

    // 判定式クエリ生成
    private static function AddJudg($item, $column, &$query){
        if($item !== ""){
            if($query !== "")$query .= " AND ";
               $query .= "${column} = '${item}'";
        }
    }

    // 活性判定クエリ生成
    private static function AddJudgActive($active, &$query){
        switch($active){
            case ACTIVE: if($query !== "")$query .= " AND "; $query .= " active = true "; break;
            case DEACTIVE: if($query !== "")$query .= " AND "; $query .= " active = false "; break;
            case ACTIVE_DEACTIVE: break;
            default: break;
        }
    }

    // 範囲指定クエリ生成
    private static function AddJudgBetween($from,$to,$column,&$query){

        $date_type = 0;
        if($from !== "")$date_type += DATE_FROM;
        if($to !== "")$date_type += DATE_TO;

        if(strtotime($to) < strtotime($from))return;
        
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

    // 配列指定クエリ生成
    private static function AddJudgArray($array, $column, &$query){
        $judg_state = "";
            foreach($array as $item){
                if($judg_state !== "")$judg_state .= " OR ";
                $judg_state .= " ${column} = ${item} ";
            }
            if($judg_state !== ""){
                if($query !== "") $query .= " AND ";
                $query .= " (${judg_state}) ";
            }
    }

}