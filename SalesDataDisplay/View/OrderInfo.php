<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/SalesSystem.php -->
<?php 
require_once '../config.php';
require_once '../Model.php';

$branch_id = (isset($_POST['branch_id'])) ? $_POST['branch_id'] : "";
$branch_name = (isset($_POST['branch_name'])) ? $_POST['branch_name'] : "";
$match_type = (isset($_POST['match_type'])) ? $_POST['match_type'] : -1;

$data = Model::getBranch($branch_id,$branch_name,$match_type);
?>
<div id="content">
    <h2>支店一覧画面</h2>
    <form action="" method="POST"><input type="submit" value="新規作成" class="btn02"><input type="hidden" name="screen_type" value="<?=BRANCH_INSERT?>"></form>
    <form action="" method="POST">
        <input type="hidden" name="screen_type" value="<?=BRANCH?>">
        <table>
        <tr>
            <td><div class="label01">注文ID</div></td><td><input type="text" name="branch_id" value="<?=$branch_id?>" class="inputItem01" maxlength="6"></td>
        </tr>
        <tr>
            <td><div class="label01">支店ID</div></td><td><input type="text" name="branch_name" value="<?=$branch_name?>" class="inputItem01" maxlength="6"></td>
        </tr>
        <tr>
            <td><div class="label01">顧客ID</div></td><td><input type="text" name="branch_name" value="<?=$branch_name?>" class="inputItem01" maxlength="6"></td>
        </tr>
        <tr>
            <td><div class="label01">輸送ID</div></td><td><input type="text" name="branch_name" value="<?=$branch_name?>" class="inputItem01" maxlength="6"></td>
        </tr>
        <tr>
            <td><div class="label01">注文日付</div></td><td><input type="date" name="branch_name" value="<?=$branch_name?>" class="inputItem01">～<input type="date" name="branch_name" value="<?=$branch_name?>" class="inputItem01"></td>
        </tr>
        <tr>
            <td><div class="label01">注文ステータス</div></td>
            <td  class="terms">
                <input type="checkbox" name="branch_name" value="0">商品確認中
                <input type="checkbox" name="branch_name" value="1">配達中
                <input type="checkbox" name="branch_name" value="2">配達済
            </td>
        </tr>
        </table>
        <input type="submit" value="検索" class="btn02">
    </form>
    <div class="scrollBox02">
        <table>
            <tr>
            <th class="label01">支店ID</th>
            <th class="label01">支店名</th>
            <th class="label01">略称</th>
            <th class="label01">詳細</th>
            </tr>
            <?php while($row = $data->fetch(PDO::FETCH_ASSOC)){ ?>
              <tr>
              <td <?php echo ($row["active"]) ? 'class="label01"' : 'class="label01-disabled"'; ?>><?=$row["id"]?></td>
              <td <?php echo ($row["active"]) ? 'class="label01"' : 'class="label01-disabled"'; ?> style="width: 300px;"><?=$row["name"]?></td>
              <td <?php echo ($row["active"]) ? 'class="label01"' : 'class="label01-disabled"'; ?> style="width: 100px;"><?=$row["abbreviation"]?></td>
              <td <?php echo ($row["active"]) ? 'class="label01"' : 'class="label01-disabled"'; ?>>
                  <form action="" method="POST">
                      <input type="hidden" name="screen_type" value="<?=BRANCH_DETAILS?>">
                      <input type="hidden" name="branch_id" value="<?=$row["id"]?>">
                      <input type="submit" value="詳細" <?php echo ($row["active"]) ? 'class="labelBtn01"' : 'class="labelBtn01-disabled" disabled'; ?>>
                    </form>
                </td>
              </tr>
            <?php } ?>
        </table>
    </div>
</div>