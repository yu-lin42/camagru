<?php
	require('config/connect.php');
	require('model/clean.php');
	$email = $_GET['email'];
	$token = $_GET['token'];

	$stmnt = $conn->prepare("SELECT * FROM `users` WHERE `email`=:email AND `token`=:token");
	$stmnt->bindParam(':email', $email, PDO::PARAM_STR);
	$stmnt->bindParam(':token', $token, PDO::PARAM_STR);
	$stmnt->execute();
	if ($stmnt->rowCount() == 1)
	{
?>
<head>
    <link rel="stylesheet" href="/<?=$server_location?>/view/style.css">
</head>
<body>
	<script src="/<?=$server_location?>/controller/update_user.js"></script>
	<div class="box">
		<div class="container">
			<div>
				<img src="/<?=$server_location?>/view/img/camagru_logo_lite.png" alt="logo" width="250px">
			</div>
            <div align=center>
                <h3>Please enter your email and new password</h3>
            </div>
			<div id="form" class="form">
				<div class="resetbox">
					<input type="hidden" id="email" value="<?=$_GET['email']?>">
					<input type="hidden" id="token" value="<?=$_GET['token']?>">
					<input type="password" id="new_pwd" placeholder="New Password">
					<input type="password" name="renewpwd" placeholder="Re-enter New Password">
					<button type="submit" id="reset_pwd" onclick="reset_password()">Reset Password</button>
				</div>
				<div id="error_msg"></div>
			</div>
		</div>
	</div>
</body>
<?php
	}
	else {
		header("location: /$server_location/view/error.php?message=Invalid+Token");
	}
?>
