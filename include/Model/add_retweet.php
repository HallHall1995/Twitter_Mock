<?php
/**
* 届いてきたretweet情報が正しいか調べる
* 
* @param  array $retweet_data_ ReTweetのデータ
* @return bool                 正しければtrue間違っていればfalse
*/
function check_retweet_data($retweet_data_) {
    $flag = true;
    //tweet_idのチェック
    if (empty($retweet_data_['target_tweet_id'])) {
        $flag = false;
    }
    //コメントのチェック
    if (!empty($retweet_data_['retweet_comment'])) {
        if(!check_strlen($retweet_data_['retweet_comment'], MAX_TWEET_COMMENT_STRLEN)) {
            $flag = false;
        }
    }
    //画像のチェック
    if (!empty($retweet_data_['image']['name'])) {
        if (!check_image($retweet_data_['image'])) {
            $flag = false;
        }  
    }
    return $flag;
}


/**
* ReTweet情報を登録
* 
* @param  array $retweet_data_  リツート情報
* @param  str   $user_id_       リツートをしたユーザーid
* @param  obj   $link_          DBハンドル
* @return bool                  成功したらtrue、失敗したらfalse
*/
function add_retweet($retweet_data_, $user_id_, $link_) {
     // 更新系の処理を行う前にトランザクション開始(オートコミットをオフ）
    mysqli_autocommit($link_, false);
    
    //リツイートテーブルへのReTweet情報登録
    $flag = add_retweet_to_DB($retweet_data_, $user_id_, $link_);
    
    //登録したReTweetのidを入手
    if ($flag) $retweet_id_ = mysqli_insert_id($link_);
    
    //タイムラインテーブルへReTweet情報登録
    if ($flag) {
        $flag = add_retweet_to_time_line($retweet_id_, $user_id_, $link_);
    }
    
    //全ての処理が上手くいけば処理確定
    if ($flag) {
        // 処理確定
        mysqli_commit($link_);
    } else {
        // 処理取消
        mysqli_rollback($link_);
    }
    
    return $flag;
}


/**
* ReTweet情報をリツイートテーブルに登録
* 
* @param  array $retweet_data_  リツート情報
* @param  str   $user_id_       リツートをしたユーザーid
* @param  obj   $link_          DBハンドル
* @return bool                  成功したらtrue、失敗したらfalse
*/
function add_retweet_to_DB($retweet_data_, $user_id_, $link_) {
    $flag = true;
    $date_ = date('Y-m-d H:i:s');
    
    //サーバーへの画像のアップロード
    if (!empty($retweet_data_['image']['name'])) {
        $path_ = add_image_to_server($retweet_data_['image']);
        if ($path_ == false) $flag = false;
    } else {
        $path_ = '';
    }
    
    $sql_ = 'INSERT INTO retweet_table (user_id, tweet_id, comment, date, img_path) 
            VALUES ('.$user_id_.','.$retweet_data_['target_tweet_id'].',"'.$retweet_data_['retweet_comment'].'","'.$date_.'","'.$path_.'")';
            echo $sql_;
    if ($flag) {
        $flag = insert_db($link_, $sql_);
    }
    
    return $flag;
}


/**
* ReTweet情報をタイムラインテーブルに登録
* 
* @param str $retweet_id_  リツイートid
* @param str $user_id_     ユーザーid
* @param obj $link_        DBハンドル
* @return bool              成功したらtrue失敗したらfalse
*/
function add_retweet_to_time_line($retweet_id_, $user_id_, $link_) {
    $date_ = date('Y-m-d H:i:s');
    $sql_ = 'INSERT INTO time_line_table (retweet_id, user_id, date)
            VALUES('.$retweet_id_.','.$user_id_.',"'.$date_.'")';
    return insert_db($link_, $sql_);
}