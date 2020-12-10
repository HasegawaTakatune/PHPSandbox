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
    <h3>条件</h3>
    <form action="" method="$_POST"><input type="submit" value="新規作成" class="btn02"></form>
    <form action="" method="POST">
        <input type="hidden" name="screen_type" value="<?=BRANCH?>">
        <table>
        <tr>
            <td><div class="label01">支店ID</div></td><td><input type="text" name="branch_id" value="<?=$branch_id?>" class="inputItem01" maxlength="6"></td>
        </tr>
        <tr>
            <td><div class="label01">支店名</div></td><td><input type="text" name="branch_name" value="<?=$branch_name?>" class="inputItem01" maxlength="40"></td>
        </tr>
        <tr>
            <td><div class="label01">一致タイプ</div></td><td><input type="radio" name="match_type" value=<?=PART?> checked class="inputItem01">部分一致</td><td><input type="radio" name="match_type" value=<?=PERFECT?> class="terms">完全一致</td>
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
              <td class="label01"><?=$row["id"]?></td>
              <td class="label01" style="width: 300px;"><?=$row["name"]?></td>
              <td class="label01" style="width: 100px;"><?=$row["abbreviation"]?></td>
              <td class="label01">
                  <form action="" method="POST">
                      <input type="hidden" name="screen_type" value="<?=BRANCH_DETAILS?>">
                      <input type="hidden" name="branch_id" value="<?=$row["id"]?>">
                      <input type="submit" value="詳細" class="labelBtn01">
                    </form>
                </td>
              </tr>
            <?php } ?>
        </table>
    </div>
</div>