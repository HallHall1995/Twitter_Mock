<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>友達検索</title>
<link rel="stylesheet" href="../CSS/twitter.css">
</head>

<body class="friend_search">
	<div class="friend_search_wrap">
        	<!--ヘッダー-->
    	<header class="twitter_header">
    	
    		<!--ホーム部分-->
    		<nav class="home_wrap">
    			<a href="profile.php">
    			<img src="../img/twitter_img/home.png">
    			<p class="nav_txt">ホーム</p>
    			</a>
    		</nav>
    		
    			<!--通知部分-->
    		<nav class="other_icons_wrap">
    			<a href="#">
    			<img src="../img/twitter_img/bell.png">
    			<p class="nav_txt">通知</p>
    			</a>
    		</nav>
    		
    			<!--メッセージ部分-->
    		<nav class="other_icons_wrap">
    			<a href="#">
    			<img src="../img/twitter_img/mail.png">
    			<p class="nav_txt">メッセージ</p>
    			</a>
    		</nav>
    		
    		<!--メインアイコン-->
    		<nav class="twitter_icon">
    			<a href="#">
    				<img src="../img/twitter_img/icon.png">
    			</a>
    		</nav>
    		
    		<!--検索部分-->
    		<nav class="search_wrap">
    			<form action="friend_search.php" method="post">
    				<input type="text" name="search_user" value="" placeholder="Twiterを検索" class="search_box">
    			</form>
    		</nav>
    		
    		<!--プロフィール画像-->
    		<nav class="other_icons_wrap header_profile_img">
    			<a href="profile.php">
    				<img src="<?php echo $user_data['img_path']; ?>">
    			</a>
    		</nav>
    		
    		<!--ツイートする-->
    		<nav class="other_icons_wrap header_twitter_wrap">
    			<a href="input_tweet_to_me.php">
    				<img src="../img/twitter_img/tweet.png">
    				<p class="nav_txt">ツイートする</p>
    			</a>
    		</nav>
    		<div class="clearfix"></div>
    	</header>
    	<!--ここまでヘッダー-->

        <div class="friend_table_wrap">
            <table class="friend_table">
<?php
    foreach($search_data as $search) {
?>
                <tr>
                    <td>
                        <form action="other_profile.php" method="post">
                    	<input type="hidden" name="target_id" value="<?php echo $search['user_id'] ?>">
                        <img src="<?php echo $search['img_path'] ?>" class="friend_img">
                        <p class="friend_name"><?php echo $search['name'] ?></p>
                        <input type="submit" value="詳しいプロフィールを見る" class="friend_id_submit_btn">
                        </form>
                    </td>
                </tr>
<?php
    }
?>
    	    </table>
        </div>

    </div><!--/class="friend_search_wrap"-->
</body>
</html>
