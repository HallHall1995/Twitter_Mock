<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>プロフィール</title>
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
		    <form name="Form1" method="post" action="input_tweet.php" >
                <input type=hidden name="tweet_target_user_id" value="<?php echo $target_user_id; ?>">
                <a href="javascript:Form1.submit()">
                    <img src="../img/twitter_img/tweet.png">
			        <p class="nav_txt">ツイート</p>
                </a>
            </form>
		</nav>
		<div class="clearfix"></div>
	</header>
	<!--ここまでヘッダー-->
	
	
	<!--色つきの背景-->
	<div class="header_color_box"></div>
	
	<!--プロフィール情報表示部分-->
	<div class="twitter_profile_info_line">	
		<!--プロフィール画像-->
		<img src="<?php echo $target_user_data['img_path']; ?>" class="twitter_profile_img">	
		<!--ツイート数-->
		<a href="#">
			<ruby class="profile_tweet_num_wrap">
				<rb><?php echo $tweet_count; ?></rb>
				<rp>（</rp>
				<rt>ツイート</rt>
				<rp>）</rp>
			</ruby>
		</a>	
		<!--フォロー-->
		<a href="#">
			<ruby class="profile_other_wrap">
				<rb><?php echo $follow_count; ?></rb>
				<rp>（</rp>
				<rt>フォロー</rt>
				<rp>）</rp>
			</ruby>
		</a>	
		<!--フォロワー-->
		<a href="#">
			<ruby class="profile_other_wrap">
				<rb><?php echo $follower_count; ?></rb>
				<rp>（</rp>
				<rt>フォロワー</rt>
				<rp>）</rp>
			</ruby>
		</a>
		<!--このユーザーをフォローする(フォローを外す)-->
		<form action="add_follow.php" method="post">
		    <input type="hidden" name="target_id" value="<?php echo $target_user_id ?>">
<?php 
    if ($is_follow) {
?>
            <input type="submit" class="other_profile_btn" value="このユーザーのフォローを外す" name="remove_follow">
<?php
    } else {
?>
		    <input type="submit" class="other_profile_btn" value="このユーザーをフォローする" name="add_follow">
<?php
    }
?>
		</form>
		<!--プロフィール編集-->
		<!--button class="edit_profile_btn"><a href="profile_edit.php">プロフィール編集</a></button-->
	</div><!--/プロフィール情報表示部分 class="twitter_profile_info_line"-->
	
	<article class="my_profile_article clearfix">
		<!--左のラッパー-->
		<section class="my_profile_left_wraper">
			<p class="my_user_name"><?php echo $target_user_data['name'];  ?></p>
			<p class="my_profile_comment"><?php echo $target_user_data['comment']; ?></p>
		</section>
		
		<!--中央のラッパー-->
		<section class="my_profile_center_wraper">
			<table class="my_tweet_time_line_table">
				<tr class="my_tweet_time_line_roof">
					<th>ツイート</th>
				</tr>
				<!--ここからはユーザーによって変わる-->
				<?php
    foreach ($tweet_data as $tweet){
?>
                <tr class="my_tweet_time_line">
                    <th class="time_line_border">
                        <!--tweet文章-->
                        <div class="in_time_line_left_wrap">
                            <p><?php echo $tweet['comment']?></p>
                            <p class="date_info_size"><?php echo $tweet['date']?></p>
                        </div>
                        
                        <!--tweet画像-->
                        <div class="in_time_line_right_wrap">
<?php
        //画像データのキー
        $tweet_images_str = array('img_1_path', 'img_2_path', 'img_3_path', 'img_4_path');
        foreach ($tweet_images_str as $tweet_image_str) {
            if (!empty($tweet[$tweet_image_str])) { //twwetの際に画像を登録してあれば
?>
                            <img class="tweet_img" src="<?php echo $tweet[$tweet_image_str] ?>">
<?php
            }
        }
?>
                        </div>
                    </th>
                </tr>
<?php
    }
?>
<tr class="my_tweet_time_line">
                    <th class="time_line_border">
                        <form method="post">
                            <input type="hidden" name="target_id" value="<?php echo $target_user_id; ?>">
<?php
    if ($offset_time_line_id >= 1) {
        $before_offset_id = (int)$offset_time_line_id - SELECT_LIMIT;
?>
                            <input type="submit" value="新しいタイムラインを見る" name="before_time_btn">
                            <input type="hidden" name="before_time_line_id" value="<?php echo $before_offset_id; ?>">
<?php
    }
    if ($tweet_count >= SELECT_LIMIT) {
        $next_offset_id = (int)$offset_time_line_id + SELECT_LIMIT;
?>
                            <input type="submit" value="過去のタイムラインを見る" name="next_time_btn">
                            <input type="hidden" name="next_time_line_id" value="<?php echo $next_offset_id; ?>">
<?php
    }
?>
                        </form>
                    </th>
                </tr>
			</table>
		</section>
		
		<!--右のラッパー-->
		<!--section>余裕があれば実装（おすすめユーザーなど）</section-->
	</article>
	
</body>
</html>