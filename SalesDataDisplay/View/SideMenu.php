<?php require_once('../config.php'); ?>
<div id="side-menu">
    <form action="" method="POST">
        <input type="hidden" name="screen_type" value="<?=HOME?>">
        <input type="submit" value="ホーム" class="btn01">
    </form>
    <form action="" method="POST">
        <input type="hidden" name="screen_type" value="<?=BRANCH?>">
        <input type="submit" value="支店情報" class="btn01">
    </form>
    <form action="" method="POST">
        <input type="hidden" name="screen_type" value="<?=ORDER?>">
        <input type="submit" value="注文情報" class="btn01">
    </form>
    <form action="" method="POST">
        <input type="hidden" name="screen_type" value="<?=CUSTOMER?>">
        <input type="submit" value="顧客情報" class="btn01">
    </form>
    <form action="" method="POST">
        <input type="hidden" name="screen_type" value="<?=PRODUCT?>">
        <input type="submit" value="商品情報" class="btn01">
    </form>
    <form action="" method="POST">
        <input type="hidden" name="screen_type" value="<?=REPORT?>">
        <input type="submit" value="帳票" class="btn01">
    </form>
 </div>