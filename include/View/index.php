<!doctype html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<title>twitter</title>
<link rel="stylesheet" href="../../htdocs/CSS/twitter.css">
</head>
<body class="twitter_index clearfix">
	<!--文字情報-->
	<div class="twitter_index_info">
    	<p class="welcome_to_twitter">Twitterへようこそ</p>
        <p class="this_twitter_is">このページはTwitterの課題として作られた物です</p>
    </div>
    <!--ログイン、登録-->
    <div class="twitter_index_right_wrap clearfix">
    		<!--ログイン-->
    		<div class="twitter_index_login">
            <form action="login.php" method="post">
            	<input type="text" name="name" placeholder="ユーザー名" class="user_name" value=''><br>
                <input type="password" name="pass" placeholder="パスワード" class="user_pass" value=''>
                <input type="submit" value="ログイン" class="submit_btn">
                <!--div class="check_box"><input type="checkbox" name="cookie_check" value="checked">次回からユーザ名の入力を省略</div-->
<?php
    if (isset($err_flag)) {
?>
				<p class="forget_pass">ユーザー名かパスワードか違います</p>
<?php
    }
?>
            </form>
         </div>
         <!--登録-->
         <div class="twitter_index_entry">
		 	<div class="Hello_Twitter">Twitterの世界へようこそ</div>
			<hr>
          	<form action="entry.php" method="post">
				<input type="text" name="real_name" placeholder="名前" class="entry_box" ><br>
				<input type="text" name="mail_address" placeholder="メールアドレス" class="entry_box" ><br>
				<input type="password" name="password" placeholder="パスワード" class="entry_box" ><br>
				<input type="text" name="name" placeholder="ユーザー名" class="entry_box" ><br>
				<input type="submit" value="新しくTwitterを始める" class="entry_button" name="entry">
            </form>
         </div>
    </div>
</body>
</html>
