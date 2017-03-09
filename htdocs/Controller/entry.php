<?php
//設定ファイル読み込み
require_once '../../include/const.php';
//汎用Modelファイル読み込み
include_once '../../include/Model/twitter_model.php';
//entry.php用Modelファイル読み込み
include_once '../../include/Model/entry.php';

//ユーザー情報
$user_data = array();

//エラーチェック
$err_msg = array();

//DB接続
$link = get_db_connect();

//都道府県情報（表示用）
$places_data = get_place_data($link);

//POSTデータ受け取り
if (isset($_POST['name']))  $user_data['name'] = entity_str($_POST['name']);
if (isset($_POST['mail_address'])) $user_data['mail_address'] = entity_str($_POST['mail_address']);
if (isset($_POST['password'])) $user_data['password'] = entity_str($_POST['password']);
if (isset($_POST['real_name']))  $user_data['real_name'] = entity_str($_POST['real_name']);

//正しいデータか調べる
$err_msg = entry_data_check($user_data, $link);
//アカウント登録
//登録
if (empty($err_msg)) {
	//データ登録
	if(entry_to_twitter($user_data, $link)) {
		//cookieセット
		set_cookie($user_data['name'], $link);
	} else {
		$err_msg[] = '登録失敗';
	}
} 

//DBとのコネクション切断
close_db_connect($link);

// トップページファイル読み込み
include_once '../../include/View/entry.php';