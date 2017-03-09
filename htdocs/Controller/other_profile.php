<?php
//設定ファイル読み込み
require_once '../../include/const.php';
//汎用Modelファイル読み込み
include_once '../../include/Model/twitter_model.php';


//ユーザー情報（ログインしているユーザー）
$user_data = array();

//表示する(ターゲット)ユーザー情報
$target_user_data = array();
$target_user_id;

//ターゲットをすでにフォローしているかどうか
$is_follow;

//フォロー情報
$follow_count;

//フォロワー情報
$follower_count;

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

//ユーザー情報取得
$user_data = get_user_data($cookie_user_id, $link);

//ターゲットユーザー情報取得
if (isset($_POST['target_id'])) {
    if (ctype_digit($_POST['target_id'])) { //送られてきたデータが数値か調べる
        $target_user_id = $_POST['target_id'];
        $target_user_data = get_user_data($target_user_id, $link);
        
        //フォローしているか調べる
        $is_follow = check_is_follow($cookie_user_id, $target_user_id, $link);
        
        //フォロー情報取得
        $follow_count = get_follow_count($target_user_id, $link);

        //フォロワー情報取得
        $follower_count = get_follower_count($target_user_id, $link);
        
        //tweet情報取得
        $tweet_data = get_tweet_data($target_user_id, $offset_time_line_id, $link);
        //tweet数
        $tweet_count = count($tweet_data);
    }
}


//DBとのコネクション切断
close_db_connect($link);

// トップページファイル読み込み
include_once '../../include/View/other_profile.php';