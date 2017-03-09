<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>リツイート入力</title>
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
	    <!--元のtweet情報表示-->
	    <table class="my_tweet_time_line_table">
	        <tr class="my_tweet_time_line">
                <th class="time_line_border">
                    <!--tweet文章-->
                    <div class="in_time_line_left_wrap">
                        <form name="user" method="POST" action="oher_profile.php">
                            <input type="hidden" name="target_id" value="<?php echo $tweet_user_data['user_id'] ?>">
                        </form>
                        <!--ユーザー情報-->
                        <a href="" onclick="document.target_id.submit();return false;">
                            <p>
                                【<?php echo $tweet_user_data['name'];?>】 
                                <img class="in_time_line_user_img" src="<?php echo $tweet_user_data['img_path'] ?>">
                            </p>
                        </a>
                        <p><?php echo $tweet_data['comment']; ?></p>
                        <p class="date_info_size"><?php echo $tweet_data['date']; ?></p>
                    </div>
                    
                    <!--tweet画像-->
                    <div class="in_time_line_right_wrap">
<?php
    //画像データのキー
    $tweet_images_str = array('img_1_path', 'img_2_path', 'img_3_path', 'img_4_path');
    foreach ($tweet_images_str as $tweet_image_str) {
        if (!empty($tweet_data[$tweet_image_str])) { //twwetの際に画像を登録してあれば
?>
                        <img class="tweet_img" src="<?php echo $tweet_data[$tweet_image_str] ?>">
<?php
        }
    }
?>
                    </div>
                </th>
            </tr>
	   </table>
	   
	   <!--retweet情報入力部分-->
	    <form action="add_retweet.php" method="post" enctype="multipart/form-data">
	        <input type="hidden" name="target_tweet_id" value="<?php echo $target_tweet_id; ?>">
	        <p>retweetに追加する文章</p>
	        <textarea name="retweet_comment" cols=40 rows=4></textarea>
	        <p>画像を添付する</p>
	        <div><input type="file" name="image"></div>
	        <div><input type="submit" class="input_tweet_submit_btn" value="リツイート"></div>
	    </form>
	</article>
	<!--/tweet情報入力部分-->
	
</body>
</html>