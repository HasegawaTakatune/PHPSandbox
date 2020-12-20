<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/SalesSystem.php -->
<?php 
require_once '../config.php';
require_once '../Model.php';

$id = (isset($_POST['id'])) ? $_POST['id'] : "";
$name = (isset($_POST['name'])) ? $_POST['name'] : "";
$match_type = (isset($_POST['match_type'])) ? $_POST['match_type'] : -1;
$category = (isset($_POST['category'])) ? $_POST['category'] : array();

$data = Model::getProduct($id,$name,$match_type,$category);
$common = Model::getCommon('CATEGORY');
?>
<div id="content">
    <h2>商品一覧画面</h2>
    <form action="" method="POST"><input type="submit" value="新規作成" class="btn02"><input type="hidden" name="screen_type" value="<?=PRODUCT_INSERT?>"></form>
    <form action="" method="POST">
        <input type="hidden" name="screen_type" value="<?=PRODUCT?>">
        <table>
        <tr>
            <td><div class="label01">商品ID</div></td>
            <td><input type="text" name="id" value="<?=$id?>" class="inputItem01" maxlength="6"></td>
        </tr>
        <tr>
            <td><div class="label01">商品名</div></td>
            <td><input type="text" name="name" value="<?=$name?>" class="inputItem01" maxlength="40"></td>
        </tr>
        <tr>
            <td><div class="label01">一致タイプ</div></td>
            <td class="terms">
                <input type="radio" name="match_type" value=<?=PART?> checked>部分一致
                <input type="radio" name="match_type" value=<?=PERFECT?>>完全一致
            </td>
        </tr>
        <tr>
            <td><div class="label01">カテゴリ</div></td>
            <td class="terms">
                <?php while ($row = $common->fetch(PDO::FETCH_ASSOC)){ ?>
                    <input type="checkbox" name="category[]" value="<?=$row["sub_items"]?>" <?php if(in_array($row["sub_items"],$category))echo "checked" ?>><?=$row["name"]?>
                <?php } ?>
            </td>
        </tr>
        </table>
        <input type="submit" value="検索" class="btn02">
    </form>
    <div class="scrollBox02">
        <table>
            <tr>
            <th class="label01">商品ID</th>
            <th class="label01" style="width: 300px;">商品名</th>
            <th class="label01" style="width: 100px;">価格</th>
            <th class="label01" style="width: 200px;">カテゴリ</th>
            <th class="label01"></th>
            </tr>
            <?php while($row = $data->fetch(PDO::FETCH_ASSOC)){ ?>
              <tr>
              <td <?php ActivStyleLbl($row["active"]) ?>><?=$row["id"]?></td>
              <td <?php ActivStyleLbl($row["active"]) ?>><?=$row["name"]?></td>
              <td <?php ActivStyleLbl($row["active"]) ?>><?=number_format($row["price"]) . "￥"?></td>
              <td <?php ActivStyleLbl($row["active"]) ?>><?=$row["category"]?></td>
              <td <?php ActivStyleLbl($row["active"]) ?>>
                  <form action="" method="POST">
                      <input type="hidden" name="screen_type" value="<?=PRODUCT_DETAILS?>">
                      <input type="hidden" name="id" value="<?=$row["id"]?>">
                      <input type="submit" value="詳細" <?php ActivStyleLblBtn($row["active"]) ?>>
                    </form>
                </td>
              </tr>
            <?php } ?>
        </table>
    </div>
</div>