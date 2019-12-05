<?php
	require('config/connect.php');
	session_start();

    $email = $_POST["mail"];
    $token = $_POST["token"];
	$newpwd = $_POST["newpwd"];
	try {
		$pwdhash = password_hash($newpwd, PASSWORD_BCRYPT);
		$stmnt = $conn->prepare('UPDATE `users` SET `hashpwd`=:pwdhash WHERE `email`=:email AND `token`=:token');
		$stmnt->bindParam(':email', $email);
		$stmnt->bindParam(':token',$token);
		$stmnt->bindParam(':pwdhash', $pwdhash);
		if ($stmnt->execute()) {
			http_response_code(200);
		}
		else {
			http_response_code(400);
		}
	}
	catch(PDOException $e) {
		echo $e->getMessage;
	}
?>