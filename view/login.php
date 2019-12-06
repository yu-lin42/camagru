<?php
	require('view/clean.php');
?>
<title>Camagru_login</title>
<head>
	<link rel="stylesheet" href="/<?=$server_location?>/view/style.css">
</head>
<body>
	<main>
		<div class="container">
			<div>
				<img src="img/camagru_login_dark.png" alt="logo" width=250px>
			</div>
			<div class="form">
				<div class="loginbox" action="/<?=$server_location?>/model/login_check.php" method="post">
					<input type="text" id="username" placeholder="Username">
					<input type="password" id="passwd" placeholder="Password">
					<a href="/<?=$server_location?>/view/forgotten.php">Forgot Password</a>
					<button type="submit" id="login">Log In</button>
				</div>
			</div>
			<div class="register">
				<h1>Or</h1>
				<h3>Need an Account? <a href="/<?=$server_location?>/view/register.php">Register</a></h3>
			</div>	
		</div>
	</main>
</body>
<?php
	require "view/footer.php"
?>

<script>
var username = document.getElementById("username");
var passwd = document.getElementById("passwd");
var login = document.getElementById("login");

login.addEventListener("click", () => {
	var request = new XMLHttpRequest();
	request.addEventListener("load", () => {
		if (request.status == 200){
			location.replace("/<?=$server_location?>/");
		}
		else if (request.status == 400){
			alert("Username or Password is invalid");
		}
	});
	request.open("POST", "/<?=$server_location?>/model/login_check.php");
	request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	request.send(
		"username=" + username.value +
		"&passwd=" + passwd.value
	);
})
</script>