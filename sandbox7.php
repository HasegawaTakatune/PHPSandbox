<?php
include('VariableMethod.php');

$filePath = 'VariableMethod.php';
$code = file_get_contents($filePath);
$safeCode = "\n" . htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
?>

<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <title>Document</title>
  <style>
    /* --- CSSスタイル定義 --- */
    body {
      background-color: #f0f0f0;
      /* 全体の背景色（わかりやすく変更） */
      padding: 20px;
      font-family: sans-serif;
    }

    /* 1. コードと結果をまとめる枠 */
    .code-set {
      max-width: 800px;
      margin: 0 auto;
      /* 中央寄せ */
    }

    /* 2. ソースコード部分（上） */
    .code-box {
      background-color: #1e1e1e;
      border-radius: 6px 6px 0 0;
      /* 下側は直角 */
      color: #d4d4d4;
      font-family: 'Menlo', 'Monaco', 'Consolas', monospace;
      overflow: hidden;
    }

    .code-box summary {
      background-color: #2d2d2d;
      padding: 10px 15px;
      cursor: pointer;
      list-style: none;
      /* 三角アイコン削除 */
      display: flex;
      justify-content: space-between;
      align-items: center;
      color: #ccc;
      font-size: 0.9rem;
      border-bottom: 1px solid #333;
    }

    /* Safari/Chromeのデフォルトアイコン消去 */
    .code-box summary::-webkit-details-marker {
      display: none;
    }

    /* 開閉矢印 */
    .code-box summary::after {
      content: '';
      width: 6px;
      height: 6px;
      border-right: 2px solid #888;
      border-bottom: 2px solid #888;
      transform: rotate(45deg);
      transition: 0.2s;
    }

    .code-box[open] summary::after {
      transform: rotate(225deg);
      border-color: #fff;
    }

    .code-content {
      padding: 0;
      margin: 0;
    }

    .code-content pre {
      margin: 0;
      padding: 15px;
      overflow-x: auto;
      line-height: 1.5;
      font-size: 0.9rem;
    }

    /* 3. 実行結果部分（下） */
    .result-window {
      background-color: #111;
      color: #eee;
      border: 1px solid #333;
      border-top: none;
      /* 上の線を消してつなげる */
      border-radius: 0 0 6px 6px;
      /* 上側は直角 */
      font-family: 'Menlo', 'Monaco', 'Consolas', monospace;
    }

    .result-title {
      background-color: #1a1a1a;
      color: #888;
      font-size: 0.75rem;
      padding: 5px 15px;
      font-weight: bold;
      border-top: 1px solid #333;
      border-bottom: 1px solid #333;
    }

    .result-content {
      margin: 0;
      padding: 15px;
      overflow-x: auto;
      font-size: 0.9rem;
    }
  </style>
</head>

<body>
  <h2>
    <a rel="stylesheet" href="https://www.php.net/manual/ja/functions.variable-functions.php" target="_blank" rel="noopener noreferrer">可変関数</a>のサンプル
  </h2>

  <div class="code-set">

    <details class="code-box">
      <summary><?php echo $filePath; ?></summary>
      <div class="code-content">
        <pre>
          <code>
            <?php echo $safeCode; ?>
          </code>
        </pre>
      </div>
    </details>

    <div class="result-window">
      <div class="result-title">OUTPUT</div>
      <pre class="result-content"><code>
      <?php main(); ?>
    </code></pre>
    </div>

  </div>
</body>

</html>