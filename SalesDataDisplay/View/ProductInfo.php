<?php 

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
                <td><label for="id"><div class="label01">商品ID</div></label></td>
                <td><input type="text" name="id" id="id" value="<?=htmlspecialchars($id, ENT_QUOTES)?>" class="inputItem01" maxlength="6"></td>
            </tr>
            <tr>
                <td><label for="name"><div class="label01">商品名</div></label></td>
                <td><input type="text" name="name" id="name" value="<?=htmlspecialchars($name, ENT_QUOTES)?>" class="inputItem01" maxlength="40"></td>
            </tr>
            <tr>
                <td><div class="label01">一致タイプ</div></td>
                <td class="terms">
                    <input type="radio" name="match_type" id="part" value=<?=PART?> checked><label for="part">部分一致</label>
                    <input type="radio" name="match_type" id="perfect" value=<?=PERFECT?>><label for="perfect">完全一致</label>
                </td>
            </tr>
            <tr>
                <td><div class="label01">カテゴリ</div></td>
                <td class="terms">
                    <?php while ($row = $common->fetch(PDO::FETCH_ASSOC)){ ?>
                    <input type="checkbox" name="category[]" id="<?=$row["sub_items"]?>" value="<?=$row["sub_items"]?>" <?php if(in_array($row["sub_items"],$category))echo "checked" ?>><label
                        for="<?=$row["sub_items"]?>"><?=$row["name"]?></label>
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
            <?php while($row = $data->fetch(PDO::FETCH_ASSOC)){ $is_active = $row["active"]; ?>
            <tr>
                <td <?php ActivStyleLbl($is_active) ?>><?=$row["id"]?></td>
                <td <?php ActivStyleLbl($is_active) ?>><?=htmlspecialchars($row["name"], ENT_QUOTES)?></td>
                <td <?php ActivStyleLbl($is_active) ?>><?=number_format($row["price"]) . "￥"?></td>
                <td <?php ActivStyleLbl($is_active) ?>><?=$row["category"]?></td>
                <td <?php ActivStyleLbl($is_active) ?>>
                    <form action="" method="POST">
                        <input type="hidden" name="screen_type" value="<?=PRODUCT_DETAILS?>">
                        <input type="hidden" name="id" value="<?=$row["id"]?>">
                        <input type="submit" value="詳細" <?php ActivStyleLblBtn($is_active) ?>>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>