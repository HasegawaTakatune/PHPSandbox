<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/SalesSystem.php -->
<?php 
require_once '../config.php';
require_once '../Model.php';
require_once '../Message.php';

$id = (isset($_POST['id']) ? $_POST['id'] : "");
$name = (isset($_POST['name'])) ? trim($_POST['name']) : "";
$abbreviation = (isset($_POST['abbreviation'])) ? trim($_POST['abbreviation']) : "";
$is_update = (isset($_POST['is_update'])) ? true : false;
$is_delete = (isset($_POST['is_delete'])) ? true : false;

$result = false;

if($is_update && $id !== "" && $name !== "" && $abbreviation !== ""){
    $result = Model::updBranch($id,$name,$abbreviation);    
}else if($is_delete && $id !== ""){
    $result = Model::dltBranch($id);
}
$data = Model::getBranch($id);
?>
<div id="content">
    <h2>支店詳細画面</h2>
        <form action="" method="POST">
        <?php 
        $row = $data->fetch(PDO::FETCH_ASSOC);
        $is_active = $row["active"];
        ?>
        <table>
            <tr>
                <th class="label01">支店ID</th>
                <td class="label02"><input type="text" value="<?=$row["id"]?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
            </tr>
            <tr>
                <th class="label01">支店名</th>
                <td class="label02"><input type="text" name="name" value="<?=$row["name"]?>" style="width: 250px;" maxlength="40" <?php ActivStyleInp($is_active) ?> required></td>
            </tr>
            <tr>
                <th class="label01">略称</th>
                <td class="label02"><input type="text" name="abbreviation" value="<?=$row["abbreviation"]?>" style="width: 100px;" maxlength="20" <?php ActivStyleInp($is_active) ?> required></td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="更新" onclick="return confirm('更新しますか？')" <?php ActivStyleBtn($is_active) ?>>
                    <input type="hidden" name="screen_type" value="<?=BRANCH_DETAILS?>">
                    <input type="hidden" name="id" value="<?=$row["id"]?>">
                    <input type="hidden" name="is_update" value="1">
                </td>
            </tr>
        </table>
        </form>
        <form action="" method="POST">
            <input type="submit" value="削除" onclick="return confirm('削除しますか？')" <?php ActivStyleBtn($is_active) ?>>
            <input type="hidden" name="screen_type" value="<?=BRANCH_DETAILS?>">
            <input type="hidden" name="id" value="<?=$row["id"]?>">
            <input type="hidden" name="is_delete" value="1">
        </form>
        <?php if($is_update) echo ($result)? MSG_SUCCESS_UPDATE : MSG_FAILED_UPDATE; ?>
</div>