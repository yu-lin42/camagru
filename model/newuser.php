<?php
require('config/connect.php');
require('model/clean.php');
session_start();

$username = $_POST['usr'];
$email = $_POST['mail'];
$passwd = $_POST['pwd'];
$passwdRepeat = $_POST['pwd-repeat'];
$fail = false;

if (empty($username) || empty($email) || empty($passwd) || empty($passwdRepeat)) {
	$fail = true;
	echo "Cannot have empty fields:";
}
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
	$fail = true;
	echo "Email is invalid:";
}
if (!preg_match("/^[a-zA-Z0-9]{5,}$/", $username)) {
	$fail = true;
	echo "Username can only contain letters and numbers and must be longer than 5 characters:";
}
if ($passwd !== $passwdRepeat) {
	$fail = true;
	echo "Passwords don't match:";
}
if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#\$%\^&\*])(?=.{8,})/", $passwd)) {
	$fail = true;
	echo "Password must have 1 upper 1 lower 1 special and 1 number and must be longer than 8 characters:";
}
	if ($fail == false) {	
		$token = bin2hex(random_bytes(10));
		$pwdhash = password_hash($passwd, PASSWORD_BCRYPT);
		try {
			$sql = $conn->prepare("INSERT INTO users(username, email, hashpwd, token, verified, notified)
			VALUES (?, ?, ?, ?, ?, ?)");
			$sql->execute(array($username, $email, $pwdhash, $token, 0, 0));

			$to      = $email; // Send email to our user
			$subject = 'Signup | Verification'; // Give the email a subject 
			$message = '

			Thanks for signing up!
			Your account has been created, you can login with the following credentials after you have activated your account by pressing the url below.
			
			------------------------
			Username: '.$username.'
			Password: '.$passwd.'
			------------------------
			
			Please click this link to activate your account:
			http://localhost:8080/'.$server_location.'/view/verify.php?email='.$email.'&token='.$token.'
			
			'; // Our message above including the link
							
			$headers = 'From:noreply@camagru.com' . "\r\n"; // Set from headers
			mail($to, $subject, $message, $headers); // Send our email

			echo "An email has been sent for verification.";
		}		
		catch(PDOException $e)
		{
			http_response_code(400);
			echo "Email has been taken";
		}
}
else {
	http_response_code(400);
}
?>