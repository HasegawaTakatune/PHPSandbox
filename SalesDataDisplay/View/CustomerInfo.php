<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/SalesSystem.php -->
<?php 
require_once '../config.php';
require_once '../Model.php';

$id = (isset($_POST['id'])) ? $_POST['id'] : "";
$name = (isset($_POST['name'])) ? $_POST['name'] : "";
$match_type = (isset($_POST['match_type'])) ? $_POST['match_type'] : -1;

$data = Model::getCustomer($id,$name,$match_type);
?>
<div id="content">
    <h2>顧客一覧画面</h2>
    <form action="" method="POST"><input type="submit" value="新規作成" class="btn02"><input type="hidden" name="screen_type" value="<?=CUSTOMER_INSERT?>"></form>
    <form action="" method="POST">
        <input type="hidden" name="screen_type" value="<?=CUSTOMER?>">
        <table>
        <tr>
            <td><label for="id"><div class="label01">顧客ID</div></label></td>
            <td><input type="text" name="id" id="id" value="<?=$id?>" class="inputItem01" maxlength="6"></td>
        </tr>
        <tr>
            <td><label for="name"><div class="label01">名前</div></label></td>
            <td><input type="text" name="name" id="name" value="<?=$name?>" class="inputItem01" maxlength="40"></td>
        </tr>
        <tr>
            <td><div class="label01">一致タイプ</div></td>
            <td class="terms"><input type="radio" name="match_type" id="part" value=<?=PART?> checked><label for="part">部分一致</label></td>
            <td class="terms"><input type="radio" name="match_type" id="perfect" value=<?=PERFECT?>><label for="perfect">完全一致</label></td>
        </tr>
        </table>
        <input type="submit" value="検索" class="btn02">
    </form>
    <div class="scrollBox02">        
        <table>
            <tr>
            <th class="label01">顧客ID</th>
            <th class="label01" style="width: 120px;">名前</th>
            <th class="label01" style="width: 40px;">年齢</th>
            <th class="label01" style="width: 100px;">性別</th>
            <th class="label01" style="width: 120px;">メール</th>
            <th class="label01" style="width: 120px;">電話番号</th>
            <th class="label01"></th>
            </tr>
            <?php while($row = $data->fetch(PDO::FETCH_ASSOC)){ ?>
              <tr>
              <td <?php echo ($row["active"]) ? 'class="label01"' : 'class="label01-disabled"'; ?>><?=$row["id"]?></td>
              <td <?php echo ($row["active"]) ? 'class="label01"' : 'class="label01-disabled"'; ?>><?=$row["last_name"] . "&nbsp" . $row["first_name"] ?></td>              
              <td <?php echo ($row["active"]) ? 'class="label01"' : 'class="label01-disabled"'; ?>><?=$row["age"]?></td>
              <td <?php echo ($row["active"]) ? 'class="label01"' : 'class="label01-disabled"'; ?>><?=$row["gender"]?></td>
              <td <?php echo ($row["active"]) ? 'class="label01"' : 'class="label01-disabled"'; ?>><?=$row["email"]?></td>
              <td <?php echo ($row["active"]) ? 'class="label01"' : 'class="label01-disabled"'; ?>><?=$row["tell"]?></td>
              <td <?php echo ($row["active"]) ? 'class="label01"' : 'class="label01-disabled"'; ?>>
                    <form action="" method="POST">
                      <input type="hidden" name="screen_type" value="<?=CUSTOMER_DETAILS?>">
                      <input type="hidden" name="id" value="<?=$row["id"]?>">
                      <input type="submit" value="詳細" <?php ActivStyleLblBtn($row["active"]) ?>>
                    </form>
                </td>
              </tr>
            <?php } ?>
        </table>
    </div>
</div>