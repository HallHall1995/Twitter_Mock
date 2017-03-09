<?php
//設定ファイル読み込み
require_once '../../include/const.php';
//汎用Modelファイル読み込み
include_once '../../include/Model/twitter_model.php';
//ツイート情報取得のためにtweet_info.phpを読み込み
include_once '../../include/Model/tweet_info.php';

//ユーザー情報
$user_data = array();

//tweet情報
$tweet_data = array();

//エラーチェック
$err_flag = true;

//DB接続
$link = get_db_connect();

//クッキーに登録されているユーザーidを取得
$cookie_user_id = return_cookie_user_id($link);
if (empty($cookie_user_id)) $err_flag = false;

//retweetするtweet情報を取得
if ((!empty($_POST['tweet_id'])) && ($err_flag) ) {
    $target_tweet_id = (int)$_POST['tweet_id'];
    $tweet_data = get_tweet_info($target_tweet_id, $link);
    //tweetしたユーザー情報取得
    if (!empty($tweet_data)) {
         $tweet_user_data = get_user_data($tweet_data['user_id'], $link);
    } else {
        $err_flag = false;
    }
} else {
    $err_flag = false;
}

//retweet情報登録
if ($err_flag) {
    // リツイート入力ページ読み込み
    include_once '../../include/View/input_retweet.php';
} else {
    //プロフィルールページへ移動
    header( "Location: profile.php" );
}