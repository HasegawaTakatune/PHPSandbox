<?php 

define('ORDER_INFO', 0);
define('BRANCH_ORDER', 1);
define('CATEGORY_ORDER', 2);
define('CUSTOMER_ORDER',3);

define('FULL_ORDER',1);

$report = (isset($_POST['report']))? $_POST['report'] : array();
$year = (isset($_POST['year']) && is_numeric($_POST['year']))? intval($_POST['year']) : -1;
$branch = (isset($_POST['branch_list']))? $_POST['branch_list'] : "";
$category = (isset($_POST['category_list']))? $_POST['category_list'] : "";
$age_from = (isset($_POST['age_from']) && is_numeric($_POST['age_from']))? intval($_POST['age_from']) : -1;
$age_to = (isset($_POST['age_to']) && is_numeric($_POST['age_to']))? intval($_POST['age_to']) : -1;
$gender = (isset($_POST['gender']))? $_POST['gender'] : array();

$branch_list = Model::getBranch();
$category_list = Model::getCommon('CATEGORY');
$gender_list = Model::getCommon('GENDER');

$minYear = 1990;

if(isset($_POST['output'])){
    ExcelReport::init($report, $year, $branch, $category, $age_from, $age_to, $gender);
    ExcelReport::OutputReport();
    // ExcelReport::ReportOrderInfo();
    debug_zval_dump($report);
}

?>
<div id="content">
    <h2>帳票画面</h2>
    <form action="" method="POST">
        <table>
            <tr>
                <td></td>
                <td><label for="year" class="label01">年度</label></td>
                <td><input type="number" name="year" id="year" value="<?=date('Y')?>" min=<?=$minYear?> max=<?=intval(date('Y'))?> maxlength="4" class="inputItem01" required></td>
            </tr>
            <tr>
                <td class="terms"><input type="checkbox" name="report[]" id="order" value="<?=ORDER_INFO?>"><label for="order">注文情報</label></td>
            </tr>
            <tr>
                <td class="terms"><input type="checkbox" name="report[]" id="branch_order_info" value="<?=BRANCH_ORDER?>"><label for="branch_order_info">支店別注文情報</label></td>
                <td><label for="branch_list">
                        <div class="label01">支店</div>
                    </label></td>
                <td>
                    <select name="branch_list" id="branch_list" class="label01">
                        <!-- <option value=""></option> -->
                        <?php while($row = $branch_list->fetch(PDO::FETCH_ASSOC)){ ?>
                        <option value="<?=$row["id"]?>" class="label01"><?=$row["name"]?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="terms"><input type="checkbox" name="report[]" id="category_order" value="<?=CATEGORY_ORDER?>"><label for="category_order">カテゴリ別注文情報</label></td>
                <td><label for="category_list">
                        <div class="label01">商品カテゴリ</div>
                    </label></td>
                <td>
                    <select name="category_list" id="category_list" class="label01">
                        <!-- <option value=""></option> -->
                        <?php while($row = $category_list->fetch(PDO::FETCH_ASSOC)){ ?>
                        <option value="<?=$row["sub_items"]?>" class="label01"><?=$row["name"]?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="terms"><input type="checkbox" name="report[]" id="customer_order" value="<?=CUSTOMER_ORDER?>"><label for="customer_order">顧客別注文情報</label></td>
                <td>
                    <div class="label01">年齢</div>
                </td>
                <td class="terms">
                    <input type="number" name="age_from" value="0" min=0 max=200 class="inputItem01">
                    ～
                    <input type="number" name="age_to" value="0" min=0 max=200 class="inputItem01">
                </td>
                <td>
                    <div class="label01">性別</div>
                </td>
                <td class="terms">
                    <?php while($row = $gender_list->fetch(PDO::FETCH_ASSOC)){ ?>
                    <input type="checkbox" name="gender[]" id="gender<?=$row["sub_items"]?>" value="<?=$row["sub_items"]?>">
                    <label for="gender<?=$row["sub_items"]?>"><?=$row["name"]?></label>
                    <?php } ?>
                </td>
            </tr>
        </table>
        <input type="hidden" name="screen_type" value="<?=REPORT?>">
        <input type="submit" name="output" class="btn02" value="出力">
    </form>
</div>