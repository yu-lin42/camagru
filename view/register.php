<?php
	require('view/clean.php');
	session_start();
?>
<head>
	<script src="/<?=$server_location?>/controller/register.js"></script>
	<link rel="stylesheet" href="/<?=$server_location?>/view/style.css">
</head>
<main>
	<div class="box">
		<div class="container">
			<div>
				<img src="/<?=$server_location?>/view/img/camagru_logo_lite.png" alt="logo" width="250px">
			</div>
			<div id="form" class="form">
				<div id="registerbox" class="registerbox" action="/<?=$server_location?>/model/newuser.php" method="post">
					<input id="username" type="text" name="usr" placeholder="Username">
					<input id="email" type="text" name="mail" placeholder="E-mail">
					<input id="password" type="password" name="pwd" placeholder="Password">
					<input id="confirm" type="password" name="pwd-repeat" placeholder="Reconfirm Password">
					<div><button id="submit" type="button" name="signup-submit">Signup</button></div>
				</div>
				<div class="register">
					<h1>Or</h1>
					<h3>Already have an account? <a href="/<?=$server_location?>/view/login.php">Log In</a></h3>
				</div>			
			</div>
		</div>
		<div id="errors" class="error_box"></div>
	</div>
</main>
<?php
	require "view/footer.php";
?>