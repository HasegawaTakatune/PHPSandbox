<?php $data = Model::getNotice();?>
<div id="content">
    <h2>ホーム画面</h2>
    </br>
    <h3>お知らせ</h3>
    <div class="scrollBox">
        <ul>
            <?php if(isset($data)){ while($row = $data->fetch(PDO::FETCH_ASSOC)){ ?>
            <li><?=htmlspecialchars($row["notification_date"], ENT_QUOTES)?>&nbsp;&nbsp;<?=htmlspecialchars($row["info"], ENT_QUOTES)?>&nbsp;&nbsp;
                <a href="<?=$row["reference"]?>"><?=htmlspecialchars($row["reference_name"], ENT_QUOTES)?></a>
            </li>
            <?php }} ?>
        </ul>
    </div>
</div>