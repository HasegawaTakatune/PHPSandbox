<!-- http://localhost/PHPSandbox/SalesDataDisplay/SalesDataDisplay.php -->
<html>
<body>
    <?php 
    
    $dns = 'mysql:dbname=sales_system;host=localhost';
    $user = 'root';
    $password = 'secret';

    try{
        $dbh = new PDO($dns,$user,$password);

        $query = 'SELECT * FROM CUSTOMER_INFO';
        $stmt = $dbh->query($query);

        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
            echo $row['last_name'] . '※※※';
        
    }catch(PDOException $e){
        print('データベースの接続に失敗しました。' . $e->getMessage());
        die();
    }

    $dbh = null;
    ?>
</body>
</html>