<?php 

$id = (isset($_POST['id'])) ? $_POST['id'] : "";
$name = (isset($_POST['name'])) ? $_POST['name'] : "";
$match_type = (isset($_POST['match_type'])) ? $_POST['match_type'] : -1;

$data = Model::getBranch($id,$name,$match_type);
?>
<div id="content">
    <h2>支店一覧画面</h2>
    <form action="" method="POST"><input type="submit" value="新規作成" class="btn02"><input type="hidden" name="screen_type" value="<?=BRANCH_INSERT?>"></form>
    <form action="" method="POST">
        <input type="hidden" name="screen_type" value="<?=BRANCH?>">
        <table>
            <tr>
                <td><label for="id"><div class="label01">支店ID</div></label></td>
                <td><input type="text" name="id" id="id" value="<?=htmlspecialchars($id, ENT_QUOTES)?>" class="inputItem01" maxlength="6"></td>
            </tr>
            <tr>
                <td><label for="name"><div class="label01">支店名</div></label></td>
                <td><input type="text" name="name" id="name" value="<?=htmlspecialchars($name, ENT_QUOTES)?>" class="inputItem01" maxlength="40"></td>
            </tr>
            <tr>
                <td><div class="label01">一致タイプ</div></td>
                <td class="terms"><input type="radio" name="match_type" id="part" value=<?=PART?> checked><label for="part">部分一致</label></td>
                <td class="terms"><input type="radio" name="match_type" id="perfect" value=<?=PERFECT?>><Label for="perfect">完全一致</Label></td>
            </tr>
        </table>
        <input type="submit" value="検索" class="btn02">
    </form>
    <div class="scrollBox02">
        <table>
            <tr>
                <th class="label01">支店ID</th>
                <th class="label01" style="width: 300px;">支店名</th>
                <th class="label01" style="width: 100px;">略称</th>
                <th class="label01"></th>
            </tr>
            <?php while($row = $data->fetch(PDO::FETCH_ASSOC)){ $is_active = $row["active"]; ?>
            <tr>
                <td <?php ActivStyleLbl($is_active) ?>><?=$row["id"]?></td>
                <td <?php ActivStyleLbl($is_active) ?>><?=htmlspecialchars($row["name"], ENT_QUOTES)?></td>
                <td <?php ActivStyleLbl($is_active) ?>><?=htmlspecialchars($row["abbreviation"], ENT_QUOTES)?></td>
                <td <?php ActivStyleLbl($is_active) ?>>
                    <form action="" method="POST">
                        <input type="hidden" name="screen_type" value="<?=BRANCH_DETAILS?>">
                        <input type="hidden" name="id" value="<?=$row["id"]?>">
                        <input type="submit" value="詳細" <?php ActivStyleLblBtn($is_active) ?>>
                    </form>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>