<?php 

$id = (isset($_POST['id'])) ? trim($_POST['id']) : "";
$name = (isset($_POST['name'])) ? trim($_POST['name']) : "";
$category = (isset($_POST['category'])) ? $_POST['category'] : "";
$price = (isset($_POST['price'])) ? $_POST['price'] : "";
$is_insert = (isset($_POST['is_insert'])) ? true : false;
$data = null;
$row = null;

if($is_insert && $name !== "" && $category !== "" && $price !== ""){
    $data = Model::instProduct($name, $category, $price);
}
$common = Model::getCommon("CATEGORY");
?>
<div id="content">
    <h2>商品登録画面</h2>
    <form action="" method="POST">
        <?php if(!is_null($data)) $row = $data->fetch(PDO::FETCH_ASSOC); ?>
        <table>
            <tr>
                <th class="label01">商品ID</th>
                <td class="label02"><input type="text" value="<?= (!is_null($row)) ? $row["id"] : ""; ?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
            </tr>
            <tr>
                <th class="label01">商品名</th>
                <td class="label02"><input type="text" name="name" value="<?=htmlspecialchars($name, ENT_QUOTES)?>" style="width: 250px;" class="inputItem01" maxlength="40" required></td>
            </tr>
            <tr>
                <th class="label01">カテゴリ</th>
                <td>
                    <select name="category" class="label01" style="margin-left: 5px;" required>
                        <?php while ($item = $common->fetch(PDO::FETCH_ASSOC)){ ?>
                        <option value="<?=$item["sub_items"]?>" class="label01" <?php if($item["sub_items"] == $category)echo "selected"; ?>><?=$item["name"]?></option>
                        <?php } ?>
                    </select>
                </td>
            </tr>
            <tr>
                <th class="label01">価格</th>
                <td class="label02"><input type="number" name="price" value="<?=$price?>" style="width: 100px;" class="inputItem01" min="0" required pattern="^[0-9]+$"></td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="登録" class="btn02" onclick="return confirm('登録しますか？')">
                    <input type="hidden" name="screen_type" value="<?=PRODUCT_INSERT?>">
                    <input type="hidden" name="is_insert" value="1">
                </td>
            </tr>
        </table>
    </form>
    <?php if($is_insert) echo (!is_null($row))? MSG_SUCCESS_INSERT : MSG_FAILED_INSERT; ?>
</div>