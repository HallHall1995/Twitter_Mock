<?php
/**
* 入力されたデータからクッキー情報を登録
* 
* @param  str   $name_  ユーザー名
* @param  str   $pass_  パスワード
* @param  obj   $link_  DBハンドル
* @return array $cookie_user_data_  成功すればtrue　失敗すればfalse
*/
function get_cookie_user_data($name_, $pass_, $link_) {
    $flag = true;
    $cookie_user_data_;
    $login_password = change_to_hashed($pass_.ADD_PASSWORD_STR);
    $sql_ = 'SELECT user_id, name, cookie_password FROM user_table WHERE name="'.$name_.'"AND password="'.$login_password.'"';
    $cookie_user_data_ = get_as_array($link_, $sql_);
    if (empty($cookie_user_data_)) $flag = false;
    
    if ($flag == true) $flag = setcookie('cookie_user_name', $cookie_user_data_[0]['name'], time() + TIME_STANP);
	if ($flag == true) $flag = setcookie('cookie_user_id', $cookie_user_data_[0]['user_id'], time() + TIME_STANP);
	if ($flag == true) $flag = setcookie('cookie_password', $cookie_user_data_[0]['cookie_password'], time() + TIME_STANP);
	
	return $flag;
}