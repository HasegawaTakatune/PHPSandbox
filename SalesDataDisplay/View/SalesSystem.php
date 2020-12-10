<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/SalesSystem.php -->
<!-- 
TODO:ログイン情報の保持につてい、ログイン画面も含めて説明してくれています。
TODO:ログインボタンが押された後にセッション変数でログイン情報の保持している。
TODO:その、セッション変数をもとに空白であればログイン画面にリダイレクトする
TODO:などの処理を行う。
 -->
<?php 
require_once '../config.php'; 
require_once '../Model.php';
Model::StartConnect();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="..\CSS\CommonView.css" rel="stylesheet">
  <title>注文管理システム</title>
</head>
<body>
<h1>注文管理システム</h1>
<div id="parent">
  <?php 
  include 'SideMenu.php';

  $type = (isset($_POST['screen_type'])) ? $_POST['screen_type'] : -1;
  switch($type){
    case HOME: include 'Home.php'; break;
    case BRANCH: include 'BranchInfo.php'; break;
    case BRANCH_DETAILS: include 'BranchDetails.php'; break;
    case BRANCH_INSERT: include 'BranchInsert.php'; break;
    case ORDER: include ''; break;
    case CUSTOMER: include ''; break;
    case PRODUCT: include ''; break;
    case REPORT: include ''; break;
    default: include 'NotScreen.php'; break;
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