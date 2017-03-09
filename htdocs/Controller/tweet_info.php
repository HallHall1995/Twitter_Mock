<?php
//設定ファイル読み込み
require_once '../../include/const.php';
//汎用Modelファイル読み込み
include_once '../../include/Model/twitter_model.php';
//tweet_info用のModelファイル読み込み
include_once '../../include/Model/tweet_info.php';

//エラー情報
$err_flag = true;

//ユーザー情報
$user_data = array();
//tweet情報
$tweet_data = array();
//retweet除法
$retweet_data = array();
//tweetしたユーザーの情報
$tweet_user_data = array();
//retweetしたユーザーの情報

//DB接続
$link = get_db_connect();

//クッキーに登録されているユーザーidを取得
$cookie_user_id = return_cookie_user_id($link);

if (!empty($cookie_user_id)) {
	//ユーザー情報取得
	$user_data = get_user_data($cookie_user_id, $link);
} else {
    $err_flag = false;
}

//tweet情報取得
if (isset($_POST['tweet_id'])) {
    $tweet_data = get_tweet_info((int)$_POST['tweet_id'], $link);
    if (!empty($tweet_data)) {
         $tweet_user_data = get_user_data($tweet_data['user_id'], $link);
    } else {
        $err_flag = false;
    }
//retweet情報取得
} else if(isset($_POST['retweet_id'])) {
    $retweet_data = get_retweet_info((int)$_POST['retweet_id'], $link);
    if (!empty($retweet_data)) {
        $tweet_user_data = get_user_data($tweet_data['tweet_user_id'], $link);
        $retweet_user_data = get_user_data($tweet_data['user_id'], $link);
    } else {
        $err_flag = false;
    }
} else {
    $err_flag = false;
}

//DBとのコネクション切断
close_db_connect($link);

include_once '../../include/View/tweet_info.php';
/*if ($err_flag) {
    include_once '../../include/View/tweet_info.php';
} else { //エラーの場合は
    //プロフィルールページへ移動
    header( "Location: profile.php" );
}*/