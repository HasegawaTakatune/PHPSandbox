<?php require_once('../config.php'); ?>
<div id="side-menu">
    <form action="" method="POST">
        <input type="hidden" name="screenType" value="<?=HOME?>">
        <input type="submit" value="ホーム" class="btn01">
    </form>
    <form action="" method="POST">
        <input type="hidden" name="screenType" value="<?=BRANCH?>">
        <input type="submit" value="支店情報" class="btn01">
    </form>
    <form action="" method="POST">
        <input type="hidden" name="screenType" value="<?=ORDER?>">
        <input type="submit" value="注文情報" class="btn01">
    </form>
    <form action="" method="POST">
        <input type="hidden" name="screenType" value="<?=CUSTOMER?>">
        <input type="submit" value="顧客情報" class="btn01">
    </form>
    <form action="" method="POST">
        <input type="hidden" name="screenType" value="<?=PRODUCT?>">
        <input type="submit" value="商品情報" class="btn01">
    </form>
    <form action="" method="POST">
        <input type="hidden" name="screenType" value="<?=REPORT?>">
        <input type="submit" value="帳票" class="btn01">
    </form>
    <!-- <form>
        <input type="submit" name="screenType" value="home" class="btn01">
        <input type="submit" name="screenType" value="branch" class="btn01">
        <input type="submit" name="screenType" value="order" class="btn01">
        <input type="submit" name="screenType" value="customer" class="btn01">
        <input type="submit" name="screenType" value="product" class="btn01">
        <input type="submit" name="screenType" value="report" class="btn01">
    </form> -->
     <!-- <a href="#" class="btn01">支店情報</a>
     <a href="#" class="btn01">注文情報</a>
     <a href="#" class="btn01">顧客情報</a>
     <a href="#" class="btn01">商品情報</a>
     <a href="#" class="btn01">帳票</a> -->
 </div>