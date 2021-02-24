<?php 
require_once './Components/Model.php';
require_once './Components/Message.php';

$email = isset($_POST['email']) ? $_POST['email'] : "Non";
$name = isset($_POST['name']) ? $_POST['name'] : "Non";
$password = isset($_POST['password']) ? $_POST['password'] : "Non";

if(isset($_POST['is_insert'])){
    $data = Model::instUser($email, $name, $password);
    if(!empty($data)){
        $row = $data->fetch(PDO::FETCH_ASSOC);

        session_start();
        $_SESSION['id'] = $row['id'];
        header("Location: Controller.php", true, 307);
        exit();
    }
    $signup = true;
}
?>
<script src="//code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
$(function(){
    var password = '#password';
    var passcheck = '#passcheck';

    $(passcheck).change(function(){
        if($(this).prop('checked'))
            $(password).attr('type','text');
        else
            $(password).attr('type','password');
    });
});
</script>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href=".\CSS\CommonView.css" rel="stylesheet">
    <title>ユーザ確認</title>
</head>

<body>
    <div>
        <h2>ユーザ情報の確認</h2>
        </br>        
        <div style="text-align: center;">
        <?php if(!empty($signup)) echo MSG_FAILED_INSERT?>
        </div>
        <h3>下記の内容で登録してよろしいでしょうか。</h3>
        <form action="" method="POST" style="text-align: center;">
            <div class="label01" style="width: 100px; display: inline-block; text-align: right; padding-right:10px;">メールアドレス</div>
            <input type="text" id="email" value="<?=htmlspecialchars($email, ENT_QUOTES)?>" class="inputItem01" style="width: 200px;" readonly disabled>
            <br>
            <div class="label01" style="width: 100px; display: inline-block; text-align: right; padding-right:10px;">名前</div>
            <input type="text" id="name" value="<?=htmlspecialchars($name, ENT_QUOTES)?>" class="inputItem01" style="width: 200px;" readonly disabled>
            <br>
            <div class="label01" style="width: 100px; display: inline-block; text-align: right; padding-right:10px;">パスワード</div>
            <input type="password" id="password" value="<?=htmlspecialchars($password, ENT_QUOTES)?>" class="inputItem01" style="width: 200px;" readonly disabled>
            <br>
            <p>
            <input type="checkbox" id="passcheck">
            <label for="passcheck" style="color: #fff;">パスワードを表示する</label>
            </p>
            <input type="hidden" name="screen_type" value="<?=HOME?>">
            <input type="hidden" name="email" value="<?=$email?>">
            <input type="hidden" name="name" value="<?=$name?>">
            <input type="hidden" name="password" value="<?=$password?>">
            <input type="submit" name="is_insert" value="Sign in" class="btn02" style="width: 200px; height: 40px">
        </form>
        <form action="Login.php" style="text-align: center;">
            <input type="submit" value="戻る" class="btn02" style="width: 200px; height: 40px">
        </form>
    </div>
</body>

</html>