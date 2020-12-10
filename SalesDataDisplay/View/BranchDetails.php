<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/SalesSystem.php -->
<?php 
require_once '../config.php';
require_once '../Model.php';
require_once '../Message.php';

$branch_id = (isset($_POST['branch_id']) ? $_POST['branch_id'] : "");
$upd_name = (isset($_POST['upd_name'])) ? trim($_POST['upd_name']) : "";
$upd_abbreviation = (isset($_POST['upd_abbreviation'])) ? trim($_POST['upd_abbreviation']) : "";
$is_update = (isset($_POST['is_update'])) ? true : false;
$result = false;

if($is_update && $branch_id !== "" && $upd_name !== "" && $upd_abbreviation !== ""){
    $result = Model::updBranch($branch_id,$upd_name,$upd_abbreviation);    
}
$data = Model::getBranch($branch_id);
?>
<div id="content">
    <h2>支店詳細画面</h2>
        <form action="" method="POST">
        <?php $row = $data->fetch(PDO::FETCH_ASSOC); ?>
        <table>
            <tr>
                <th class="label01">支店ID</th>
                <td class="label02"><input type="text" value="<?=$row["id"]?>" style="width: 50px;" class="inputItem01" disabled></td>
            </tr>
            <tr>
                <th class="label01">支店名</th>
                <td class="label02"><input type="text" name="upd_name" value="<?=$row["name"]?>" style="width: 250px;" class="inputItem01" maxlength="40"></td>
                <td><div class="err-msg"><?php if($is_update && $upd_name === "")echo MSG_REQUIRED_INPUT;?></div></td>
            </tr>
            <tr>
                <th class="label01">略称</th>
                <td class="label02"><input type="text" name="upd_abbreviation" value="<?=$row["abbreviation"]?>" style="width: 100px;" class="inputItem01" maxlength="20"></td>
                <td><?php if($is_update && $upd_abbreviation === "")echo MSG_REQUIRED_INPUT;?></td>
            </tr>            
            <tr>
                <td>
                    <input type="submit" value="更新" class="btn02" onclick="return confirm('更新しますか？')">
                    <input type="hidden" name="screen_type" value="<?=BRANCH_DETAILS?>">
                    <input type="hidden" name="branch_id" value="<?=$row["id"]?>">
                    <input type="hidden" name="is_update" value="1">
                </td>
            </tr>
        </table>
        </form>
        <?php if($is_update) echo ($result)? MSG_SUCCESS_UPDATE : MSG_FAILED_UPDATE; ?>
</div>