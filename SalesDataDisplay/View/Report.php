<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/SalesSystem.php -->
<?php 
require_once '../config.php';
require_once '../Model.php';

define('BASE_ORDER',0);
define('FULL_ORDER',1);
define('CATEGORY_ORDER',2);
define('CUSTOMER_ORDER',3);

$report = (isset($_POST['report']))? $_POST['report'] : "";
$year = (isset($_POST['year']))? $_POST['year'] : "";
$branch = (isset($_POST['branch']))? $_POST['branch'] : "";
$category = (isset($_POST['category']))? $_POST['category'] : "";
$age_from = (isset($_POST['age_from']))? $_POST['age_from'] : "";
$age_to = (isset($_POST['age_to']))? $_POST['age_to'] : "";
$gender = (isset($_POST['gender']))? $_POST['gender'] : array();

$branch_list = Model::getBranch();
$category_list = Model::getCommon('CATEGORY');
$gender_list = Model::getCommon('GENDER');

$minYear = 1990;
?>
<div id="content">
    <h2>帳票画面</h2>
    <table>
        <tr>
            <form action="" method="POST">
            <td>
            <input type="hidden" name="screen_type" value="<?=REPORT?>">
            <input type="hidden" name="report" value="<?=BASE_ORDER?>">
            <input type="submit" value="注文情報" class="labelBtn01">
            </td>
            <td><div class="label01">年度</div></td>
            <td><input type="number" name="year" value="<?=date('yy')?>" min=<?=$minYear?> max=<?=date('yy')?> class="inputItem01" required></td>
            </form>
        </tr>
        <tr>
            <form action="" method="POST">
            <td>
            <input type="hidden" name="screen_type" value="<?=REPORT?>">
            <input type="hidden" name="report" value="<?=FULL_ORDER?>">
            <input type="submit" value="支店別注文情報" class="labelBtn01">
            </td>
            <td><div class="label01">年度</div></td>
            <td><input type="number" name="year" value="<?=date('yy')?>" min=<?=$minYear?> max=<?=date('yy')?> class="inputItem01" required></td>
            <td><div class="label01">支店</div></td>
            <td>
                <select name="branch" class="label01">
                    <option value=""></option>
                    <?php while($row = $branch_list->fetch(PDO::FETCH_ASSOC)){ ?>
                    <option value="<?=$row["id"]?>" class="label01"><?=$row["name"]?></option>
                    <?php } ?>
                </select>
            </td>
            </form>
        </tr>
        <tr>
            <form action="" method="POST">
            <td>
            <input type="hidden" name="screen_type" value="<?=REPORT?>">
            <input type="hidden" name="report" value="<?=CATEGORY_ORDER?>">
            <input type="submit" value="カテゴリ別注文情報" class="labelBtn01">
            </td>
            <td><div class="label01">年度</div></td>
            <td><input type="number" name="year" value="<?=date('yy')?>" min=<?=$minYear?> max=<?=date('yy')?> class="inputItem01" required></td>
            <td><div class="label01">商品カテゴリ</div></td>
            <td>
                <select name="category" class="label01">
                    <option value=""></option>
                    <?php while($row = $category_list->fetch(PDO::FETCH_ASSOC)){ ?>
                    <option value="<?=$row["sub_items"]?>" class="label01"><?=$row["name"]?></option>
                    <?php } ?>
                </select>
            </td>
            </form>
        </tr>
        <tr>
            <form action="" method="POST">
            <td>
            <input type="hidden" name="screen_type" value="<?=REPORT?>">
            <input type="hidden" name="report" value="<?=CUSTOMER_ORDER?>">
            <input type="submit" value="顧客別注文情報" class="labelBtn01">
            </td>
            <td><div class="label01">年度</div></td>
            <td><input type="number" name="year" value="<?=date('yy')?>" min=<?=$minYear?> max=<?=date('yy')?> class="inputItem01" required></td>
            <td><div class="label01">年齢</div></td>
            <td class="terms">
                <input type="number" name="age_from" value="0" min=0 max=200 class="inputItem01">
                ～
                <input type="number" name="age_to" value="0" min=0 max=200 class="inputItem01">
            </td>
            <td><div class="label01">性別</div></td>
            <td class="terms">
                <?php while($row = $gender_list->fetch(PDO::FETCH_ASSOC)){ ?>
                <input type="checkbox" name="gender[]" value="<?=$row["sub_items"]?>"><?=$row["name"]?>
                <?php } ?>
            </td>
            </form>
        </tr>
    </table>
</div>