<!DOCTYPE html>
<!-- 
TODO:ログイン情報の保持につてい、ログイン画面も含めて説明してくれています。
TODO:ログインボタンが押された後にセッション変数でログイン情報の保持している。
TODO:その、セッション変数をもとに空白であればログイン画面にリダイレクトする
TODO:などの処理を行う。
 -->
<?php 
require_once './Components/config.php'; 
require_once './Components/Model.php';
require_once './Components/Debug.php';

session_start();

if(!isset($_SESSION['id']) && !IS_DEBUG){
  $_POST['logout'] = 1;
  header("Location: Login.php", true, 307);
}

?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href=".\CSS\CommonView.css" rel="stylesheet">
    <title>注文管理システム</title>
</head>

<body>
    <h1>注文管理システム</h1>
    <div id="parent">
        <?php 
  include './View/SideMenu.php';

  $type = (isset($_POST['screen_type'])) ? $_POST['screen_type'] : -1;
  switch($type){
    case HOME:              include './View/Home.php'; break;
    case BRANCH:            include './View/BranchInfo.php'; break;
    case BRANCH_DETAILS:    include './View/BranchDetails.php'; break;
    case BRANCH_INSERT:     include './View/BranchInsert.php'; break;
    case ORDER:             include './View/OrderInfo.php'; break;
    case ORDER_DETAILS:     include './View/OrderDetails.php'; break;
    case CUSTOMER:          include './View/CustomerInfo.php'; break;
    case CUSTOMER_DETAILS:  include './View/CustomerDetails.php'; break;
    case CUSTOMER_INSERT:   include './View/CustomerInsert.php'; break;
    case PRODUCT:           include './View/ProductInfo.php'; break;
    case PRODUCT_DETAILS:   include './View/ProductDetails.php'; break;
    case PRODUCT_INSERT:    include './View/ProductInsert.php'; break;
    case REPORT:            include './View/Report.php'; break;
    default: include './View/NotScreen.php'; break;
  }
  ?>
    </div>

    <?php
if(IS_DEBUG){
  echo "<h2>SCREEN : ${type}</h2>";
}
?>
</body>

</html>