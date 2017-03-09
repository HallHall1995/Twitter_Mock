<?php
//設定ファイル読み込み
require_once '../../include/const.php';
//汎用Modelファイル読み込み
include_once '../../include/Model/twitter_model.php';

//ユーザー情報
$user_data = array();

//エラーチェック
$err_msg = array();

//DB接続
$link = get_db_connect();

//都道府県情報（表示用）
$places_data = get_place_data($link);

//クッキーに登録されているユーザーidを取得
$cookie_user_id = return_cookie_user_id($link);

if (!empty($cookie_user_id)) {
	//ユーザー情報取得
	$user_data = get_user_data($cookie_user_id, $link);
}

//DBとのコネクション切断
close_db_connect($link);

if (empty($user_data)) {
	//エラーページ表示
	include_once '../../include/View/error.php';
} else {
	// トップページファイル読み込み
	include_once '../../include/View/profile_edit.php';
}
