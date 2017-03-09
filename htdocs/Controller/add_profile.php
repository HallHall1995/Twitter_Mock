<?php
//設定ファイル読み込み
require_once '../../include/const.php';
//汎用Modelファイル読み込み
include_once '../../include/Model/twitter_model.php';
//entry.php用Modelファイル読み込み
include_once '../../include/Model/add_profile.php';

//エラーメッセージ
$err_msg = array();

//DB接続
$link = get_db_connect();

//クッキーに登録されているユーザーidを取得
$cookie_user_id = return_cookie_user_id($link);

//画像データ登録
 if (isset($_FILES["user_img"]["name"]) == true) {
 	if (!set_to_profile_image($_FILES["user_img"], $cookie_user_id, $link)) {
 		$err_msg[] = '画像アップロード失敗';
 	}
}

//住んでいる都道府県情報登録
 if (isset($_POST['user_place'])) {
 	if (!set_to_profile_place($_POST['user_place'], $cookie_user_id, $link)) {
 		$err_msg[] = '都道府県情報更新失敗';
 	}
    
}

//公開ステータス登録
 if (isset($_POST['user_state'])) {
    if (!set_to_user_state($_POST['user_state'], $cookie_user_id, $link)){
    	$err_msg[] = '公開ステータス更新失敗';
    }
}

//ユーザープロフィール文章登録
 if (isset($_POST['user_info'])) {
    if (set_to_user_comment($_POST['user_info'], $cookie_user_id, $link)) {
    	$err_msg[] = 'ユーザープロフィール文章更新失敗';
    }
}

//DBとのコネクション切断
close_db_connect($link);

//プロフィルールページへ移動
header( "Location: profile.php" );