<?php
//設定ファイル読み込み
require_once '../../include/const.php';
//汎用Modelファイル読み込み
include_once '../../include/Model/twitter_model.php';
//汎用Modelファイル読み込み
include_once '../../include/Model/login.php';

//エラーフラグ
$err_flag = true;

//DB接続
$link = get_db_connect();

//データが揃っていたら
if ( (!empty($_POST['name'])) && (!empty($_POST['pass'])) ){
    $name = entity_str($_POST['name']);
    $pass = entity_str($_POST['pass']);
} else {
    $err_flag = false;
}

if ($err_flag) {
    $err_flag = get_cookie_user_data($name, $pass, $link);
}

//DBとのコネクション切断
close_db_connect($link);

if ($err_flag) {
    //プロフィルールページへ移動
    header( "Location: profile.php" );
} else {
    //ログイン画面に戻る
	include_once '../../include/View/index.php';
}