<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/SalesSystem.php -->
<?php 
require_once '../config.php';
require_once '../Model.php';

$id = (isset($_POST['id'])) ? $_POST['id'] : "";

$data = Model::getOrderDetails($id);
$baseData = Model::getOrderBaseInfo($id)
?>
<div id="content">
    <h2>注文明細画面</h2>

    <?php foreach($baseData as $row){ ?>
    <table>
        <tr>
            <td><div class="label01" style="width: 70px;">注文ID</div></td>
            <td class="label02"><input type="text" value="<?=$row["id"]?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
            <td><div class="label01" style="width: 70px;">配達状況</div></td>
            <td class="label02"><input type="text" value="<?=$row["state"]?>" style="width: 70px;" class="inputItem01-disabled" disabled></td>
        </tr>
        <tr>
            <td><div class="label01" style="width: 70px;">合計金額</div></td>
            <td class="label02"><input type="text" value="<?=number_format($row["total"]) . "￥"?>" style="width: 120px; text-align: right;" class="inputItem01-disabled" disabled></td>
            <td><div class="label01">入金額</div></td>
            <td class="label02"><input type="text" value="<?=number_format($row["deposit_amount"]) . "￥"?>" style="width: 120px; text-align: right;" class="inputItem01-disabled" disabled></td>
        </tr>
        <tr>
            <td><div class="label01">顧客名</div></td>
            <td class="label02"><input type="text" value="<?=$row["last_name"] . "&nbsp" . $row["first_name"]?>" style="width: 120px;" class="inputItem01-disabled" disabled></td>
            <td><div class="label01">メール</div></td>
            <td class="label02"><input type="text" value="<?=$row["email"]?>" style="width: 250px;" class="inputItem01-disabled" disabled></td>
            <td><div class="label01" style="width: 70px;">電話</div></td>
            <td class="label02"><input type="text" value="<?=$row["tell"]?>" style="width: 120px;" class="inputItem01-disabled" disabled></td>
        </tr>
    </table>
    <?php break; } ?>

    <div class="scrollBox02">
        <table>
            <tr>
            <th class="label01">明細ID</th>
            <th class="label01" style="width: 400px;">商品名</th>
            <th class="label01" style="width: 200px;">商品カテゴリ</th>
            <th class="label01" style="width: 100px;">価格</th>
            <th class="label01" style="width: 100px;">割引率</th>
            <th class="label01" style="width: 100px;">割引価格</th>
            </tr>
            <?php while($row = $data->fetch(PDO::FETCH_ASSOC)){ $price = $row["price"]; $rate = $row["discount_rate"]; ?>
              <tr>
              <td class="label01"><?=$row["detail_id"]?></td>
              <td class="label01"><?=$row["product"]?></td>
              <td class="label01"><?=$row["category"]?></td>
              <td class="label01" style="text-align: right;"><?=number_format($price) . "￥"?></td>
              <td class="label01"><?=$row["discount_rate"]."%"?></td>
              <td class="label01" style="text-align: right;"><?=(0 < $rate)? number_format($price - ($price / ($rate / 100))) . "￥" : "0￥" ?></td>
              </tr>
            <?php } ?>
        </table>
    </div>
</div>