<?php 

$last_name = (isset($_POST['last_name'])) ? trim($_POST['last_name']) : "";
$first_name = (isset($_POST['first_name'])) ? trim($_POST['first_name']) : "";
$age = (isset($_POST['age'])) ? $_POST['age'] : "";
$gender = (isset($_POST['gender'])) ? $_POST['gender'] : "";
$email = (isset($_POST['email'])) ? trim($_POST['email']) : "";
$tell = (isset($_POST['tell'])) ? trim($_POST['tell']) : "";
$is_insert = (isset($_POST['is_insert'])) ? true : false;
$data = null;
$row = null;

if($is_insert && $last_name !== "" && $first_name !== "" && $age !== "" && $gender !== "" && $email !== "" && $tell !== ""){
    $data = Model::instCustomer($last_name, $first_name, $age, $gender, $email, $tell);
}
$common = Model::getCommon("GENDER");
?>
<div id="content">
    <h2>顧客登録画面</h2>
    <form action="" method="POST">
        <?php if(!is_null($data)) $row = $data->fetch(PDO::FETCH_ASSOC); ?>
        <table>
            <tr>
                <th class="label01">顧客ID</th>
                <td class="label02"><input type="text" value="<?= (!is_null($row)) ? $row["id"] : ""; ?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
            </tr>
            <tr>
                <th class="label01">名前</th>
                <td class="label02">
                    <input type="text" name="last_name" value="<?=htmlspecialchars($last_name, ENT_QUOTES)?>" style="width: 250px;" class="inputItem01" maxlength="40" required>
                    <input type="text" name="first_name" value="<?=htmlspecialchars($first_name, ENT_QUOTES)?>" style="width: 250px;" class="inputItem01" maxlength="40" required>
                </td>
            </tr>
            <tr>
                <th class="label01">年齢</th>
                <td class="label02"><input type="number" name="age" value="<?=$age?>" style="width: 100px;" min="0" max="200" class="inputItem01" required pattern="^[0-9]+$"></td>
            </tr>
            <tr>
                <th class="label01">性別</th>
                <td class="terms">
                    <?php while ($item = $common->fetch(PDO::FETCH_ASSOC)){ ?>
                    <input type="radio" name="gender" value="<?=$item["sub_items"]?>" <?php if($item["sub_items"] == $gender)echo "checked"; ?> required><?=$item["name"]?>
                    <?php } ?>
                </td>
            </tr>
            <tr>
                <th class="label01">メール</th>
                <td class="label02"><input type="email" name="email" value="<?=htmlspecialchars($email, ENT_QUOTES)?>" style="width: 220px;" class="inputItem01" required pattern="<?=PATTERN_EMAIL?>"></td>
            </tr>
            <tr>
                <th class="label01">電話番号</th>
                <td class="label02"><input type="text" name="tell" value="<?=htmlspecialchars($tell, ENT_QUOTES)?>" style="width: 120px;" maxlength="15" class="inputItem01" required pattern="<?=PATTERN_TELL?>"></td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="登録" class="btn02" onclick="return confirm('登録しますか？')">
                    <input type="hidden" name="screen_type" value="<?=CUSTOMER_INSERT?>">
                    <input type="hidden" name="is_insert" value="1">
                </td>
            </tr>
        </table>
    </form>
    <?php if($is_insert) echo (!is_null($row))? MSG_SUCCESS_INSERT : MSG_FAILED_INSERT; ?>
</div>