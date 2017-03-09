<?php

/**
* ユーザー名が他のユーザー名と同じでないか調べる
* 
* @param  str   $user_name_  ユーザー名
* @param  obj   $link 		 DBハンドラ
* @return bool               他にユーザー名が同じ人がいなければ（登録できる）true
*/
function check_original_name($user_name_, $link_) {
	$sql = 'SELECT COUNT(*) as cnt FROM user_table WHERE name = "' . $user_name_ . '"';
	if ($result = mysqli_query($link_, $sql)) {
	    $row = mysqli_fetch_assoc($result);
	    $count = $row['cnt'];
		//他に同じ名前のユーザーがいなければ
		if ($count == 0) {
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}



/**
* メールアドレスが正しい形式か調べる
* 
* @param str   $user_address_ ユーザーのアドレス
* @return bool                正しい形式ならばtrue
*/
function check_address($user_address_) {
	$err_msg_ = array();
	$mail_preg_check = "/^(\d|[a-zA-Z]|_)+(\.(\d|[a-zA-Z]|_)+)*@(\d|[a-z])+(\.(\d|[a-z])+)*$/";
	if (preg_match($mail_preg_check, $user_address_)) {
		return true;
	} else {
		return false;
	}
}


/**
* 送られてきたデータの形式が正しいか調べる
*
* @param  array $user_data_        送られてきたユーザー情報
* @param  obj   $link              DBハンドラ
* @return array $err_msg_  　　　　　エラーメッセージ
*/
function entry_data_check($user_data_, $link_) {
	$err_msg_ = array();
	//全てのデータが存在しているか調べる
	$err_msg_ = check_isset_datas($user_data_);
	if (!empty($err_msg_)) return $err_msg_;
	
	//他に同じユーザー名がないかを調べる
	if (!check_original_name($user_data_['name'], $link_)) {
		$err_msg_[] = 'そのユーザー名はすでに使われています。';
	}
	//メールアドレスが正しい物かを調べる
	if (!check_address($user_data_['mail_address'])){
		$err_msg_[] = 'メールアドレスか電話番号が正しくありません。';
	}
	//文字数が指定以下かを調べる
	if (!entry_check_strlens($user_data_)){
		$err_msg_[] = '入力された文字数が大きすぎます。';
	}
	
	return $err_msg_;
}


/**
* 全てのデータが揃っているかどうかを調べる
* 
* @param  array  $user_data_   送られてきたデータ
* @return bool     //全てのデータが揃っていたらtrue そうでないならばfalse
*/
function check_isset_datas($user_data_) {
    $err_msg_ = array();
    //ユーザー名を調べる
    if (isset($user_data_['name'])) {
        if (strlen($user_data_['name']) <= 0) {
             $err_msg_[] = 'ユーザー名が入力されていません';
        }
    }
    //メールアドレスを調べる   
    if (isset($user_data_['mail_address'])) {
        if (strlen($user_data_['mail_address']) <= 0) {
            $err_msg_[] = 'メールアドレスが入力されていません';
        }
    }
    //パスワードを調べる
    if (isset($user_data_['password'])) {
        if (strlen($user_data_['password']) <= 0) {
            $err_msg_[] = 'パスワードが入力されていません';
        }
    }
    //実名を調べる
    if (isset($user_data_['real_name'])) {
        if (strlen($user_data_['real_name']) <= 0) {
            $err_msg_[] = '名前が入力されていません';
        }
    }
    return $err_msg_;
}


/**
* 入力されたデータが指定された文字数以下かを調べる（複数）
* 
* @param array str $strs_ 調べたい文字列の配列
* @param array int $size_ それぞれの文字列の最高文字数
* @return bool 全ての文字列の文字数が指定以下ならばtrue
*/
function entry_check_strlens($strs_) {
    //各データの最高文字数
    $data_upper_limit = array(
    		"name" 	        => MAX_NAME_STRLEN,
    		"mail_address"=> MAX_MAIL_ADDRESS_STRLEN,
    		"password"      => MAX_PASSWORD_STRLEN,
    		"real_name"      => MAX_REAL_NAME_STRLEN
    );
    foreach ($strs_ as $key_ => $data_) {
        //指定されたサイズより入力された文字列が大きければ
		if (strlen($strs_[$key_]) > $data_upper_limit[$key_]) {
			return false;
		}
    }
    return true;
}


/**
* Twitterへの新規登録
*
* @param obj $link DBハンドル
* @return bool　　　成功したらtrue
*/
function entry_to_twitter($user_data_, $link_) {
	$date = date("c");
	
	//cookiepassword生成
	$user_cookie_pass = uniqid(($user_data_['name'].rand()));
	//passwordハッシュ化
	$user_data_['password'] = change_to_hashed($user_data_['password'].ADD_PASSWORD_STR);

	// 画像パス
	$img_path = '../img/twitter_img/file.jpg';

	$sql = "INSERT INTO user_table(
        name, mail_address, password, real_name, img_path, cookie_password)
        VALUES('"
			.$user_data_['name']."','".$user_data_['mail_address']."','".$user_data_['password']."','".$user_data_['real_name']."','".$img_path."','".$user_cookie_pass.
			"')";
			
	//実行
	return insert_db($link_, $sql);
}