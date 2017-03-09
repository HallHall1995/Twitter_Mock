<?php
/**
* メールアドレスかユーザー名からユーザー情報取得(ユーザー名は入力された文字の前方一致)
* 
* @param str $search_info メールアドレスかユーザー名
* @param obj $link_ DBハンドル
* @return array $search_user_data 友達検索で検索できた友達のデータ
*/
function get_search_user_data($search_info, $link_) {
    $search_user_data_ = array();
    
    //ユーザー名かメールアドレスから検索
    $sql = "SELECT user_id, name, img_path, place_id, comment, state
            FROM user_table
            WHERE name LIKE '" . $search_info ."%' OR mail_address = '" . $search_info ."'";
            
    $search_user_data_ = get_as_array($link_, $sql);
    
    return $search_user_data_;
    
}