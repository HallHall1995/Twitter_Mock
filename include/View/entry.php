<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>新規登録</title>
<link rel="stylesheet" href="..//CSS/twitter.css">
</head>

<body class="twitter_entry">
    
<?php   if (empty($err_msg)) {  ?>

	<!--最初のあいさつ-->
	<div class="greeting_wrap clearfix">
		<h1 class="how_do_you_do">はじめまして<?php echo $user_data['name'] ?>さん</h1>
		<div class="greeting">
			<h2>Twitterへ登録ありがとうございます。</h2>
			<h2>Twitterをより楽しんでいただくために、</h2>
			<h2>プロフィールを変更しましょう</h2>
		</div>
	</div><!--//ここまで最初のあいさつ-->
	
	<form action="add_profile.php" method="post" enctype="multipart/form-data">
		<!--user_id-->
		<input type="hidden" value="" name="user_id">
		
		<!--画像登録-->
		<div class="entry_img">
			<p>自分のプロフィール画像を選んで下さい</p>
			<input type="file" name="user_img">
			<p>出身地を入力して下さい</p>
			<select name="user_place">
<?php
    foreach($places_data as $place_data) {
?>
                <option value="<?php echo $place_data['place_id'] ?>"><?php echo $place_data['place_txt']; ?></option>
<?php
    }			 
?>
			</select>
			<p>公開ステータスを選択して下さい</p>
			<select name="user_state">
                <option value="1">全員にツイートを公開する</option>
                <option value="0">自分のフォローしている人のみにツイートを公開する</option>
            </select>
		</div>
		
		<!--プロフィール登録-->
		<div class="entry_info">
			<p class="entry_info_p">自分のプロフィール文章を入れて下さい（１５０文字以内）</p>
			<textarea name = "user_info" cols="30" rows="5" class="entry_info_text"></textarea>
		</div><!--ここまでプロフィール登録-->
		
		<div class="clearfix"></div>
		
		<!--送信、スキップ-->
		<div class="submit_area clearfix">
			<input type="submit" value="登録" class="submit_btn" name="profile_edit">
		</div>
	</form>
		
<?php   } else {    ?>  
    <div class="greeting_wrap">
<?php       foreach ($err_msg as $err) {    ?>
        <h2><?php echo $err ?></h2>
<?php       }   ?>
        <a href="index.php">トップページへ戻る</a>
    </div>
<?php   }   ?>
</body>
</html>
