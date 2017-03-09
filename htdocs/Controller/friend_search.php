<?php
//設定ファイル読み込み
require_once '../../include/const.php';
//汎用Modelファイル読み込み
include_once '../../include/Model/twitter_model.php';
//friend_search.php用Modelファイル読み込み
include_once '../../include/Model/friend_search.php';

//ユーザー情報
$user_data = array();

//検索した友達情報
$search_data = array();

//DB接続
$link = get_db_connect();

//クッキーに登録されているユーザーidを取得
$cookie_user_id = return_cookie_user_id($link);

if (isset($_POST['search_user'])) {
    $search_data = get_search_user_data($_POST['search_user'], $link);
}

//ユーザー情報取得
$user_data = get_user_data($cookie_user_id, $link);

//DBとのコネクション切断
close_db_connect($link);

// viewファイル読み込み
include_once '../../include/View/friend_search.php';