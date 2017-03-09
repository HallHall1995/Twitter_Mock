<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>プロフィール変更</title>
<link rel="stylesheet" href="../CSS/twitter.css">
</head>

<body class="profile_edit">
	<div class="profile_edit_wrap">
        <form action="add_profile.php" method="post" enctype="multipart/form-data">
            <div class="profile_edit_left_wrap">
                <p>現在の画像</p>
                <img src="<?php echo $user_data['img_path']; ?>" class="profile_img">
                <p>自分のプロフィール画像を選んで下さい</p>
                <input type="file" name="user_img">
                <hr class="profile_hr">
                
                <p>プロフィール</p>
                <input type="text" value="<?php echo $user_data['comment']; ?>" name="user_info">
                <hr class="profile_hr">
                
                <p>公開ステータス</p>
                <select name="user_state">
                    <option value="1" <?php if ($user_data['state'] == 1) echo 'selected'; ?>>全員にツイートを公開する</option>
                    <option value="0" <?php if ($user_data['state'] == 0) echo 'selected'; ?>>自分のフォローしている人のみにツイートを公開する</option>
                </select>
                <hr class="profile_hr">
                  
                
                <p>出身地を入力して下さい</p>
                <select name="user_place">
<?php
    foreach($places_data as $place_data) {
        if ($place_data['place_id'] == $user_data['place_id']) {
?>
                    <option selected value="<?php echo $place_data['place_id'] ?>"><?php echo $place_data['place_txt']; ?></option>
<?php
        } else {
?>
                	<option value="<?php echo $place_data['place_id'] ?>"><?php echo $place_data['place_txt']; ?></option>
<?php
        }
    }			 
?>
                </select>
                <hr class="profile_hr">
            </div><!--/profile_edit_left_wrap-->
            
            <div class="profile_edit_right_wrap">
            	<input type="submit" class="profile_edit_button" value="変更"><br>
                <a href="profile_php"><button class="profile_edit_button profile_edit_cancel">キャンセル</button></a>
            </div>
        </form>
    </div><!--/class="profile_edit_wrap"-->
</body>
</html>
