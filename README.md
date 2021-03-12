# PHPSandbox  
  
## 概要  
PHPの練習用リポジトリです。  
sandbox + 番号はただのサンプルの連番（作成順）です。  
  
SalesDataDisplayフォルダ下はWebアプリの練習用に作成したモノです。  
データの登録・更新・削除・参照と帳票出力を行う。  
（注文データについては改竄防止という設定で変更不可）  
  
## 環境  
- 実行環境  Chrome  
- エディタ Visual Studio Code 1.54.1  
- 言語 PHP 8.0.0  
- ソフトウェア Xampp for Windows 8.0.0  
- サーバ Apache 2.4.46  
- データベース mysql 10.4.17  
- ライブラリ PHPSpreadsheet 1.15  
** 注意 **  
ライブラリの取得にはComposerを使用してSalesDataDisplayフォルダ配下にvendorフォルダを  
配置して使用しています。  
  
## ファイル構成図  
![SalesDataDisplay_Directory](https://user-images.githubusercontent.com/17695962/110878560-c27a8900-831e-11eb-8b37-46b751e0cb1e.png)

  
## システム関係図  
![SalesDataDisplay_Infra](https://user-images.githubusercontent.com/17695962/110878467-9a8b2580-831e-11eb-8f3f-76a9157adaaa.png)
  
## 画面フロー  
![SalesDataDisplay_ScreenFlow](https://user-images.githubusercontent.com/17695962/110878525-ad9df580-831e-11eb-871a-1384a7f8760c.png)

  
## DB関係図  
![SalesDataDisplay](https://user-images.githubusercontent.com/17695962/110878029-d1147080-831d-11eb-8a8a-1e9c0d677bfe.png)

  
## 参照  
- [PhpSpreadsheetのセル範囲コピー](https://stackoverflow.com/questions/34590622/copy-style-and-data-in-phpexcel)  
