<!-- http://localhost/PHPSandbox/sandbox2.php -->
<?php
$HTMLHead = '<!DOCTYPE html>
    <html lang="ja">
    <head>
      <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <meta http-equiv="X-UA-Compatible" content="ie=edge">
      <title>Document</title>
    </head>';

$show = '<br>Hello world<br>';

// 2次元配列の表示    
$data = array(
  array('base' => 'A拠点', 'category' => 'やすい屋', 'name' => '安価PC1', 'price' => 1000),
  array('base' => 'A拠点', 'category' => 'やすい屋', 'name' => '安価PC2', 'price' => 1200),
  array('base' => 'A拠点', 'category' => 'やすい屋', 'name' => '安価PC3', 'price' => 1300),
  array('base' => 'A拠点', 'category' => 'やすい屋', 'name' => '高価PC1', 'price' => 5000),
  array('base' => 'A拠点', 'category' => 'やすい屋', 'name' => '高価PC2', 'price' => 5600),

  array('base' => 'A拠点', 'category' => 'たかい屋', 'name' => '安価PC1', 'price' => 6000),
  array('base' => 'A拠点', 'category' => 'たかい屋', 'name' => '安価PC2', 'price' => 6400),
  array('base' => 'A拠点', 'category' => 'たかい屋', 'name' => '安価PC3', 'price' => 6500),
  array('base' => 'A拠点', 'category' => 'たかい屋', 'name' => '安価PC4', 'price' => 7000),
  array('base' => 'A拠点', 'category' => 'たかい屋', 'name' => '高価PC1', 'price' => 20000),

  array('base' => 'B拠点', 'category' => 'すごい屋', 'name' => '安価PC1', 'price' => 500),
  array('base' => 'B拠点', 'category' => 'すごい屋', 'name' => '安価PC2', 'price' => 600),
  array('base' => 'B拠点', 'category' => 'すごい屋', 'name' => '安価PC3', 'price' => 700),
  array('base' => 'B拠点', 'category' => 'すごい屋', 'name' => '安価PC4', 'price' => 800),

  array('base' => 'C拠点', 'category' => 'やばい屋', 'name' => '安価PC1', 'price' => 50000),
  array('base' => 'C拠点', 'category' => 'やばい屋', 'name' => '安価PC2', 'price' => 60000),
  array('base' => 'C拠点', 'category' => 'やばい屋', 'name' => '安価PC3', 'price' => 70000),
);

// baseを取得して表示
$baseA = array_values($data);
$show = $show . '<table border="1">';
$show = $show . '<tr><td>BASE</td></tr>';
foreach ($baseA as $value)
  $show = $show . '<tr><td>' . $value['base'] . '</td></tr>';
$show = $show . '</table><br><br>';

// データ一覧表示
$show = $show . '<table border="1">';
$show = $show . '<tr><td>BASE</td><td>CATEGORY</td><td>NAME</td><td>PRICE</td></tr>';
for ($i = 0; $i < count($data); $i++) {
  if (!isset($data[$i]))
    continue;
  $show = $show . '<tr><td>' . $data[$i]['base'] . '</td><td>' . $data[$i]['category'] . '</td><td>' . $data[$i]['name'] . '</td><td>' . $data[$i]['price'] . '\\</td></tr>';
}
$show = $show . '</table><br><br>';

// 多次元連想配列
$roster = array(
  'Jony' => array('AGE' => 10, 'GENDER' => 'MAN'),
  'Kein' => array('AGE' => 12, 'GENDER' => 'MAN'),
  'Maria' => array('AGE' => 15, 'GENDER' => 'WOMAN'),
);

// 多次元連想配列の一覧表示
$show = $show . '<table border="1">';
$show = $show . '<tr><td>NAME</td><td>AGE</td><td>GENDER</td><tr>';
foreach ($roster as $key => $value) {
  $show = $show . '<tr><td>' . $key . '</td><td>' . $value['AGE'] . '</td><td>' . $value['GENDER'] . '</td><tr>';
}
$show = $show . '</table><br><br>';

// HTML生成
$HTMLBody = '<body>' . $show . '</body></html>';
echo $HTMLHead . $HTMLBody;
