<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/SalesSystem.php -->
<?php 
require_once '../config.php';
require_once '../Model.php';
require_once '../Message.php';

$name = (isset($_POST['name'])) ? $_POST['name'] : "";
$abbreviation = (isset($_POST['abbreviation'])) ? $_POST['abbreviation'] : "";
$is_insert = (isset($_POST['is_insert'])) ? true : false;
$data = null;
$row = null;

if($is_insert && $name !== "" && $abbreviation !== ""){
    $data = Model::instBranch($name, $abbreviation);
}
?>
<div id="content">
    <h2>支店登録画面</h2>
        <form action="" method="POST">
        <?php if(!is_null($data)) $row = $data->fetch(PDO::FETCH_ASSOC); ?>       
        <table>
            <tr>
                <th class="label01">支店ID</th>
                <td class="label02"><input type="text" value="<?= (!is_null($row)) ? $row["id"] : ""; ?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
            </tr>
            <tr>
                <th class="label01">支店名</th>
                <td class="label02"><input type="text" name="name" value="<?=$name?>" style="width: 250px;" class="inputItem01" maxlength="40"></td>
                <td><div class="err-msg"><?php if($is_insert && $name === "")echo MSG_REQUIRED_INPUT;?></div></td>
            </tr>
            <tr>
                <th class="label01">略称</th>
                <td class="label02"><input type="text" name="abbreviation" value="<?=$abbreviation?>" style="width: 100px;" class="inputItem01" maxlength="20"></td>
                <td><?php if($is_insert && $abbreviation === "")echo MSG_REQUIRED_INPUT;?></td>
            </tr>            
            <tr>
                <td>
                    <input type="submit" value="登録" class="btn02" onclick="return confirm('登録しますか？')">
                    <input type="hidden" name="screen_type" value="<?=BRANCH_INSERT?>">
                    <input type="hidden" name="is_insert" value="1">
                </td>
            </tr>
        </table>
        </form>
        <?php if($is_insert) echo (!is_null($row))? MSG_SUCCESS_INSERT : MSG_FAILED_INSERT; ?>
</div>