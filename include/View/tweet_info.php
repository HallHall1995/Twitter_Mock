<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>ツイート情報</title>
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
		<nav class="other_icons_wrap header_twitter_wrap">
			<a href="input_tweet.php">
				<img src="../img/twitter_img/tweet.png">
				<p class="nav_txt">ツイートする</p>
			</a>
		</nav>
		<div class="clearfix"></div>
	</header>
	
	<!--色つきの背景-->
	<div class="header_color_box"></div>
	
	<!--tweet(retweet)情報-->
	<article class="tweet_info_wrap">
	    <table class="my_tweet_time_line_table">
<?php
    if (!empty($tweet_data)){
?>
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
                    <div class="clearfix"></div>
                    <form action="#" method="post">
                        <input type="hidden" name="tweet_id" value="<?php echo $tweet_data['tweet_id']; ?>">
                        <input type="submit" value="このツイートにコメントする">
                    </form>
                    <form action="input_retweet.php" method="post">
                        <input type="hidden" name="tweet_id" value="<?php echo $tweet_data['tweet_id']; ?>">
                        <input type="submit" value="このツイートをリツイートする">
                    </form>
                </th>
            </tr>
                <!--この後にコメントが入ってくる-->
<?php
    } //if (!empty($tweet_data))の終わり
    //retweet情報の表示
    else if (!empty($retweet_data)) {
?>
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
                        <p><?php echo $retweet_data['tweet_comment']; ?></p>
                        <p class="date_info_size"><?php echo $retweet_data['tweet_date']; ?></p>
                    </div>
                    
                    <!--tweet画像-->
                    <div class="in_time_line_right_wrap">
<?php
        //画像データのキー
        $tweet_images_str = array('img_1_path', 'img_2_path', 'img_3_path', 'img_4_path');
        foreach ($tweet_images_str as $tweet_image_str) {
            if (!empty($retweet_data[$tweet_image_str])) { //twwetの際に画像を登録してあれば
?>
                        <img class="tweet_img" src="<?php echo $retweet_data[$tweet_image_str] ?>">
<?php
            }
        }
?>
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                    <p>↓リツイート</p>
                    <div class="in_time_line_left_wrap">
                        <form name="user" method="POST" action="oher_profile.php">
                            <input type="hidden" name="target_id" value="<?php echo $retweet_user_data['user_id'] ?>">
                        </form>
                        <!--ユーザー情報-->
                        <a href="" onclick="document.target_id.submit();return false;">
                            <p>
                                【<?php echo $retweet_user_data['name'];?>】 
                                <img class="in_time_line_user_img" src="<?php echo $retweet_user_data['img_path'] ?>">
                            </p>
                        </a>
                        <p><?php echo $retweet_data['comment']; ?></p>
                        <p class="date_info_size"><?php echo $retweet_data['date']; ?></p>
                    </div>
                    
                    <!--tweet画像-->
                    <div class="in_time_line_right_wrap">
<?php
        if (!empty($retweet_data['img_path'])) { 
?>
                        <img class="tweet_img" src="<?php echo $retweet_data['img_path'] ?>">
<?php
        }
?>
                    </div>
                </th>
            </tr>
<?php
    } //if (!empty($retweet_data))の終わり
?>
        </tabel>
	</article>
	<!--/tweet(retweet)情報部分-->
	
</body>
</html>