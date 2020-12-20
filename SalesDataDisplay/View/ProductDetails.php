<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/SalesSystem.php -->
<?php 
require_once '../config.php';
require_once '../Model.php';
require_once '../Message.php';

$id = (isset($_POST['id']) ? $_POST['id'] : "");
$name = (isset($_POST['name'])) ? trim($_POST['name']) : "";
$category = (isset($_POST['category'])) ? trim($_POST['category']) : "";
$price = (isset($_POST['price'])) ? trim($_POST['price']) : "";
$is_update = (isset($_POST['is_update'])) ? true : false;
$is_delete = (isset($_POST['is_delete'])) ? true : false;

$result = false;
if($is_update && $id !== "" && $name !== "" && $category !== "" && $price !== ""){
    $result = Model::updProduct($id,$name,$category,$price);
}else if($is_delete && $id !== ""){
    $result = Model::dltProduct($id);
}
$data = Model::getProduct($id);
$common = Model::getCommon('CATEGORY');

?>
<div id="content">
    <h2>商品詳細画面</h2>
        
        <?php 
        $row = $data->fetch(PDO::FETCH_ASSOC);
        $is_active = $row["active"];
        ?>
        <table>
        <form action="" method="POST">
            <tr>
                <th class="label01">商品ID</th>
                <td class="label02"><input type="text" value="<?=$row["id"]?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
            </tr>
            <tr>
                <th class="label01">商品名</th>
                <td class="label02"><input type="text" name="name" value="<?=$row["name"]?>" style="width: 120px;" maxlength="40" <?php ActivStyleInp($is_active) ?> required></td>
            </tr>
            <tr>
                <th class="label01">カテゴリ</th>
                <td class="terms">
                <?php while ($item = $common->fetch(PDO::FETCH_ASSOC)){ ?>
                    <input type="radio" name="category" value="<?=$item["sub_items"]?>" <?php if($item["sub_items"] == $row["category_code"])echo "checked"; ActivStyle($is_active) ?>><?=$item["name"]?>
                <?php } ?>
                </td>
            </tr>
            <tr>
                <th class="label01">価格</th>
                <td class="label02"><input type="number" name="price" value="<?=$row["price"]?>" style="width: 100px;" min="0" <?php ActivStyleInp($is_active) ?> required pattern="^[0-9]+$"></td>
            </tr>
            <tr>
                <td>
                    <input type="submit" value="更新" onclick="return confirm('更新しますか？')" <?php ActivStyleBtn($is_active) ?>>
                    <input type="hidden" name="screen_type" value="<?=PRODUCT_DETAILS?>">
                    <input type="hidden" name="id" value="<?=$row["id"]?>">
                    <input type="hidden" name="is_update" value="1">
                </td>
            </tr>
            </form>
            <form action="" method="POST">
            <tr>
                <td>
                    <input type="submit" value="削除" onclick="return confirm('削除しますか？')" <?php ActivStyleBtn($is_active) ?>>
                    <input type="hidden" name="screen_type" value="<?=PRODUCT_DETAILS?>">
                    <input type="hidden" name="id" value="<?=$row["id"]?>">
                    <input type="hidden" name="is_delete" value="1">
                </td>
            </tr>
        </form>
        </table>       
        <?php if($is_update) echo ($result)? MSG_SUCCESS_UPDATE : MSG_FAILED_UPDATE; ?>
</div>