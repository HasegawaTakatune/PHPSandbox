<?php
$send = $_POST['send'];
?>

<p>送信した文字を表示</p>
<p><?=$send?></p>
<br>
<form action="" method="POST">
 <label>送信する文字を入力</label>
 <input type="text" name="send" >
 <input type="button" value="送信">
</form>