<!-- http://localhost/PHPSandbox/sandbox3.php -->
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Count up</title>
</head>
<body>
  <form action="" method="POST">
      
      <!-- plusボタン押下で加算 -->
      <?php
      $sum = 0;
      if(isset($_POST['sum']))$sum = $_POST['sum'];
      if(isset($_POST['plus']))$sum++;
      ?>
      <input type="hidden" name="sum" value="<?=$sum?>">
      <input type="submit" name="plus" value="plus">
      </br>
      <?=$sum?>
      </br>

      <!-- ラジオボタンの値保持 -->
      <?php 
      $testradio = "";
      if(isset($_POST['testradio']))$testradio = $_POST['testradio'];
      ?>
      <!-- <input type="radio" name="testradio" value="左" >左
      <input type="radio" name="testradio" value="中央">中央
      <input type="radio" name="testradio" value="右">右 -->

      <?php 
      /*<?php if($testradio == "左"){?>
        <input type="radio" name="testradio" value="左" checked>左
      <?php }else{?>
        <input type="radio" name="testradio" value="左" >左
      <?php }?>

      <?php if($testradio == "中央"){?>
        <input type="radio" name="testradio" value="中央" checked>中央
      <?php }else{?>
        <input type="radio" name="testradio" value="中央" >中央
      <?php }?>

      <?php if($testradio == "右"){?>
        <input type="radio" name="testradio" value="右" checked>右
      <?php }else{?>
        <input type="radio" name="testradio" value="右" >右
      <?php }?>*/
      ?>
      <?php 
      switch($testradio){
        case "左":
          echo '<input type="radio" name="testradio" value="左" checked>左';
          echo '<input type="radio" name="testradio" value="中央" >中央';
          echo '<input type="radio" name="testradio" value="右" >右';
        break;

        case "中央":
          echo '<input type="radio" name="testradio" value="左" >左';
          echo '<input type="radio" name="testradio" value="左中央" >左中央';
          echo '<input type="radio" name="testradio" value="中央" checked>中央';
          echo '<input type="radio" name="testradio" value="右中央" >右中央';
          echo '<input type="radio" name="testradio" value="右" >右';
        break;

        case "右":
          echo '<input type="radio" name="testradio" value="左" >左';
          echo '<input type="radio" name="testradio" value="中央" >中央';
          echo '<input type="radio" name="testradio" value="右" checked>右';
        break;

        default:
          echo '<input type="radio" name="testradio" value="左" >左';
          echo '<input type="radio" name="testradio" value="中央" >中央';
          echo '<input type="radio" name="testradio" value="右" >右';
      break;
      }

      // $patarn0 = '<input type="radio" name="testradio" value="左" >左 <input type="radio" name="testradio" value="中央" >中央 <input type="radio" name="testradio" value="右" >右';
      // $patarnA = '<input type="radio" name="testradio" value="左" checked>左 <input type="radio" name="testradio" value="中央" >中央 <input type="radio" name="testradio" value="右" >右';
      // $patarnB = '<input type="radio" name="testradio" value="左" >左 <input type="radio" name="testradio" value="中央" checked>中央 <input type="radio" name="testradio" value="右" >右';
      // $patarnC = '<input type="radio" name="testradio" value="左" >左 <input type="radio" name="testradio" value="中央" >中央 <input type="radio" name="testradio" value="右" checked>右';

      // if($testradio == "")$patarn0;
      // else echo ($testradio == "左") ? $patarnA : ($testradio == "中央") ? $patarnB : $patarnC;
      echo '</br>' . $testradio;
      ?>
      </br></br></br>

      <!-- ラジオボタンの値保持　省スペース版 -->
      <?php 
      $radioChecked = -1;
      if(isset($_POST['radio1']))$radioChecked = $_POST['radio1'];
      ?>
      <input type="radio" name="radio1" value="0" <?php if($radioChecked == 0)echo 'checked' ?>>剣
      <input type="radio" name="radio1" value="1" <?php if($radioChecked == 1)echo 'checked' ?>>盾
      <input type="radio" name="radio1" value="2" <?php if($radioChecked == 2)echo 'checked' ?>>鎧
      
    </br>
    
    <!-- プルダウン選択の値保持 -->
    <?php 
    $typeList = array('火','水','木','風','雷','土','闇','光');
    $typeSel = (isset($_POST['select1'])) ? $_POST['select1'] : -1;
    ?>
    <select name="select1">
    <?php 
    foreach($typeList as $key => $value)
      echo "<option value=${key} " . (($key == $typeSel) ? "selected" : "") . ">${value}</option>";
    ?>
    </select>

      <!-- <select name="select1">
        <option value="0">火</option>
        <option value="1">水</option>
        <option value="2">木</option>
        <option value="3">風</option>
        <option value="4">雷</option>
        <option value="5" selected>土</option>
      </select> -->
  </form>

  <!-- おみくじ -->
  <?php
  $rnd = rand(0,1);
  if($rnd == 0)echo "大吉";
  else echo "吉"
  ?>

  <!-- 動的に表示内容を変更できるかの検証
       PHPだけの場合はGET・POSTを使って一回画面遷移をしないと
       表示内容は更新されない？
       
       時計表示
       参照：https://minagawa.design/blog/webdesign/js-php-date-realtime/
       -->
  <?php
  $onoff = 'ON';
  ?>
  <form>
    <input type="button" name="OnOff" value="On/Off" onclick="<?php $onoff = ($onoff == 'ON') ? 'ON' : 'OFF'; ?>" >
    </br>
    <?=$onoff?>
  </form>

  </br>
  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
  <script>
    $(function(){
      var now; //タイムスタンプ
      $.getJSON('datetime.php')
      .done(function(json){
        for(var i in json){
          now = json[i] * 1000;
        }
      })
      .fail(function(){
        console.log('json_error');
      });

      function showtime(){
        today = new Date(now);
        $weekday = ['日','月','火','水','木','金','土'];
        month = today.getMonth() + 1;
        $('#datetime').html(month + "月" + today.getDate() + "日(" + $weekday[today.getDay()] + ")" + today.getHours() + ":" + ('0' + today.getMinutes()).slice(-2) + ":" + ('0' + today.getSeconds()).slice(-2));
        now += 1000;
      }
      setInterval(showtime,1000);
    });
  </script>
  <div id="datetime"></div>

</body>
</html>