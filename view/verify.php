<?php
require('config/connect.php');
require('view/clean.php');
session_start();

$email = $_GET["email"];
$token = $_GET["token"];

$stmt = $conn->prepare("UPDATE `users` SET `verified`=1 WHERE `email`=:email AND `token`=:token");
$stmt->bindParam(":email", $email);
$stmt->bindParam(":token", $token);
$stmt->execute();

if ($stmt->rowCount() == 1) {
?>
	<link rel="stylesheet" href="/<?=$server_location?>/view/style.css">
	<form action="/<?=$server_location?>/view/login.php">
		<h2>Thank you for registering with Camagru!</h2>
		Your account has been created. 
		You can log in now. :D<br>
		<button>Log in</button>
	</form>
<?php
}
else {
?>
	<link rel="stylesheet" href="/<?=$server_location?>/view/style.css">
	
	<h2>Sorry, this verification is invalid. Please try again. :(</h2>
<?php
}
?>