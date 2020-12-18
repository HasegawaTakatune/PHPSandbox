<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/SalesSystem.php -->
<?php 
require_once '../config.php';
require_once '../Model.php';

$id = (isset($_POST['id'])) ? $_POST['id'] : "";

$data = Model::getOrderDetails($id);
?>
<div id="content">
    <h2>注文明細画面</h2>

    <?php foreach($data as $row){ ?>
    <table>
        <tr>
            <td><div class="label01">注文ID</div></td>
            <td class="label02"><input type="text" value="<?=$row["id"]?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
            <td><div class="label01">配達状況</div></td>
            <td class="label02"><input type="text" value="<?=$row["id"]?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
        </tr>
        <tr>
        <td>
            <div class="label01">割引率</div></td>
            <td class="label02"><input type="text" value="<?=$row["id"]?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
            <td><div class="label01">入金額</div></td>
            <td class="label02"><input type="text" value="<?=$row["id"]?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
        </tr>
        <tr>
            <td><div class="label01">顧客名</div></td>
            <td class="label02"><input type="text" value="<?=$row["id"]?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
            <td><div class="label01">メール</div></td>
            <td class="label02"><input type="text" value="<?=$row["id"]?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
            <td><div class="label01">電話</div></td>
            <td class="label02"><input type="text" value="<?=$row["id"]?>" style="width: 50px;" class="inputItem01-disabled" disabled></td>
        </tr>
    </table>
    <?php break; } ?>

    <div class="scrollBox02">
        <table>
            <tr>
            <th class="label01">明細ID</th>
            <th class="label01" style="width: 300px;">商品名</th>
            <th class="label01" style="width: 100px;">商品カテゴリ</th>
            <th class="label01" style="width: 100px;">値段</th>
            </tr>
            <?php while($row = $data->fetch(PDO::FETCH_ASSOC)){ ?>
              <tr>
              <td class="label01"><?=$row["id"]?></td>
              <td class="label01"><?=$row["branch_id"]?></td>
              <td class="label01"><?=$row["customer_id"]?></td>
              <td class="label01"><?=$row["transport_id"]?></td>
                </td>
              </tr>
            <?php } ?>
        </table>
    </div>
</div>