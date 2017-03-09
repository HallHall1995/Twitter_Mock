<?php
/**
* フォロー情報登録
* 
* @param str $user_id_        自分のユーザーID
* @param str $target_user_id_ フォローする対象のユーザーID
* @param obj $link_           DBハンドル
* @return bool 				  成功したらtrue
*/
function add_follow_data($user_id_, $target_user_id_, $link_) {
    $sql = 'INSERT INTO follow_table(user_id, target_user_id) VALUES('.$user_id_.','. $target_user_id_.')';
    return insert_db($link_, $sql);
}


/**
* フォロー情報削除
* 
* @param str $user_id_        自分のユーザーID
* @param str $target_user_id_ フォローする対象のユーザーID
* @param obj $link_          DBハンドル
* @return bool				  成功したらtrue
*/
function remove_follow_data($user_id_, $target_user_id_, $link_) {
    $sql = "DELETE FROM follow_table WHERE user_id =".$user_id_. " AND target_user_id =".$target_user_id_;
    return mysqli_query($link_, $sql);
}