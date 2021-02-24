<?php 

$id = (isset($_POST['id']) ? $_POST['id'] : "");
$last_name = (isset($_POST['last_name'])) ? trim($_POST['last_name']) : "";
$first_name = (isset($_POST['first_name'])) ? trim($_POST['first_name']) : "";
$age = (isset($_POST['age'])) ? trim($_POST['age']) : "";
$gender = (isset($_POST['gender'])) ? trim($_POST['gender']) : "";
$email = (isset($_POST['email'])) ? trim($_POST['email']) : "";
$tell = (isset($_POST['tell'])) ? trim($_POST['tell']) : "";
$is_update = (isset($_POST['is_update'])) ? true : false;
$is_delete = (isset($_POST['is_delete'])) ? true : false;

$result = false;
if($is_update && $id !== "" && $last_name !== "" && $first_name !== "" && $age !== "" && $gender !== "" && $email !== "" && $tell !== ""){
    $result = Model::updCustomer($id,$last_name,$first_name,$age,$gender,$email,$tell);
}else if($is_delete && $id !== ""){
    $result = Model::dltCustomer($id);
}
$data = Model::getCustomer($id);
$common = Model::getCommon("GENDER");

?>
<div id="content">
    <h2>顧客詳細画面</h2>

    <?php 
        $row = $data->fetch(PDO::FETCH_ASSOC);
        $is_active = $row["active"];
        ?>
    <table>
        <form action="" method="POST">
            <tr>
                <th class="label01">顧客ID</th>
                <td class="label02"><input type="text" value="<?=$row["id"]?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
            </tr>
            <tr>
                <th class="label01">名前</th>
                <td class="label02">
                    <input type="text" name="last_name" value="<?=htmlspecialchars($row["last_name"], ENT_QUOTES)?>" style="width: 120px;" maxlength="40" <?php ActivStyleInp($is_active) ?> required>
                    <input type="text" name="first_name" value="<?=htmlspecialchars($row["first_name"], ENT_QUOTES)?>" style="width: 120px;" maxlength="40" <?php ActivStyleInp($is_active) ?> required>
                </td>
            </tr>
            <tr>
                <th class="label01">年齢</th>
                <td class="label02"><input type="number" name="age" value="<?=$row["age"]?>" style="width: 100px;" min="0" max="200" <?php ActivStyleInp($is_active) ?> required pattern="^[0-9]+$">
                </td>
            </tr>
            <tr>
                <th class="label01">性別</th>
                <td class="terms">
                    <?php while ($item = $common->fetch(PDO::FETCH_ASSOC)){ ?>
                    <input type="radio" name="gender" value="<?=$item["sub_items"]?>" <?php if($item["sub_items"] == $row["gender_code"])echo "checked"; ActivStyle($is_active) ?>><?=$item["name"]?>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th class="label01">メール</th>
                <td class="label02"><input type="email" name="email" value="<?=htmlspecialchars($row["email"], ENT_QUOTES)?>" style="width: 220px;" <?php ActivStyleInp($is_active) ?> required pattern="<?=PATTERN_EMAIL?>"></td>
            </tr>
            <tr>
                <th class="label01">電話番号</th>
                <td class="label02"><input type="text" name="tell" value="<?=htmlspecialchars($row["tell"], ENT_QUOTES)?>" style="width: 120px;" maxlength="15" <?php ActivStyleInp($is_active) ?> required
                        pattern="<?=PATTERN_TELL?>"></td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="更新" onclick="return confirm('更新しますか？')" <?php ActivStyleBtn($is_active) ?>>
                    <input type="hidden" name="screen_type" value="<?=CUSTOMER_DETAILS?>">
                    <input type="hidden" name="id" value="<?=$row["id"]?>">
                    <input type="hidden" name="is_update" value="1">
                </td>
            </tr>
        </form>
        <form action="" method="POST">
            <tr>
                <td>
                    <input type="submit" value="削除" onclick="return confirm('削除しますか？')" <?php ActivStyleBtn($is_active) ?>>
                    <input type="hidden" name="screen_type" value="<?=CUSTOMER_DETAILS?>">
                    <input type="hidden" name="id" value="<?=$row["id"]?>">
                    <input type="hidden" name="is_delete" value="1">
                </td>
            </tr>
        </form>
    </table>
    <?php if($is_update) echo ($result)? MSG_SUCCESS_UPDATE : MSG_FAILED_UPDATE; ?>
</div>