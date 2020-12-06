<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/BranchInfo.php -->
<?php 
require_once '../config.php';
require_once '../Model.php';

Model::StartConnect();

$branch_id = (isset($_POST['branch_id'])) ? $_POST['branch_id'] : "";
$branch_name = (isset($_POST['branch_name'])) ? $_POST['branch_name'] : "";
$match_type = (isset($_POST['match_type'])) ? $_POST['match_type'] : -1;

Model::StartConnect();
$data = Model::getBranch($branch_id,$branch_name,$match_type);

?>
<div id="content">
    <h2>支店一覧画面</h2>
    <h3>条件</h3>
    <form action="" method="POST">
        <input type="hidden" name="screenType" value="<?=BRANCH?>">
        <table>
        <tr>
            <td><div class="label01">支店ID</div></td><td><input type="text" name="branch_id" value="<?=$branch_id?>" class="terms"></td>
        </tr>
        <tr>
            <td><div class="label01">支店名</div></td><td><input type="text" name="branch_name" value="<?=$branch_name?>" class="terms"></td>
        </tr>
        <tr>
            <td><div class="label01">一致タイプ</div></td><td><input type="radio" name="match_type" value=<?=PART?> checked class="terms">部分一致</td><td><input type="radio" name="match_type" value=<?=PERFECT?> class="terms">完全一致</td>
        </tr>
        </table>
        <input type="submit" value="検索" class="btn02">
    </form>
    <div class="scrollBox02">
        <table>
            <tr>
            <th class="scroll" style="width: 50px;">支店ID</th>
            <th class="scroll" style="width: 600px;">支店名</th>
            <th class="scroll" style="width: 350px;">略称</th>
            <th class="scroll" style="width: 50px;">詳細</th>
            </tr>
            <?php while($row = $data->fetch(PDO::FETCH_ASSOC)){ ?>
              <tr>
              <td class="scroll"><?=$row["branch_id"]?></td>
              <td class="scroll"><?=$row["name"]?></td>
              <td class="scroll"><?=$row["abbreviation"]?></td>
              <td class="scroll">
                  <form>
                      <input type="submit" value="詳細">
                      <input type="hidden" name="screenType" value="<?=HOME?>">
                      <input type="hidden" name="branch_id" value="<?=$row["branch_id"]?>">
                    </form>
                </td>
              </tr>
            <?php } ?>
        </table>
    </div>
</div>