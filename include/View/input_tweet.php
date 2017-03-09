<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>マイプロフィール</title>
<link rel="stylesheet" href="../CSS/twitter.css">
</head>

<body class="twitter_my_profile">

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
		<!--nav class="other_icons_wrap header_twitter_wrap">
			<a href="input_tweet.php">
				<img src="../img/twitter_img/tweet.png">
				<p class="nav_txt">ツイートする</p>
			</a>
		</nav-->
		<div class="clearfix"></div>
	</header>
	
	<!--色つきの背景-->
	<div class="header_color_box"></div>
	
	<!--tweet情報入力部分-->
	<article class="input_tweet_wrap">
	    <form action="add_tweet.php" method="post" enctype="multipart/form-data">
	        <input type="hidden" name="tweet_target" value="<?php echo $tweet_target_user_id; ?>">
	        <p>tweet本文</p>
	        <textarea name="tweet_comment" cols=40 rows=4></textarea>
	        <p>画像を添付する（画像は４つまで添付できます）</p>
	        <div><input type="file" name="image_1"></div>
	        <div><input type="file" name="image_2"></div>
	        <div><input type="file" name="image_3"></div>
	        <div><input type="file" name="image_4"></div>
	        <div><input type="submit" class="input_tweet_submit_btn" value="ツイート"></div>
	    </form>
	</article>
	<!--/tweet情報入力部分-->
	
</body>
</html>