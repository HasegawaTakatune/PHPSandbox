<?php 
// DB情報
define('DNS','mysql:dbname=sales_system;host=localhost');
define('USER','root');
define('PASSWORD',''); //define('PASSWORD','secret');

// 画面名
define('HOME',1);
define('BRANCH',2);
define('ORDER',3);
define('CUSTOMER',4);
define('PRODUCT',5);
define('REPORT',6);

define('BRANCH_DETAILS',7);
define('BRANCH_INSERT',8);

define('ORDER_DETAILS',9);

define('CUSTOMER_DETAILS',10);

// パターンマッチタイプ
define('PART',0);
define('PERFECT',1);

// 活性フラグ
define('ACTIVE',0);
define('DEACTIVE',1);
define('ACTIVE_DEACTIVE',2);

// 注文ステータス
define('CHECKING','0');
define('DELIVERY','1');
define('DELIVERED','2');

// 共通
// define('GENDER','GENDER');
// define('CATEGORY','CATEGORY');
// define('ORDER_STATE','ORDER_STATE');

// デバッグ
define('IS_DEBUG',true);

// 活性スタイル
function ActivStyle($active){if(!$active)echo 'disabled';}
function ActivStyleInp($active){echo ($active) ? 'class="inputItem01"' : 'class="inputItem01-disabled" disabled';}
function ActivStyleBtn($active){echo ($active) ? 'class="btn02"' : 'class="btn02-disabled" disabled';}
?>