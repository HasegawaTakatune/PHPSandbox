<?php
require_once 'config.php';
require_once 'Debug.php';

define('DATE_FROM',1);
define('DATE_TO',2);
define('DATE_FROM_TO',3);

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

    // ユーザ名存在確認
    public static function existUserName($name){
        if(is_null(self::$connection))
            if(!self::StartConnect())return true;

        $result = true;
        try{
            $stmt = self::$connection->prepare('SELECT count(*) AS CNT FROM SYS_USER WHERE name = :name');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $result = (0 < intval($row['CNT'])) ? true : false;
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $result;
    }

    // メールアドレス存在確認
    public static function existUserEmail($email){
        if(is_null(self::$connection))
            if(!self::StartConnect())return true;

        $result = true;
        try{
            $stmt = self::$connection->prepare('SELECT count(*) AS CNT FROM SYS_USER WHERE email = :email');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $result = (0 < intval($row['CNT'])) ? true : false;
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $result;
    }

    // 顧客情報取得
    public static function getCustomer($id = "", $name = "", $match_type = PART, $active = ACTIVE_DEACTIVE){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

        $stmt = null;
        try{
            $query = "";
            $judg = "";

            $query .= " SELECT 
            info.id, info.last_name, info.first_name, info.age, 
            gender.name AS gender, gender.sub_items AS gender_code, 
            info.email, info.tell, info.active 
            FROM CUSTOMER_INFO AS info 
            LEFT JOIN COMMON_MASTER AS gender 
            ON gender.major_items = 'GENDER' 
            AND gender.sub_items = info.gender_code ";
     
            if($id !== "")$judg .= " id = :id AND ";
            if($name !== ""){
                switch($match_type){
                    case PART: $judg .= " last_name LIKE :name1 OR first_name LIKE :name2 AND "; $name = "%${name}%"; break;
                    case PERFECT: $judg .= " last_name = :name1 OR first_name = :name2 AND "; break;
                    default: break;
                }
            }
            self::AddJudgActive($active,$judg);

            $query .= " WHERE ${judg} 1 = 1 ORDER BY id ASC ";

            $stmt = self::$connection->prepare($query);
            if($id !== "")$stmt->bindParam(':id', $id, PDO::PARAM_STR);
            if($name !== ""){
                $stmt->bindParam(':name1', $name, PDO::PARAM_STR);
                $stmt->bindParam(':name2', $name, PDO::PARAM_STR);
            }
            $stmt->execute();

        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $stmt;
    }

    // 支店情報取得
    public static function getBranch($id = "", $name = "", $match_type = PART, $active = ACTIVE_DEACTIVE){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

        $stmt = null;
        try{
            $query = "";
            $judg = "";

            $query .= " SELECT * FROM BRANCH_MASTER ";

            if($id !== "")$judg .= " id = :id AND ";
            if($name !== ""){
                switch($match_type){
                    case PART: if($judg !== "")$judg .= " AND "; $judg .= " name LIKE :name1 OR abbreviation LIKE :name2 AND "; $name = "%${name}%"; break;
                    case PERFECT: if($judg !== "")$judg .= " AND "; $judg .= " name = :name1 OR abbreviation = :name2 AND "; break;
                    default: break;
                }
            }
            self::AddJudgActive($active,$judg);

            $query .= " WHERE ${judg} 1 = 1 ORDER BY id ASC ";

            $stmt = self::$connection->prepare($query);
            if($id !== "")$stmt->bindParam(':id', $id, PDO::PARAM_STR);
            if($name !== ""){
                $stmt->bindParam(':name1', $name, PDO::PARAM_STR);
                $stmt->bindParam(':name2', $name, PDO::PARAM_STR);
            }
            $stmt->execute();

        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $stmt;
    }

    // 注文情報取得
    public static function getOrder($id = "", $branch_id = "", $customer_id = "", $transport_id = "", $order_date_from = "", $order_date_to = "", $order_state = array()){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

        $stmt = null;
        try{
            $type = 0;
            if($order_date_from !== "")$type += DATE_FROM;
            if($order_date_to !== "")$type += DATE_TO;

            $judg = "";
            $query = "";
            $length = count($order_state);

            $query .= " SELECT ORDER_INFO.*, COMMON_MASTER.name 
            FROM ORDER_INFO  
            LEFT JOIN COMMON_MASTER 
            ON COMMON_MASTER.major_items = 'ORDER_STATE' 
            AND COMMON_MASTER.sub_items = order_state ";

            if($id !== "")$judg .= " id = :id AND ";
            if($branch_id !== "")$judg .= " branch_id = :branch_id AND ";
            if($customer_id !== "")$judg .= " customer_id = :customer_id AND ";
            if($transport_id !== "")$judg .= " transport_id = :transport_id AND ";
            self::AddJudgBetween("order_date", $type, $judg);
            self::AddJudgArray($length, "order_state", $judg);

            $query .= " WHERE ${judg} 1 = 1 ORDER BY id ASC ";

            $stmt = self::$connection->prepare($query);
            if($id !== "")$stmt->bindParam(':id', $id, PDO::PARAM_STR);
            if($branch_id !== "")$stmt->bindParam(':branch_id', $branch_id, PDO::PARAM_STR);
            if($customer_id !== "")$stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_STR);
            if($transport_id !== "")$stmt->bindParam(':transport_id', $transport_id, PDO::PARAM_STR);
            switch($type){
                case DATE_FROM: 
                    $stmt->bindParam(':order_date_from', $order_date_from, PDO::PARAM_STR); 
                    break;                
                case DATE_TO: 
                    $stmt->bindParam(':order_date_to', $order_date_to, PDO::PARAM_STR); 
                    break;
                case DATE_FROM_TO: 
                    $stmt->bindParam(':order_date_from', $order_date_from, PDO::PARAM_STR);
                    $stmt->bindParam(':order_date_to', $order_date_to, PDO::PARAM_STR); 
                    break;
                default: break;
            }
            for($i = 0;$i < $length; $i++)$stmt->bindParam(":order_state${i}", $order_state[$i], PDO::PARAM_STR);
            $stmt->execute();

        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $stmt;
    }

    // 注文明細取得
    public static function getOrderDetails($id){

        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

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
        self::CloseConnect();
        return $stmt;
    }

    // 注文基本情報取得
    public static function getOrderBaseInfo($id){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

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
        self::CloseConnect();
        return $stmt;
    }

    // 商品情報取得
    public static function getProduct($id = "", $name = "", $match_type = PART, $category = array(), $active = ACTIVE_DEACTIVE){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

        $stmt = null;
        try{
            $judg = "";
            $query = "";
            $length = count($category);

            $query .= " SELECT 
            info.id, info.name, info.price, info.active, common.name AS category, info.category_code
            FROM PRODUCT_INFO AS info
            LEFT JOIN COMMON_MASTER AS common
            ON common.major_items = 'CATEGORY'
            AND common.sub_items = info.category_code ";

            if($id !== "")$judg .= " id = :id AND ";
            if($name !== ""){
                switch($match_type){
                    case PART: if($judg !== "") $judg .= " info.name LIKE :name AND "; $name = "%${name}%"; break;
                    case PERFECT: if($judg !== "") $judg .= " info.name = :name AND "; break;
                    default: break;
                }
            }            
            self::AddJudgArray($length,"category_code",$judg);
            self::AddJudgActive($active,$judg);
     
            $query .= " WHERE ${judg} 1 = 1 ORDER BY id ASC ";

            $stmt = self::$connection->prepare($query);
            if($id !== "")$stmt->bindParam(':id', $id, PDO::PARAM_STR);
            if($name !== "")$stmt->bindParam(':name', $name, PDO::PARAM_STR);
            for($i = 0; $i < $length; $i++)$stmt->bindParam(":category_code${i}", $category[$i], PDO::PARAM_STR);
            $stmt->execute();

        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $stmt;
    }

    // ユーザ情報取得
    public static function getUser($email){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

        $stmt = null;
        try{
            $stmt = self::$connection->prepare('SELECT id, email, name, password FROM SYS_USER WHERE email = :email');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $stmt;
    }

    // 共通データ取得    
    public static function getCommon($category){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

        $stmt = null;
        try{
            $stmt = self::$connection->prepare('SELECT sub_items, name FROM COMMON_MASTER WHERE major_items = :category ORDER BY sub_items ASC');
            $stmt->bindValue(':category', $category, PDO::PARAM_STR);
            $stmt->execute();
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $stmt;
    }

    // 通知情報取得
    public static function getNotice(){

        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

        $stmt = null;
        try{
            $stmt = self::$connection->prepare(' SELECT * FROM NOTICE_MASTER ');
            $stmt->execute();
        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $stmt;
    }



    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※　データ更新（UPDATE）　※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //

    // 支店情報更新
    public static function updBranch($id, $name, $abbreviation){

        if(is_null(self::$connection))
            if(!self::StartConnect())return false;

        $result = false;
        try{
            self::$connection->beginTransaction();
            $stmt = self::$connection->prepare('UPDATE BRANCH_MASTER SET name = :name, abbreviation = :abbreviation WHERE id = :id');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':abbreviation', $abbreviation, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            self::$connection->commit();
        }catch(Exception $e){
            self::$connection->rollback();
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $result;
    }

    // 顧客情報更新
    public static function updCustomer($id, $last_name, $first_name, $age, $gender, $email, $tell){

        if(is_null(self::$connection))
            if(!self::StartConnect())return false;

        $result = false;
        try{
            self::$connection->beginTransaction();
            $stmt = self::$connection->prepare('UPDATE CUSTOMER_INFO SET last_name = :last_name, first_name = :first_name, age = :age, gender = :gender, email = :email, tell = :tell WHERE id = :id');
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindValue(':age', $age, PDO::PARAM_INT);
            $stmt->bindValue(':gender', $gender, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':tell', $tell, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            self::$connection->commit();
        }catch(Exception $e){
            self::$connection->rollback();
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $result;
    }

    // 商品情報更新
    public static function updProduct($id, $name, $category, $price){

        if(is_null(self::$connection))
            if(!self::StartConnect())return false;

        $result = false;
        try{
            self::$connection->beginTransaction();
            $stmt = self::$connection->prepare('UPDATE PRODUCT_INFO SET name = :name, category_code = :category, price = :price WHERE id = :id');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':category', $category, PDO::PARAM_INT);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            self::$connection->commit();
        }catch(Exception $e){
            self::$connection->rollback();
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $result;
    }



    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※　データ追加（INSERT）　※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //

    // 支店情報新規登録
    public static function instBranch($name, $abbreviation){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

        $data = null;
        try{
            self::$connection->beginTransaction();
            $stmt = self::$connection->prepare('INSERT INTO BRANCH_MASTER (name, abbreviation, active) VALUES(:name, :abbreviation, true)');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':abbreviation', $abbreviation, PDO::PARAM_STR);
            $result = $stmt->execute();
            self::$connection->commit();

            if($result)
                $data = self::$connection->query('SELECT * FROM BRANCH_MASTER ORDER BY id DESC LIMIT 1');
        }catch(Exception $e){
            self::$connection->rollback();
            error_log($e->getMessage());
        }
        self::CloseConnect();
        return $data;
    }

    // 顧客情報新規登録
    public static function instCustomer($last_name, $first_name, $age, $gender, $email, $tell){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

        $data = null;
        try{
            self::$connection->beginTransaction();
            $stmt = self::$connection->prepare('INSERT INTO CUSTOMER_INFO (last_name, first_name, age, gender, email, tell, active) VALUES(:last_name, :first_name, :age, :gender, :email, :tell, true)');
            $stmt->bindParam(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindParam(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindValue(':age', $age, PDO::PARAM_INT);
            $stmt->bindValue(':gender_code', $gender, PDO::PARAM_INT);
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':tell', $tell, PDO::PARAM_STR);
            $result = $stmt->execute();
            self::$connection->commit();

            if($result)
                $data = self::$connection->query('SELECT * FROM CUSTOMER_INFO ORDER BY id DESC LIMIT 1');
        }catch(Exception $e){
            self::$connection->rollback();
            error_log($e->getMessage());
        }
        self::CloseConnect();
        return $data;
    }

    // 商品情報新規登録
    public static function instProduct($name, $category, $price){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

        $data = null;
        try{
            self::$connection->beginTransaction();
            $stmt = self::$connection->prepare('INSERT INTO PRODUCT_INFO (name, category_code, price, active) VALUES(:name, :category, :price, true)');
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindValue(':category_code', $category, PDO::PARAM_INT);
            $stmt->bindParam(':price', $price, PDO::PARAM_STR);
            $result = $stmt->execute();
            self::$connection->commit();

            if($result)
                $data = self::$connection->query('SELECT * FROM PRODUCT_INFO ORDER BY id DESC LIMIT 1');
        }catch(Exception $e){
            self::$connection->rollback();
            error_log($e->getMessage());
        }
        self::CloseConnect();
        return $data;
    }

    // ユーザ情報新規登録
    public static function instUser($email, $name, $password){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

        $data = null;
        try{
            $hash = password_hash($password, PASSWORD_BCRYPT);

            self::$connection->beginTransaction();
            $stmt = self::$connection->prepare('INSERT INTO SYS_USER (email, name, password) VALUES(:email, :name, :password)');
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':password', $hash, PDO::PARAM_STR);
            $result = $stmt->execute();
            self::$connection->commit();

            if($result)
                $data = self::$connection->query('SELECT * FROM SYS_USER ORDER BY id DESC LIMIT 1');
        }catch(Exception $e){
            self::$connection->rollback();
            error_log($e->getMessage());
        }
        self::CloseConnect();
        return $data;
    }



    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※　データ削除（DELETE）　※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //

    // 支店情報削除
    public static function dltBranch($id){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return false;

        $result = false;
        try{
            self::$connection->beginTransaction();
            $stmt = self::$connection->prepare('UPDATE BRANCH_MASTER SET active = false WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            self::$connection->commit();
        }catch(Exception $e){
            self::$connection->rollback();
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $result;
    }

    // 顧客情報削除
    public static function dltCustomer($id){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return false;

        $result = false;
        try{
            self::$connection->beginTransaction();
            $stmt = self::$connection->prepare('UPDATE CUSTOMER_INFO SET active = false WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            self::$connection->commit();
        }catch(Exception $e){
            self::$connection->rollback();
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $result;
    }

    // 商品情報削除
    public static function dltProduct($id){
        
        if(is_null(self::$connection))
            if(!self::StartConnect())return false;

        $result = false;
        try{
            self::$connection->beginTransaction();
            $stmt = self::$connection->prepare('UPDATE PRODUCT_INFO SET active = false WHERE id = :id');
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $result = $stmt->execute();
            self::$connection->commit();
        }catch(Exception $e){
            self::$connection->rollback();
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $result;
    }

    

    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※　共有メソッド　※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //

    // 活性判定クエリ生成
    private static function AddJudgActive($active, &$query){
        switch($active){
            case ACTIVE: $query .= " active = true AND "; break;
            case DEACTIVE: $query .= " active = false AND "; break;
            case ACTIVE_DEACTIVE: break;
            default: break;
        }
    }

    // 範囲指定クエリ生成
    private static function AddJudgBetween($column, $type, &$query){
        switch($type){
            case DATE_FROM: $query .= " :${column}_from < ${column} AND "; break;
            case DATE_TO: $query .= " ${column} < :${column}_to AND "; break;
            case DATE_FROM_TO: $query .= " ${column} BETWEEN :${column}_from AND :${column}_to AND "; break;
            default: break;
        }
    }

    // 配列指定クエリ生成
    private static function AddJudgArray($length, $column, &$query){
        $judg_state = "";
        for($i = 0; $i < $length; $i++){
            if($judg_state !== "")$judg_state .= " OR ";
            $judg_state .= " ${column} = :${column}${i} ";
        }

        if($judg_state !== "") $query .= " (${judg_state}) AND ";
    }



    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※　帳票データ　※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //
    // ※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※※ //

    // 注文情報取得
    public static function getOrderInfo($year){
        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

        $stmt = null;
        try{
            $query = " SELECT 
            INF.id AS order_id, INF.order_date AS order_date, 
            PRD.id AS product_id, PRD.name AS product_name, PRD.price, CMN.name AS category,
            CST.id AS customer_id, CST.last_name, CST.first_name, 
            BRCH.id AS branch_id, BRCH.name AS branch_name 

            FROM ORDER_INFO AS INF 
            LEFT JOIN ORDER_DETAILS AS DTL 
            ON DTL.order_id = INF.id 
            LEFT JOIN PRODUCT_INFO AS PRD 
            ON PRD.id = DTL.product_id 
            LEFT JOIN CUSTOMER_INFO AS CST 
            ON CST.id = INF.customer_id 
            LEFT JOIN BRANCH_MASTER AS BRCH 
            ON BRCH.id = INF.branch_id
            LEFT JOIN COMMON_MASTER AS CMN
            ON CMN.major_items = 'CATEGORY'
            AND CMN.sub_items = PRD.category_code

            WHERE YEAR(INF.order_date) = :year 
            
            ORDER BY INF.id, INF.order_date, PRD.id";

            $stmt = self::$connection->prepare($query);
            $stmt->bindParam(':year', $year, PDO::PARAM_STR);
            $stmt->execute();

        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $stmt;
    }

    // 支店別注文情報取得
    public static function getOrderInfoByBranch($year, $branch_id){
        if(is_null(self::$connection))
            if(!self::StartConnect())return null;

        $stmt = null;
        try{
            $query = " SELECT 
            INF.id AS order_id, INF.order_date AS order_date, 
            PRD.id AS product_id, PRD.name AS product_name, PRD.price, CMN.name AS category,
            CST.id AS customer_id, CST.last_name, CST.first_name, 
            BRCH.id AS branch_id, BRCH.name AS branch_name 

            FROM ORDER_INFO AS INF 
            LEFT JOIN ORDER_DETAILS AS DTL 
            ON DTL.order_id = INF.id 
            LEFT JOIN PRODUCT_INFO AS PRD 
            ON PRD.id = DTL.product_id 
            LEFT JOIN CUSTOMER_INFO AS CST 
            ON CST.id = INF.customer_id 
            LEFT JOIN BRANCH_MASTER AS BRCH 
            ON BRCH.id = INF.branch_id
            LEFT JOIN COMMON_MASTER AS CMN
            ON CMN.major_items = 'CATEGORY'
            AND CMN.sub_items = PRD.category_code

            WHERE YEAR(INF.order_date) = :year 
            AND BRCH.id = :branch_id
            
            ORDER BY BRCH.id, INF.id, INF.order_date, PRD.id";

            $stmt = self::$connection->prepare($query);
            $stmt->bindParam(':year', $year, PDO::PARAM_STR);
            $stmt->bindParam(':branch_id', $branch_id, PDO::PARAM_STR);
            $stmt->execute();

        }catch(Exception $e){
            error_log($e->getMessage(), 1);
        }
        self::CloseConnect();
        return $stmt;
    }

}