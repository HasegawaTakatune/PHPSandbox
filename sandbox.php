<?php

    $HTMLHead = '<!DOCTYPE html>
    <html lang="ja">
    <head>
      <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Document</title>
    </head>';


    $htmlTag = '<p>テスト</p>';

    // 1次元配列の表示
    $hello = array('test' => 'Hello', 'test2' => 'World', 'test3' => 'every', 'test4' => 'one');
    $HelloStr = '<table border="1">';
    foreach($hello as $key => $value)
        $HelloStr = $HelloStr . '<tr><td>' . $key . '</td><td>' . $value . '</td></tr>';            
    $HelloStr = $HelloStr . '</table><br><br>';

    // 2次元配列の表示
    $data = array(        
        array('base' => 'A拠点', 'category' => 'やすい屋', 'name' => '安価PC1', 'price' => '1000円'),
        array('base' => 'A拠点', 'category' => 'やすい屋', 'name' => '安価PC2', 'price' => '1200円'),
        array('base' => 'A拠点', 'category' => 'やすい屋', 'name' => '安価PC3', 'price' => '1300円'),
        array('base' => 'A拠点', 'category' => 'やすい屋', 'name' => '高価PC1', 'price' => '5000円'),
        array('base' => 'A拠点', 'category' => 'やすい屋', 'name' => '高価PC2', 'price' => '5600円'),
    
        array('base' => 'A拠点', 'category' => 'たかい屋', 'name' => '安価PC1', 'price' => '6000円'),
        array('base' => 'A拠点', 'category' => 'たかい屋', 'name' => '安価PC2', 'price' => '6400円'),
        array('base' => 'A拠点', 'category' => 'たかい屋', 'name' => '安価PC3', 'price' => '6500円'),
        array('base' => 'A拠点', 'category' => 'たかい屋', 'name' => '安価PC4', 'price' => '7000円'),
        array('base' => 'A拠点', 'category' => 'たかい屋', 'name' => '高価PC1', 'price' => '20000円'),
    
        array('base' => 'B拠点', 'category' => 'すごい屋', 'name' => '安価PC1', 'price' => '500円'),
        array('base' => 'B拠点', 'category' => 'すごい屋', 'name' => '安価PC2', 'price' => '600円'),
        array('base' => 'B拠点', 'category' => 'すごい屋', 'name' => '安価PC3', 'price' => '700円'),
        array('base' => 'B拠点', 'category' => 'すごい屋', 'name' => '安価PC4', 'price' => '800円'),
    
        array('base' => 'C拠点', 'category' => 'やばい屋', 'name' => '安価PC1', 'price' => '50000円'),
        array('base' => 'C拠点', 'category' => 'やばい屋', 'name' => '安価PC2', 'price' => '60000円'),
        array('base' => 'C拠点', 'category' => 'やばい屋', 'name' => '安価PC3', 'price' => '70000円'),
        );

    // データ一覧表示
    $details = '<table border="1">';
    foreach($data as $key => $value){
        if(!isset($value['category']) || !isset($value['name']) || !isset($value['price'])) continue;
        $details = $details . '<tr><td>' . $key . '</td><td>' . $value['category'] . '</td><td>' . $value['name'] . '</td><td>' . $value['price'] . '</td></tr>';
    }
    $details = $details . '</table><br><br>';
    
    // name一覧表示（重複無し）
    $nameList = array();
    foreach($data as $key => $value){
        if(!isset($value['name']))continue;
        if(!in_array($value['name'],$nameList))$nameList[] = $value['name'];
    }
    $nameListStr = '<table border="1">';
    foreach($nameList as $value){
        $nameListStr = $nameListStr . '<tr><td>' . $value . '</td></tr>';
    }
    $nameListStr = $nameListStr . '</table><br><br>';
    
    $itemList = array_keys($data,'やすい屋');
    $itemStr = '<table border="1">';
    foreach($itemList as $value){
        $itemStr = $itemStr . '<tr>' . $value . '</tr>';
    }
    $itemStr = $itemStr . '</table><br><br>';

    // Key一覧表示
    $keyDictionary['Name'] = 'Jony';
    $keyDictionary['Name'] = 'King';
    $keyDictionary['Name'] = 'Arlon';
    $keyDictionary['Age'] = 30;
    $keyDictionary['Age'] = 50;
    $keyDictionary['Gender'] = 'Man';
    $keys = array_keys($keyDictionary);
    $keysStr = '<table border="1">';
    foreach($keys as $key){
        $keysStr = $keysStr . '<tr><td>' . $key . '</td></tr>';
    }
    $keysStr = $keysStr . '</table><br><br>';

    // value一覧表示
    $values = array_values($keyDictionary);
    $valueStr = '<table border="1">';
    foreach($values as $value){
        $valueStr = $valueStr . '<tr><td>' . $value . '</td></tr>';
    }
    $valueStr = $valueStr . '</table><br><br>';

    // 画面生成
    $HTMLBody = '<body>'. $HelloStr . $details . $nameListStr . $itemStr . $keysStr . $valueStr . '</body></html>';
    echo $HTMLHead . $HTMLBody;
