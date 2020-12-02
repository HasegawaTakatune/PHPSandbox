<!-- http://localhost/PHPSandbox/SalesDataDisplay/ClassSample/Main.php -->
<?php 
$selected = "MENU";
?>
<html>
    <link href="..\CSS\CommonView.css" rel="stylesheet">
    <head>
        <meta charset="utf-8">
        <title>注文管理システム</title>
    </head>
    <body>
       <h1>注文管理システム</h1>

       <div id="parent">
        <div id="side-menu">
            <a href="#" class="btn01">Button</a>
            <a href="#" class="btn01">Button</a>
            <a href="#" class="btn01">Button</a>
            <a href="#" class="btn01">Button</a>
            <a href="#" class="btn01">Button</a>
            <a href="#" class="btn01">Button</a>
            <a href="#" class="btn01">Button</a>
            <a href="#" class="btn01">Button</a>
        </div>
        <div id="content">
            <?php
            require 'Menu.php';

            $content = new Menu();
            $content->init("Title > Menu");
            echo $content->ShowView();
            
            ?>
        </div>
       </div>
    </body>
</html>