<!-- http://localhost/PHPSandbox/SalesDataDisplay/View/Home.php -->
<?php 
require_once '../Model.php';

Model::StartConnect();
$data = Model::getNotice();
?>
<div id="content">
  <h2>ホーム画面</h2>
  </br>
  <h3>お知らせ</h3>
  <div class="scrollBox">
    <ul>
      <?php while($row = $data->fetch(PDO::FETCH_ASSOC)){ ?>
      <li><?=$row["notification_date"]?>&nbsp;&nbsp;<?=$row["info"];?>&nbsp;&nbsp;<a href="<?=$row["reference"]?>"><?=$row["reference_name"]?></a></li>
      <?php } ?>
    </ul>
  </div>
</div>