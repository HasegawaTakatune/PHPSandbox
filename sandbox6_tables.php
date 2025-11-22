<!DOCTYPE html>
<html lang="ja">

<head>
    <?php
    $rows = isset($_POST['items']) ? $_POST['items'] : null;
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script type="text/javascript" src="techAcademy.js"></script>

    <title>Sword</title>
</head>

<body>

    <table border="1" id="sword-table">
        <tr>
            <th>No.</th>
            <th>名前（かな）</th>
        </tr>
        <?php for ($i = 0; $i < count($rows); $i++) { ?>
            <tr>
                <td><?= ($i + 1) ?></td>
                <td><?= $rows[$i] ?></td>
            </tr>
        <?php } ?>
    </table>

</body>