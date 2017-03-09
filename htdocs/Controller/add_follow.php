<?php
//設定ファイル読み込み
require_once '../../include/const.php';
//汎用Modelファイル読み込み
include_once '../../include/Model/twitter_model.php';
//add_follow.php用Modelファイル読み込み
include_once '../../include/Model/add_follow.php';


//ユーザー情報
$user_data = array();

//表示する(ターゲット)ユーザー情報
$target_user_data = array();
$target_user_id;

//フォロー情報
$follow_count;
//フォロワー情報
$follower_count;

//ターゲットをすでにフォローしているかどうか
$is_follow;

//フォロー情報(フォロー、フォロワー情報)
//$follow_data = array();

//DB接続
$link = get_db_connect();

//クッキーに登録されているユーザーidを取得
$cookie_user_id = return_cookie_user_id($link);

//ユーザー情報取得
if ($cookie_user_id != null) {
	$user_data = get_user_data($cookie_user_id, $link);
}

//tweet情報取得
//$tweet_data = get_tweet_data($cookie_user_id);

//ターゲットユーザー情報取得
if (isset($_POST['target_id'])) {
    if (ctype_digit($_POST['target_id'])) { //送られてきたデータが数値か調べる
    
        $target_user_id = $_POST['target_id'];
        $target_user_data = get_user_data($target_user_id, $link);
        
        //ユーザーをフォロー
        if (isset($_POST['add_follow'])) {  //POST情報が届いているか調べる
            //フォローに成功したらtrue
            $is_follow = add_follow_data($cookie_user_id, $target_user_id, $link);
        } else if (isset($_POST['remove_follow'])) {    //フォローを外す
        	//フォローを外せたらfalse
            $is_follow = !remove_follow_data($cookie_user_id, $target_user_id, $link);
        }
        
        //フォロー情報取得
        $follow_count = get_follow_count($target_user_id, $link);

        //フォロワー情報取得
        $follower_count = get_follower_count($target_user_id, $link);
        
        //tweet情報取得
        $tweet_data = get_tweet_data($target_user_id, 0,$link);
        //tweet数
        $tweet_count = count($tweet_data);
    }
}

//DBとのコネクション切断
close_db_connect($link);

// トップページファイル読み込み
include_once '../../include/View/other_profile.php';
