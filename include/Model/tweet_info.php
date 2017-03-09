<?php
/**
* ツイート情報の詳細を入手する
* 
* @param  str $tweet_id_ ツイートid 
* @param  obj $link_     DBハンドル
* @return array          ツイート情報 失敗したらfalse
*/
function get_tweet_info($tweet_id_, $link_) {
    $sql_ = 'SELECT tweet_id, user_id, comment, date, target_user_id, img_1_path, img_2_path, img_3_path, img_4_path
             FROM tweet_table
             WHERE tweet_id = '.$tweet_id_;
    $return_array = get_as_array($link_, $sql_);
    if (empty($return_array)) return false;
    return $return_array[0];
}


/**
* リツイート情報の詳細を入手する
* 
* @param str $retweet_id_ リツイートid
* @param obj $link_       DBハンドル
* @return array           ReTweet情報 失敗したらfalse
*/
function get_retweet_info($retweet_id_, $link_) {
    $sql_ = 'SELECT retweet_id, 
                    retweet_table.user_id,
                    retweet_table.tweet_id,
                    retweet_table.comment,
                    retweet_table.date,
                    img_path,
                    tweet_table.user_id AS tweet_user_id,
                    tweet_table.comment AS tweet_comment,
                    tweet_table.date AS tweet_date,
                    target_user_id,
                    img_1_path,
                    img_2_path,
                    img_3_path,
                    img_4_path 
            FROM retweet_table, tweet_table 
            WHERE retweet_table.tweet_id = tweet_table.tweet_id 
            AND   retweet_table.retweet_id = '.$retweet_id_;
    $return_array = get_as_array($link_, $sql_);
    if (empty($return_array)) return false;
    return $return_array[0];
}