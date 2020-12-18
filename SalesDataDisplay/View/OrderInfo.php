<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/SalesSystem.php -->
<?php 
require_once '../config.php';
require_once '../Model.php';
require_once '../Debug.php';

$id = (isset($_POST['id'])) ? $_POST['id'] : "";
$branch_id = (isset($_POST['branch_id'])) ? $_POST['branch_id'] : "";
$customer_id = (isset($_POST['customer_id'])) ? $_POST['customer_id'] : "";
$transport_id = (isset($_POST['transport_id'])) ? $_POST['transport_id'] : "";
$order_date_from = (isset($_POST['order_date_from'])) ? $_POST['order_date_from'] : "";
$order_date_to = (isset($_POST['order_date_to'])) ? $_POST['order_date_to'] : "";
$order_state = (isset($_POST['order_state'])) ? $_POST['order_state'] : array();

$data = Model::getOrder($id,$branch_id,$customer_id,$transport_id,$order_date_from,$order_date_to,$order_state);
$common = Model::getCommon("ORDER_STATE");
?>
<div id="content">
    <h2>注文一覧画面</h2>
    <form action="" method="POST">
        <input type="hidden" name="screen_type" value="<?=ORDER?>">
        <table>
        <tr>
            <td><div class="label01">注文ID</div></td>
            <td><input type="text" name="id" value="<?=$id?>" class="inputItem01" maxlength="6"></td>
            <td><div class="label01">支店ID</div></td>
            <td><input type="text" name="branch_id" value="<?=$branch_id?>" class="inputItem01" maxlength="6"></td>
        </tr>
        <tr>
            <td><div class="label01">顧客ID</div></td>
            <td><input type="text" name="customer_id" value="<?=$customer_id?>" class="inputItem01" maxlength="6"></td>
            <td><div class="label01">輸送ID</div></td>
            <td><input type="text" name="transport_id" value="<?=$transport_id?>" class="inputItem01" maxlength="6"></td>
        </tr>
        </table>
        <table>
        <tr>
            <td><div class="label01">注文日付</div></td>
            <td class="terms">
                <input type="date" name="order_date_from" value="<?=$order_date_from?>" class="inputItem01">
                ～
                <input type="date" name="order_date_to" value="<?=$order_date_to?>" class="inputItem01">
            </td>
        </tr>
        <tr>
            <td><div class="label01">注文ステータス</div></td>
            <td class="terms">
                <?php while ($row = $common->fetch(PDO::FETCH_ASSOC)){ ?>
                    <input type="checkbox" name="order_state[]" value="<?=$row["sub_items"]?>" <?php if(in_array($row["sub_items"],$order_state))echo "checked" ?>><?=$row["name"]?>
                <?php } ?>
            </td>
        </tr>
        </table>
        <input type="submit" value="検索" class="btn02">
    </form> 
    <div class="scrollBox02">
        <table>
            <tr>
            <th class="label01">注文ID</th>
            <th class="label01">支店ID</th>
            <th class="label01">顧客ID</th>
            <th class="label01">輸送ID</th>
            <th class="label01">注文日付</th>
            <th class="label01">注文ステータス</th>
            <th class="label01">入金額</th>
            <th class="label01"></th>
            </tr>
            <?php while($row = $data->fetch(PDO::FETCH_ASSOC)){ ?>
              <tr>
              <td class="label01"><?=$row["id"]?></td>
              <td class="label01" style="width: 300px;"><?=$row["branch_id"]?></td>
              <td class="label01" style="width: 100px;"><?=$row["customer_id"]?></td>
              <td class="label01" style="width: 100px;"><?=$row["transport_id"]?></td>
              <td class="label01" style="width: 100px;"><?=$row["order_date"]?></td>
              <td class="label01" style="width: 100px;"><?=$row["name"]?></td>
              <td class="label01" style="width: 100px;"><?=$row["deposit_amount"]?></td>
              <td class="label01">
                  <form action="" method="POST">
                      <input type="hidden" name="screen_type" value="<?=ORDER_DETAILS?>">
                      <input type="hidden" name="id" value="<?=$row["id"]?>">
                      <input type="submit" value="詳細" class="labelBtn01">
                    </form>
                </td>
              </tr>
            <?php } ?>
        </table>
    </div>
</div>