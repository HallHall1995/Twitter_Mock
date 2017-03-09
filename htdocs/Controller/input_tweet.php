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

//クッキーに登録されているユーザーidを取得
$cookie_user_id = return_cookie_user_id($link);


if (!empty($cookie_user_id)) {
	//ユーザー情報取得
	$user_data = get_user_data($cookie_user_id, $link);
	
	//add_tweetのためにtweet_targetを入手
	if (!empty($_POST['tweet_target_user_id'])) {
        $target_id_ = (int)($_POST['tweet_target_user_id']);
        if ($target_id_ == $cookie_user_id) { //普通のtweetの場合
            $tweet_target_user_id = $cookie_user_id;
        } else { //他のユーザーにtweetする場合
            $tweet_target_user_id = $target_id_;
        }
    }
}

//DBとのコネクション切断
close_db_connect($link);

// トップページファイル読み込み
include_once '../../include/View/input_tweet.php';