<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/SalesSystem.php -->
<!-- 
TODO:ログイン情報の保持につてい、ログイン画面も含めて説明してくれています。
TODO:ログインボタンが押された後にセッション変数でログイン情報の保持している。
TODO:その、セッション変数をもとに空白であればログイン画面にリダイレクトする
TODO:などの処理を行う。
 -->
<?php require_once '../config.php'; ?>
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
  <?php include 'SideMenu.php' ?>

  <?php 
  $type = (isset($_POST['screenType'])) ? $_POST['screenType'] : HOME;
  // echo $type . '</br>';
  // global $login_user;
  // echo ($login_user != '')? $Parameta->login_user : 'Not setting';
  //  $Parameta->login_user = $type;
  switch($type){
    case HOME: include 'Home.php'; break;
    case BRANCH: include 'BranchInfo.php'; break;
    case ORDER: include ''; break;
    case CUSTOMER: include ''; break;
    case PRODUCT: include ''; break;
    case REPORT: include ''; break;
    default: include 'NotScreen.php'; break;
  }
  ?>

 <?php 
 /*<div id="content">
 <h2>ホーム画面</h2>
 </br>
 <h3>お知らせ</h3>
 <div class="scrollBox">
   <ul>
     <?php foreach($info_list as $key => $value){ ?>
     <li><?=$value["date"]?>&nbsp;&nbsp;<?=$value["info"];?>&nbsp;&nbsp;<a href="<?=$value["reference"]?>"><?=$value["reference_name"]?></a></li>
     <?php } ?>
   </ul>
 </div>
</div> */
 ?>
</div>
</body>
</html>