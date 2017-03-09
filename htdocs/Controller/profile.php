<?php
//設定ファイル読み込み
require_once '../../include/const.php';
//汎用Modelファイル読み込み
include_once '../../include/Model/twitter_model.php';


//ユーザー情報
$user_data = array();
//tweet情報
$tweet_data = array();
//フォロー情報
$follow_count = 0;
//フォロワー情報
$follower_count = 0;

//ページング番号受け取り
if (isset($_POST['next_time_btn'])) {
    //前のページから飛んできた場合
    $offset_time_line_id = (int)$_POST['next_time_line_id'];
} else if(isset($_POST['before_time_btn'])) {
    //後のページから飛んできた場合（戻ってきた場合）
    $offset_time_line_id = (int)$_POST['before_time_line_id'];
} else { //普通にアクセスした場合
    $offset_time_line_id = 0;
}


//DB接続
$link = get_db_connect();

//クッキーに登録されているユーザーidを取得
$cookie_user_id = return_cookie_user_id($link);

if (!empty($cookie_user_id)) {
	//ユーザー情報取得
	$user_data = get_user_data($cookie_user_id, $link);
	
	//フォロー数取得
	$follow_count = get_follow_count($cookie_user_id, $link);
	
	//フォロワー数取得
	$follower_count = get_follower_count($cookie_user_id, $link);
	
	//タイムラインのtweet情報取得
    $tweet_data = get_time_line_data($cookie_user_id, $offset_time_line_id,$link);
    //タイムラインの表示件数を取得
    $time_line_count = count($tweet_data);
    
    //自分のtweet数取得
    $tweet_count = get_tweet_count($cookie_user_id, $link);
    
    //タイムラインで表示するユーザー情報入手
	$time_line_users_data = get_user_data_for_time_line($tweet_data, $link);
}


//DBとのコネクション切断
close_db_connect($link);

if (empty($user_data)) {
	//エラーページ表示
	include_once '../../include/View/error.php';
} else {
	// トップページファイル読み込み
	include_once '../../include/View/profile.php';
}
