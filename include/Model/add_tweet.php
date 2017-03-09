<?php
/**
* tweet情報に問題がなければtweet情報を記録
* 
* @param str   $user_id_        ユーザーid
* @param str   $target_usre_id_ tweetする対象のユーザーid 
* @param array $tweet_data_     tweetのデータ
* @param obj   $link_           DBハンドラ
* @return bool 　　　　　       成功したらtrue失敗したらfalse
*/
function tweet_process($user_id_, $target_user_id_, $tweet_data_, $link_){
    $flag_ = false;
    if (check_tweet_data($tweet_data_)) {
        $flag_ = tweet($tweet_data_, $user_id_, $target_user_id_, $link_);
    }
    return $flag_;
}


/**
* tweet情報に間違いがないか調べる
* 
* @param array $tweet_data_ tweetのデータ
* @return bool １つ以上データが届いており、届いたデータが全て正しい場合はtrue そうでないならfalse
*/
function check_tweet_data($tweet_data_) {
    //チェック用の変数
    $flag_;
    
    //画像データのキー
    $post_images_str = array('image_1', 'image_2', 'image_3', 'image_4');
    
    //コメントのチェック
    if (isset($tweet_data_['comment'])) {
        if (check_strlen($tweet_data_['comment'], MAX_TWEET_COMMENT_STRLEN)){//文字数チェック
            $flag_ = true;
        } else {
            $flag_ = false;
        }
    }
    
    //画像データのチェック
    foreach ($post_images_str as $post_image_str) {
        if (!empty($tweet_data_[$post_image_str]['name'])) {
            if ( (check_image($tweet_data_[$post_image_str])) && ($flag_ != false) ){
                $flag_ = true;
            } else {
                $flag_ = false;
            }
        }
    }
    //何もデータが届いていなければfalse
    if ($flag_ == null) $flag_ = false;
    
    return $flag_;
}


/**
* tweet処理
* 
* @param array $tweet_data_ ツイート情報
* @param str   $user_id_    ユーザーid
* @param str   $target_user_id_  ツイートする対象のid
* @param obj   $link_       DBハンドル
*/
function tweet($tweet_data_, $user_id_, $target_user_id_, $link_) {
    $date = date('Y-m-d H:i:s');
    $imgs_path = array();
    $comment_text = "";
    $tweet_id_;
    $err_flag = true;
    
    //画像登録と画像パス取得
    $imgs_path = add_tweet_img($tweet_data_);
    //画像登録に失敗したら
    if ($imgs_path == false) {$err_flag = false; echo "sippai"; }
    
    //tweet文取得
    if (isset($tweet_data_['comment'])) {
        $comment_text = $tweet_data_['comment'];
    }
    
    // トランザクション開始
    mysqli_autocommit($link_, false);
    
    //tweet情報登録
    $sql_ = 'INSERT INTO tweet_table(user_id, comment, date, target_user_id, img_1_path, img_2_path, img_3_path, img_4_path) 
             VALUES('
             .$user_id_.',"'.get_not_hash_comment($comment_text).'","'.$date.'",'.$target_user_id_.',"'
             .$imgs_path['image_1'].'","'.$imgs_path['image_2'].'","'.$imgs_path['image_3'].'","'.$imgs_path['image_4']
             .'")';
    
    $err_flag = insert_db($link_, $sql_);
    
    
    //tweet_id取得
    $tweet_id_ = mysqli_insert_id($link_);
    
    if($tweet_id_ != 0) { //tweet_idの取得に成功すれば
        //time_line_tableに登録
        
    
        //ハッシュ情報登録
       if (!add_hash_data($comment_text, $tweet_id_, $link_)) { //ハッシュ情報の登録に失敗したら
            $err_flag = false;
        }
    } else {
        $err_flag = false;
    }
    
    //time_line_tableに登録
    if ($err_flag) {
        $sql_ = 'INSERT INTO time_line_table(tweet_id, user_id, date) 
                VALUES('
                .$tweet_id_.','.$user_id_.',"'.$date.'")';
        $err_flag = insert_db($link_, $sql_);
    }
    
    // トランザクション成否判定
    if ($err_flag) {
    	// 処理確定
    	mysqli_commit($link_);
    } else {
    	// 処理取消
    	mysqli_rollback($link_);
    }
    
    return $err_flag;
    
}



/**
* ハッシュタグを除いたツイートの本文を取得
* 
* @param  str $comment_        ツイート内容（ハッシュタグあり）
* @return str $return_comment_ ツイート本文
*/
function get_not_hash_comment($comment_) {
    $return_comment_ = $comment_;
    $return_comment_ = mb_convert_encoding($return_comment_, "UTF-8", "auto");
    //$pattern = "/[#＃][Ａ-Ｚａ-ｚA-Za-z一-鿆0-9０-９ぁ-ヶｦ-ﾟー]+/"; ＃が入るとカタカナの「ハ」も認識してしまった。
    $pattern = "/[#][Ａ-Ｚａ-ｚA-Za-z一-鿆0-9０-９ぁ-ヶｦ-ﾟー]+/";
    $hash_count = preg_match_all($pattern, $return_comment_, $hashes);
    
    if ($hash_count > 0) {
        foreach($hashes[0] as $hash) {
            //echo "hash=".$hash;
            $return_comment_ = str_replace($hash, "", $return_comment_);
        }
    }
    return $return_comment_;
}


/**
* ハッシュタグ登録
* 
* @param  str   $comment_         ツイート内容
* @param  str   $hash_manage_id_  ハッシュマネージid
* @param  obj   $link_            DBハンドル
* @return bool                    登録に失敗したらfalse それ以外はtrue
*/
function add_hash_data($comment_, $tweet_id_, $link_) {
    //ハッシュタグの文章を取得
    $hashes;
    //$pattern = "/[#＃][Ａ-Ｚａ-ｚA-Za-z一-鿆0-9０-９ぁ-ヶｦ-ﾟー]+/"; ＃が入るとカタカナの「ハ」も認識してしまった。
    $pattern = "/[#][Ａ-Ｚａ-ｚA-Za-z一-鿆0-9０-９ぁ-ヶｦ-ﾟー]+/";
    $hash_count = preg_match_all($pattern, $comment_, $hashes);
    
    if ($hash_count > 0) {
        foreach($hashes[0] as $hash) {
            //ハッシュテーブルからハッシュidを取得
            $hash_id_ = get_hash_id($hash, $link_);
            
            //ハッシュタグがテーブルに存在しなければ
            if ($hash_id_ == null) {
                echo "hash!";
                //新しくハッシュタグを登録
                $hash_id_ = make_new_hash($hash, $link_);
            }
            
            $sql_ = 'INSERT INTO hash_manage_table(tweet_id, hash_id) VALUES('.$tweet_id_.','. $hash_id_.')';
            
            //登録に失敗したらfalseを返す
            if(!insert_db($link_, $sql_)) return false;
        }
    }
    return true;
}


/**
* ハッシュタグからhash_idを入手
* 
* @param  str  $comment_   ハッシュタグの内容
* @param  obj  $link_      DBハンドル
* @return bool             ハッシュタグが存在していればid、存在しなければnull
*/
function get_hash_id($comment_, $link_) {
    $hash_data_ = array();
    $sql_ = 'SELECT hash_id FROM hash_table WHERE comment = "' . $comment_ . '"';
    $hash_data_ = get_as_array($link_, $sql_);
    if (empty($hash_data_)) return null;
    return $hash_data_[0]['hash_id'];
}


/**
* ハッシュタグを新しく登録
* 
* @param  str $comment_  追加するハッシュタグの文章
* @param  obj $link_     DBハンドル
* @return int            追加したハッシュタグのhash_id 失敗した場合はfalse
*/
function make_new_hash($comment_, $link_) {
    $hash_id_;
    $sql_ = 'INSERT INTO hash_table(comment) VALUES("'.$comment_.'")';
    if(insert_db($link_, $sql_)){
        $hash_id_ = mysqli_insert_id($link_);
    } else {
        $hash_id_ = false;
    }
    return $hash_id_;
}


/**
* tweetされた画像情報を登録
* 
* @param  array $tweet_data_ tweet情報
* @return array $imgs_path_  登録した画像の画像パス 失敗したらfalse;
*/
function add_tweet_img($tweet_data_) {
	$imgs_path_ = array();
    //画像データのキー
    $post_images_str = array('image_1', 'image_2', 'image_3', 'image_4');
    //画像のキーを回しながら画像データの登録とデータベースへの登録
    foreach ($post_images_str as $post_image_str) {
        //画像データが存在していたら
        if (!empty($tweet_data_[$post_image_str]['name']) ){
        	//サーバーへの画像データ登録
        	$imgs_path_[$post_image_str] = add_image_to_server($tweet_data_[$post_image_str]);
        	//画像登録に失敗したら
        	if ($imgs_path_[$post_image_str] == false) return false;
        	//DBへの画像情報登録
        	//if ($img_path != false) $flag_ = add_image_to_DB($tweet_id_, $img_path, $user_id_, $link_);
        } else {
            $imgs_path_[$post_image_str] = "";
        }
    }
    return $imgs_path_;
}



/**
* データベースへの画像情報の登録
* 
* @param  str $tweet_id_ ツイートid
* @param  str $img_path_ 画像名
* @param  str $user_id_  ユーザーid
* @param  obj $link_     DBハンドル
* @return bool           成功したらtrue 失敗したらfalse
*/
function add_image_to_DB($tweet_id_, $img_path_, $user_id_, $link_) {
	$flag = true;
	$sql_ = 'INSERT INTO tweet_img_table(tweet_id, img_path, user_id) 
			 VALUES('.$tweet_id_.',"'.$img_path_.'",'.$user_id_.')';
	//insert文が失敗したら
	if(!insert_db($link_, $sql_)){
		$flag = false;
	} 
	
	return $flag;
}