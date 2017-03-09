<?php
//設定ファイル読み込み
require_once '../../include/const.php';
//汎用Modelファイル読み込み
include_once '../../include/Model/twitter_model.php';
//add_tweet_to_DB用Modelファイル読み込み
include_once '../../include/Model/add_tweet.php';

//ユーザー情報
$user_data = array();

//tweet情報
$tweet_data = array();

//エラーチェック
$err_flag;

//DB接続
$link = get_db_connect();

//クッキーに登録されているユーザーidを取得
$cookie_user_id = return_cookie_user_id($link);

//tweetする対象
if (!empty($_POST['tweet_target'])) {
    $target_user_id = (int)$_POST['tweet_target'];
}

if (!empty($cookie_user_id)) {
	//ユーザー情報取得
	$user_data = get_user_data($cookie_user_id, $link);
	//tweet情報取得
	if (isset($_POST['tweet_comment'])) $tweet_data['comment'] = entity_str($_POST['tweet_comment']);
	if (isset($_FILES["image_1"]["name"])) $tweet_data['image_1'] = $_FILES["image_1"];
	if (isset($_FILES["image_2"]["name"])) $tweet_data['image_2'] = $_FILES["image_2"];
	if (isset($_FILES["image_3"]["name"])) $tweet_data['image_3'] = $_FILES["image_3"];
	if (isset($_FILES["image_4"]["name"])) $tweet_data['image_4'] = $_FILES["image_4"];
	//tweet情報をDBに記録(エラー情報記録)
	$err_flag = tweet_process($cookie_user_id, $target_user_id, $tweet_data, $link);
}

//DBとのコネクション切断
close_db_connect($link);

//プロフィルールページへ移動
header( "Location: profile.php" );