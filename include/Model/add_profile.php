<?php
/**
* 画像データの登録
* 
* @param obj $file_ 　　　　　　 送られてきた画像データ
* @param str $cookie_user_id_ 登録する対象のユーザーID
* @param obj $link_           DBハンドル
* @return bool 成功したらtrue 失敗したらfalse
*/
function set_to_profile_image($file_, $cookie_user_id_, $link_) {
    //画像データの確認
    if (check_image($file_)) {
    	if (update_profile_image($file_, $cookie_user_id_, $link_)) {
    		return true;
    	} else {
    		return false;
    	} 
    }
} 


/**
* 画像の登録
* 
* @param obj $file_ 送られてきた画像データ
* @param str $user_id_ 登録する対象のユーザーID
* @param obj $link_ DBハンドル
* @return bool 画像アップロード成功時true 失敗時false
*/
function update_profile_image($file_, $user_id_, $link_) {
	//判定用のフラグ
	$flag_ = false;
	
     //拡張子取得
    $file_name = htmlspecialchars($file_["name"]);
    $extension = pathinfo($file_name, PATHINFO_EXTENSION);
    //ファイル位置と名前を決定
    $date_ = date('c');
    $upload_file_name = sha1_file($file_['tmp_name']) . $date_;
     //$rand = rand(1, strlen($upload_file_name));
    //$upload_file_name = wordwrap($upload_file_name,$rand,"/",true);
    
     if (move_uploaded_file(
        $file_['tmp_name'],
        $path = sprintf('../img/user_img/%s.%s',
                        $upload_file_name,
                        $extension
                        )
    )) {
	        $sql = "UPDATE user_table SET img_path = '".$path."' WHERE user_id='".$user_id_."'";
	        if (insert_db($link_, $sql)) $flag_ = true;
	   }
        
        return $flag_;
}


/**
* 住んでいる土地の登録
* 
* @param str $place_ 住んでいる県に応じた番号
* @param str $user_id_ ユーザーid
* @param obj $link_ DBハンドラ
* @return bool 成功したならtrue　失敗したならfalse
*/
function set_to_profile_place($place_id_, $user_id_, $link_) {
	//判定用のフラグ
	$flag_ = false;
    //送られてきた情報が数字であれば
    if(preg_match("/^[0-9]+$/",$place_id_)){
        $sql = "UPDATE user_table SET place_id = '".$place_id_."' WHERE user_id='".$user_id_."'";
        if (insert_db($link_, $sql)) $flag_ = true;
    }
    return $flag_;
}


/**
* 公開ステータス登録
* 
* @param str $state_ 登録するステータス
* @param str $user_id_ ユーザーid
* @param obj $link_ DBハンドラ
* @return bool 成功したらtrue 失敗したらfalse
*/
function set_to_user_state($state_, $user_id_, $link_) {
	//判定用のフラグ
	$flag_ = false;
	
    //送られてきた情報が数字であれば
    if(preg_match("/^[0-9]+$/",$state_)){
        $sql = "UPDATE user_table SET state = '".$state_."' WHERE user_id='".$user_id_."'";
        if (insert_db($link_, $sql)) $flag_ = true;
    }
    
    return $flag_;
}


/**
* プロフィール文章登録
* 
* @param str $user_comment_ 登録するプロフィール
* @param str $user_id_ ユーザーid
* @param obj $link_ DBハンドラ
* @return bool 成功したらtrue　失敗したらfalse
*/
function set_to_user_comment($user_comment_, $user_id_, $link_) {
	//判定用のフラグ
	$flag_ = false;
	
    //送られてきた情報が数字であれば
    $comment_ = htmlspecialchars($user_comment_);
    $sql = "UPDATE user_table SET comment = '".$comment_."' WHERE user_id='".$user_id_."'";
    if (insert_db($link_, $sql)) $flag_ = true;
    
   return $flag_;
}

