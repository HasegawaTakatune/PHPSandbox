<?php 
require_once './Components/Model.php';
require_once './Components/Message.php';

// ログファイル生成
$now = date('Ymd');
if(!file_exists("./log/{$now}")){
    file_put_contents("./log/{$now}.log", "※※※ {$now} logs ※※※※※※※※※※※");
}

session_start();

if(isset($_POST['logout'])){
    unset($_POST);
    unset($_SESSION['id']);
    session_destroy();
}

if(isset($_POST['signin'])){
    $email = (isset($_POST['email'])) ? $_POST['email'] : "";
    $password = (isset($_POST['password'])) ? $_POST['password'] : "";
    $message = '';

    $data = Model::getUser($email);

    while($row = $data->fetch(PDO::FETCH_ASSOC)){
        if (isset($row['password']) && password_verify($password, $row['password'])){
            $_SESSION['id'] = $row['id'];
            header("Location: Controller.php", true, 307);
            exit();
        }
        break;
    }
    $message = MSG_MISTAKE_EMAIL_PASSWORD;
}

if(isset($_POST['signup'])){
    $email = (isset($_POST['email'])) ? $_POST['email'] : "";
    $name = (isset($_POST['name'])) ? $_POST['name'] : "";
    $message = '';

    if(Model::existUserEmail($email)) $message = MSG_DUPLICATE_EMAIL;
    if(Model::existUserName($name)) $message = MSG_DUPLICATE_USER_NAME;

    if($message == ''){
        header("Location: Signup.php", true, 307);
        exit();
    }
}

?>
<script src="//code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
$(function(){
    var password = '#password';
    var password_conf = '#password_conf';
    var passcheck = '#passcheck';

    $(passcheck).change(function(){
        if($(this).prop('checked')){
            $(password).attr('type','text');
            $(password_conf).attr('type','text');
        }else{
            $(password).attr('type','password');
            $(password_conf).attr('type','password');
        }
    });
});
</script>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link type="text/css" href="./CSS/CommonView.css" rel="stylesheet">
    <title>ログイン</title>
</head>

<body>
    <h2>ログイン</h2>
    </br>
    <div style="text-align: center;">
    <?php if(!empty($message)) echo $message?>
    </div>
    <div id="parent">
        <div id="login">
            <form action="" method="POST" style="text-align: center;">
                <h3>ログイン情報を入力して下さい。</h3>
                <div class="label01" style="width: 100px; display: inline-block; text-align: right; padding-right:10px;">メールアドレス</div>
                <input type="email" name="email" class="inputItem01" style="width: 200px;" placeholder="sample@example.com" value="" required>
                <br>
                <div class="label01" style="width: 100px; display: inline-block; text-align: right; padding-right:10px;">パスワード</div>
                <input type="password" name="password" class="inputItem01" style="width: 200px;" placeholder="Password" value="" required>
                <br>
                <input type="hidden" name="screen_type" value="<?=HOME?>">
                <input type="submit" name="signin" value="Sign in" class="btn02" style="width: 200px; height: 40px">
            </form>
        </div>
        <div id="login">
            <form action="" method="POST" style="text-align: center;">
                <h3>新規登録する場合はこちらに入力して下さい。</h3>
                <div class="label01" style="width: 140px; display: inline-block; text-align: right; padding-right:10px;">メールアドレス</div>
                <input type="email" name="email" class="inputItem01" style="width: 200px;" placeholder="sample@example.com" value="" required>
                <br>
                <div class="label01" style="width: 140px; display: inline-block; text-align: right; padding-right:10px;">ユーザ名</div>
                <input type="text" name="name" class="inputItem01" style="width: 200px;" value="" maxlength="40" required>
                <br>
                <div class="label01" style="width: 140px; display: inline-block; text-align: right; padding-right:10px;">パスワード</div>
                <input type="password" name="password" id="password" class="inputItem01" style="width: 200px;" placeholder="Password" value="" autocomplete="new-password" required>
                <br>
                <div class="label01" style="width: 140px; display: inline-block; text-align: right; padding-right:10px;">パスワード（確認）</div>
                <input type="password" name="password_conf" id="password_conf" class="inputItem01" style="width: 200px;" placeholder="Password" value="" required>
                <br>
                <p>
                <input type="checkbox" id="passcheck">
                <label for="passcheck" style="color: #fff;">パスワードを表示する</label>
                </p>
                <input type="submit" name="signup" value="Sign up" class="btn02" style="width: 200px; height: 40px">
            </form>
        </div>
    </div>
</body>

</html>