<?php

define('DB_HOST',   'db_host');         // データベースのホスト名又はIPアドレス
define('DB_USER',   'db_user');      // MySQLのユーザ名
define('DB_PASSWD', 'db_pass');          // MySQLのパスワード
define('DB_NAME',   'db_name');      // データベース名

define('ADD_PASSWORD_STR', 'add_pass');

define('HTML_CHARACTER_SET', 'UTF-8');    // HTML文字エンコーディング
define('DB_CHARACTER_SET',   'UTF8');     // DB文字エンコーディング

define('IMG_SIZE', 2*1000*1000);	      //登録する画像データの最大サイズ

define('MAX_NAME_STRLEN', 30);            //名前の最大文字数
define('MAX_MAIL_ADDRESS_STRLEN', 100);   //メールアドレスの最大文字数
define('MAX_PASSWORD_STRLEN', 100);       //パスワードの最大文字数
define('MAX_REAL_NAME_STRLEN', 30);       //実名の最大文字数
define('MAX_TWEET_COMMENT_STRLEN', 140);  //最大ツイート文字数

define('TIME_STANP', 60 * 60 * 24 * 30);  //cookieのタイムスタンプ

define('PUBLIC_STATE', 1);                //ユーザーステータス、フォロワー全てに公開
define('PRIVATE_STATE', 0);               //ユーザーステータス、友達にのみに公開

//define('SELECT_LIMIT',100);                //SELECTで入手するデータの最大個数
define('SELECT_LIMIT',5);