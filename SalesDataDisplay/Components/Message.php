<?php
$err_cls = 'class="err-msg"';
$success_cls = 'class="scss-msg"';

// 実行成功メッセージ
define('MSG_SUCCESS_UPDATE',"<div ${success_cls}>更新が成功しました。</div>"); // 更新成功
define('MSG_SUCCESS_INSERT',"<div ${success_cls}>新規登録が成功しました。</div>"); // 登録成功

// 実行失敗メッセージ
define('MSG_FAILED_UPDATE',"<div ${err_cls}>更新に失敗しました。もう一度実行してください。</div>"); // 更新失敗
define('MSG_FAILED_INSERT',"<div ${err_cls}>登録に失敗しました。もう一度実行してください。</div>"); // 登録失敗

define('MSG_MISTAKE_EMAIL_PASSWORD',"<div ${err_cls}>メールアドレスまたはパスワードが間違っています。</div>"); // メールアドレス重複
define('MSG_DUPLICATE_EMAIL',"<div ${err_cls}>入力したメールアドレスは既に登録されています。</div>"); // メールアドレス重複
define('MSG_DUPLICATE_USER_NAME',"<div ${err_cls}>入力したユーザ名は既に登録されています。</div>"); // ユーザ名重複
?>