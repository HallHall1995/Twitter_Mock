<?php
//設定ファイル読み込み
require_once '../../include/const.php';
//汎用Modelファイル読み込み
include_once '../../include/Model/twitter_model.php';
//add_retweet.php用Modelファイル読み込み
include_once '../../include/Model/add_retweet.php';

//ユーザー情報
$user_data = array();

//retweet情報
$retweet_data = array();

//エラーチェック
$err_flag = true;

//DB接続
$link = get_db_connect();

//クッキーに登録されているユーザーidを取得
$cookie_user_id = return_cookie_user_id($link);
if (empty($cookie_user_id)) $err_flag = false;

if (isset($_POST['target_tweet_id'])) $retweet_data['target_tweet_id'] = (int)$_POST['target_tweet_id'];
if (isset($_POST['retweet_comment'])) $retweet_data['retweet_comment'] = entity_str($_POST['retweet_comment']);
if (isset($_FILES["image"]["name"])) $retweet_data['image'] = $_FILES["image"];

$err_flag = check_retweet_data($retweet_data);

//retweet情報登録
if ($err_flag) {
    $err_flag = add_retweet($retweet_data, $cookie_user_id, $link);
}

//DBとのコネクション切断
close_db_connect($link);

if ($err_flag) {
    //プロフィルールページへ移動
    header( "Location: profile.php" );
} else {
    echo '失敗';
}