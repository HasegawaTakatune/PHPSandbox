<!-- http://localhost/PHPSandbox/sandbox4.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>function test</title>
</head>
<body>
<?php
$number = 0;

function EchoA(){
    echo '<p>A</p></br>';
}

function AddNum(){
    global $number;
    $number++;
}
?>
<form>
    <input type="button" value="表示A" onclick="EchoA()">
    <input type="button" value="値加算" onclick="AddNum()">
</form>
<?=$number?>
</body>
</html>