<!-- http://localhost/PHPSandbox/SalesDataDisplay/SalesDataDisplay.php -->
<html>
    <head>
        <meta charset="utf-8">
        <title>注文管理システム</title>
    </head>
    <body>
        <?php
        
        require 'model.php';
    
        $dns = 'mysql:dbname=sales_system;host=localhost';
        $user = 'root';
        $password = 'secret';

        Model::StartConnect();
        $data = Model::getCustomer();

        while($row = $data->fetch(PDO::FETCH_ASSOC))
        echo $row['last_name'] . '※※※';

        // try{
        //     $dbh = new PDO($dns,$user,$password);

        //     $query = 'SELECT * FROM CUSTOMER_INFO';
        //     $stmt = $dbh->query($query);



        //     while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        //         echo $row['last_name'] . '※※※';
        
        // }catch(PDOException $e){
        //     print('データベースの接続に失敗しました。' . $e->getMessage());
        //     die();
        // }

        // $dbh = null;
        ?>
    </body>
</html>