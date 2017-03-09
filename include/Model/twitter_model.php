<?php


/**
* 特殊文字を空白を埋めてHTMLエンティティに変換する
* @param str $str 変換前文字
* @return str 変換後文字
*/
function entity_str($str_) {
	$str = htmlspecialchars($str_, ENT_QUOTES, HTML_CHARACTER_SET);
    return preg_replace("/( |　)/", "", $str);
}


/**
* 特殊文字を空白を埋めてHTMLエンティティに変換する(２次元配列の値)
* @param array $assoc array 変換前配列
* @return array 変換後配列
*/
function entity_assoc_array($assoc_array) {
    foreach ($assoc_array as $key => $value) {
        foreach ($value as $keys => $values) {
            //特殊文字をHTMLエンティティに変換
            $assoc_array[$key][$keys] = entity_str($values);
        }
    }
    return $assoc_array;
}


/**
* DBハンドルを取得
* return obj $link DBハンドル
*/
function get_db_connect() {
    //コネクション取得
    if (!$link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWD, DB_NAME)) {
        die('error: ' . mysqli_connect_error());
    }
    
    //文字コードセット
    mysqli_set_charset($link, DB_CHARACTER_SET);
    
    return $link;
}


/**
* DBとのコネクション切断
* @param obj $link DBハンドル
* @return bool     成功したらtrue
*/
function close_db_connect($link) {
    //接続を閉じる
    return mysqli_close($link);
}


/**
* クエリを実行しその結果を配列で取得する
* 
* @param obj $link DBハンドル
* @param str $sql SQL文
* @return array 結果配列データ
*/
function get_as_array($link, $sql) {
    //返却用配列
    $data = array();
    
    //クエリを実行する
    if ($result = mysqli_query($link, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            //１件ずつ取り出す
            while ($row = mysqli_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        //結果アセットを開放
        mysqli_free_result($result);
    }
    return $data;
}


/**
* insertを実行する
*
* @param obj $link DBハンドル
* @param str SQL文
* @return bool　成功したらtrue 失敗したらfalse
*/
function insert_db($link, $sql) {
 
    // クエリを実行する
    return mysqli_query($link, $sql) ;

}


/**
* サーバーへの画像の登録
* 
* @param obj $file_ 送られてきた画像データ
* @return bool 画像アップロード成功時ファイル名 失敗時false
*/
function add_image_to_server($file_) {
	
     //拡張子取得
    $extension = ".".pathinfo($file_['name'], PATHINFO_EXTENSION);
    //ファイル位置と名前を決定
    $date_ = date('c');
    $upload_file_name = sha1_file($file_['tmp_name']) . $date_;
    
     if (move_uploaded_file(
        $file_['tmp_name'],
        $path = sprintf('../img/user_img/%s%s',
                        $upload_file_name,
                        $extension
                        )
    )) {
	        return $path;
	   }
        return false;
}


/**
* パスワードのハッシュ化
* 
* @param str $pass_ 元々のパスワード
* @return str $hashed_pass ハッシュ化されたパスワード
*/
function change_to_hashed($pass_) {
    return crypt($pass_, CRYPT_BLOWFISH);
}


/**
* 都道府県情報を取得
* @param obj $link_ DBハンドラ
* @return array $place_data_ 都道府県情報
*/
function get_place_data($link_) {
    $sql = "SELECT place_id, place_txt FROM place_table";
    return get_as_array($link_, $sql);
}



/**
 * cookieにあるユーザー名を返す
 * 
 * @return String ユーザー名
 */
function return_cookie_user_name() {
     if (isset($_COOKIE['cookie_user_name'])){
         return $_COOKIE['cookie_user_name'];
    }else{
        return "";
    }
 }
 
 
 /**
 * cookieにあるパスワードを返す
 * 
 * @return String パスワード
 */
function return_cookie_password() {
     if (isset($_COOKIE['cookie_password'])){
         return $_COOKIE['cookie_password'];
    }else{
        return "";
    }
 }
 
 
/**
* cookieにあるユーザー名とパスワードが正しければユーザーidを返す
* 
* @param obj $link_ DBハンドラ
* @return String ユーザーid 失敗ならばnull;
*/
function return_cookie_user_id($link_) {
    $cookie_name    = return_cookie_user_name();
    $cookie_password = return_cookie_password();
    $sql = 'SELECT user_id FROM user_table WHERE name ="'.$cookie_name.'" AND cookie_password ="'.$cookie_password.'"';
    $user_data_ = get_as_array($link_, $sql);
    
    if (empty($user_data_)) return null;
    
    return $user_data_[0]['user_id'];
}


/**
* cookieにユーザー名とcookieパスワードを登録する
* 
* @param int $user_name_  ユーザー名
* @param obj $link_      DBハンドラ
* @return bool           成功したらtrue 失敗したらfalse
*/
 function set_cookie($user_name_, $link_) {
 	 $flag;
     $cookie_user_id = get_user_id($user_name_, $link_);
     $cookie_password = get_cookie_password($user_name_, $link_);
     //登録に必要な情報が入手できているかチェック
     if ((empty($cookie_user_id) || empty($cookie_password))) {
     	$flag = false;
     } else {
	     $flag = setcookie('cookie_user_name', $user_name_, time() + TIME_STANP);
	     if ($flag == true) $flag = setcookie('cookie_user_id', $cookie_user_id, time() + TIME_STANP);
	     if ($flag == true) $flag = setcookie('cookie_password', $cookie_password, time() + TIME_STANP);
     }
     return $flag;
 }


/**
* cookie_password取得
* 
* @param int $user_name_  ユーザー名
* @param obj $link_      DBハンドラ
* @return array $user_data_  入手したcookie_password 失敗したらnull
*/
function get_cookie_password ($user_name_, $link_) {
    $sql = "SElECT cookie_password FROM user_table WHERE name = '" . $user_name_ ."'";
    $user_data_ = get_as_array($link_, $sql);
    //echo $sql;
    return $user_data_[0]['cookie_password'];
}


/**
* user_idを取得
* 
* @param str $user_name    ユーザー名
* @param obj $link_ DBハンドラ
* @return str $user_id_ ユーザーID 失敗したらnull
*/
function get_user_id($user_name_, $link_) {
    $sql = "SELECT user_id FROM user_table WHERE name = '" . $user_name_ ."'";
    $user_data_ = get_as_array($link_, $sql);
    
    if (empty($user_data_)) return null;
    
    return $user_data_[0]['user_id'];
} 


/**
* ユーザー情報を入手
* 
* @param str $user_id_  ユーザーid
* @param obj $link_ DBハンドラ
* @return aray ユーザー情報 失敗したならばnull
*/
function get_user_data($user_id_, $link_) {
    $user_data_ = array();
    $sql = "SELECT user_id, name, mail_address, place_id, img_path, comment, state
    FROM user_table 
    WHERE user_id = ". $user_id_;
    $user_data_ = get_as_array($link_, $sql);
    
    if (empty($user_data_)) return null;
    
    return $user_data_[0];
}


/**
* 指定されたユーザーをすでにフォローしているか調べる
* 
* @param  str  $user_id_        ユーザー（自分）のID
* @param  str  $target_user_id_ ターゲット（調べる対象)のID
* @param  obj  $link_           DBハンドル
* @return bool                  すでにフォローしていればtrue、まだしていなければfalse
*/
function check_is_follow($user_id_, $target_user_id_, $link_) {
    $sql = "SELECT user_id FROM follow_table WHERE user_id = ".$user_id_." AND target_user_id = ".$target_user_id_;
    $return_data = get_as_array($link_, $sql);
    if (count($return_data) == 0) return false;
    return true;
}


/**
* ユーザーがフォローしてる人数を調べる
* 
* @param  str  $user_id_  ユーザー（自分）のID
* @param  obj  $link_     DBハンドル
* @return int             ユーザーがフォローしている人数 失敗したならば０
*/
function get_follow_count($user_id_, $link_) {
    $sql = "SELECT user_id FROM follow_table WHERE user_id = ".$user_id_;
    $return_data = get_as_array($link_, $sql);
    return count($return_data);
}


/**
* ユーザーが何人にフォローされているか調べる
* 
* @param  str  $user_id_  ユーザー（自分）のID
* @param  obj  $link_     DBハンドル
* @return int             ユーザーをフォローしている人数　失敗したならば０
*/
function get_follower_count($user_id_, $link_) {
    $sql = "SELECT user_id FROM follow_table WHERE target_user_id = ".$user_id_;
    $return_data = get_as_array($link_, $sql);
    return count($return_data);
}

/**
* 文字数が０文字より大きく、指定された数以下か調べる
* 
* @param str $target_str_ 調べたい文字列
* @param int $check_length_     指定する文字数
* @return bool            文字数が０文字より大きく、指定された文字数以下ならばtrue 異なるならばfalse
*/
function check_strlen($target_str_, $check_length_) {
    $flag_ = false;
    $str_length_ = mb_strlen($target_str_);
    if ( ($str_length_>0) && ($str_length_ <= (int)$check_length_) ) {
        $flag_ = true;
    }
    return $flag_;
}


/**
* 画像データのチェック
* 
* @param obj $file_ 送らてきた画像データ
* @return boolean 問題なければtrue
*/
function check_image($file_) {
    $flag = false;
    $extensions = array("png", "jpeg", "jpg");
    if ($file_["name"] !== "") {
        //画像サイズをチェック
        if ($file_['size'] > IMG_SIZE) {
            return false;
        }
        //拡張子が指定しているものなのか判定
        $file = htmlspecialchars($file_["name"]);
        $extension = pathinfo($file, PATHINFO_EXTENSION);
        return in_array($extension, $extensions);
    } else {
        return false;
    }
}


/**
* time_line情報の取得
* 
* @param  str   $user_id_    ターゲットとなるユーザーのid
* @param  str   $offset_id_     検索開始位置
* @param  obj   $link_       DBハンドル
* @return array              ツイートに関する情報
*/
function get_time_line_data($user_id_, $offset_id_, $link_) {
                            
    // tweetテーブルとretweetテーブルをUNIONさせている。
    //それぞれ副問い合わせで、time_line_tableの中のユーザーidが一致するものを、１００件取得している。
    $offset_limit = SELECT_LIMIT+$offset_id_;
    $sql_ = 'SELECT
            	tweet_id,
            	user_id,
            	comment,
            	date,
            	target_user_id,
            	img_1_path,
            	img_2_path,
            	img_3_path,
            	img_4_path,
            	NULL AS retweet_id,
            	NULL AS retweet_user_id,
            	NULL AS retweet_comment,
            	NULL AS retweet_date,
            	NULL AS retweet_img_path
            FROM tweet_table
            WHERE tweet_id IN (
                SELECT tweet_id FROM(
                	SELECT 
                		tweet_id
                	FROM time_line_table
                	WHERE time_line_table.user_id IN (
                		SELECT target_user_id
                		FROM follow_table
                		WHERE user_id = '.$user_id_.'
                	) OR time_line_table.user_id ='.$user_id_.'
                	ORDER BY date DESC
                	LIMIT '.$offset_id_.','.$offset_limit.'
                ) AS tweet
            ) 
            
            UNION
            
            SELECT
            	NULL AS tweet_id,
            	NULL AS user_id,
            	NULL AS comment,
            	NULL AS date,
            	NULL AS target_user_id,
            	NULL AS img_1_path,
            	NULL AS img_2_path,
            	NULL AS img_3_path,
            	NULL AS img_4_path,
            	retweet_id,
            	user_id AS retweet_user_id,
            	comment AS retweet_comment,
            	date AS retweet_date,
            	img_path AS retweet_img_path
            FROM retweet_table
            WHERE retweet_id IN (
                SELECT retweet_id FROM (
                	SELECT 
                		retweet_id
                	FROM time_line_table
                	WHERE time_line_table.user_id IN (
                		SELECT target_user_id
                		FROM follow_table
                		WHERE user_id ='.$user_id_.'
                	) OR time_line_table.user_id ='.$user_id_.'
                	ORDER BY date DESC
                	LIMIT '.$offset_id_.','.$offset_limit.'
                ) As retweet
            )
            ORDER BY date DESC
            LIMIT 0,'.SELECT_LIMIT;
        echo $sql_;    
    return get_as_array($link_, $sql_);
}


/**
* tweet情報を入手
* 
* @param  str   $user_id_ 対象となるユーザーのid
* @param  str   $offset_   検索を開始する位置
* @param  obj   $link_    DBハンドル
* @return array           tweet情報
*/
function get_tweet_data($user_id_, $offset_, $link_) {
    $offset_limit = SELECT_LIMIT+$offset_;
    $sql_ = 'SELECT tweet_id, user_id, comment, date, target_user_id, img_1_path, img_2_path, img_3_path, img_4_path 
            FROM tweet_table 
            WHERE user_id='.$user_id_.' ORDER BY date DESC LIMIT '.$offset_.','.$offset_limit;
            
    return get_as_array($link_, $sql_);
}


/**
* tweet数を数える
* 
* @param  str  $user_id_ 対象となるユーザーのid
* @param  obj  $link_    DBハンドル
* @return str            tweet数 失敗したらfalse
*/
function get_tweet_count($user_id_, $link_) {
    $sql_ = 'SELECT COUNT(*) AS count_data FROM tweet_table WHERE user_id='.$user_id_;
    $tweet_count_data = get_as_array($link_, $sql_);
    if (empty($tweet_count_data)) return false;
    return $tweet_count_data[0]['count_data'];
}


/**
* タイムライン用の配列を元にユーザーの情報を入手する
* 
* @param  array $tweet_data_        tweet情報
* @param  obj   $link_              DBハンドル
* @return array $return_users_data_ ユーザー情報  失敗したらfalse
*/
function get_user_data_for_time_line($tweet_data_, $link_) {
    if (count($tweet_data_) == 0) return false;
    $return_users_data_ = array();
    
    $users_id_ = array();
    //タイムラインに表示されるユーザー達のユーザーidを入手
    foreach ($tweet_data_ as $tweet_) {
        if (!empty($tweet_['user_id'])) {
            $users_id[] = $tweet_['user_id'];
        } else if (!empty($tweet_['retweet_user_id'])) {
            $users_id[] = $tweet_['retweet_user_id'];
        }
    }
    
    //ユーザー情報入手
    $sql_ = "SELECT user_id, name, img_path FROM user_table WHERE user_id IN (".implode(",",$users_id).")";
    $users_data_ = get_as_array($link_, $sql_);
    if (empty($users_data_)) return false;
    
    //上の情報を利用し、タイムラインの表示のために必要なユーザー名と画像パスを配列に格納
    foreach ($tweet_data_ as $tweet_) {
        //タイムライン１つ１つを調べユーザーidを入手
        if (!empty($tweet_['user_id'])) {
            $tweet_user_id_ = $tweet_['user_id'];
        } else if (!empty($tweet_['retweet_user_id'])) {
            $tweet_user_id_ = $tweet_['retweet_user_id'];
        }
        //ツイートしているユーザーの情報を記録
        foreach ($users_data_ as $user_data_) {
            if ($tweet_user_id_ == $user_data_['user_id']) {
                //ユーザー情報を配列に格納
                $return_users_data_[] = $user_data_;
                break;
            }
        }
    }
    
    return $return_users_data_;
}