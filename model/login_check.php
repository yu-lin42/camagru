<?php
require('config/connect.php');
require('model/clean.php');
session_start();

	$username = $_POST['username'];
	$passwd = $_POST['passwd'];
	
	$statement = $conn->prepare("SELECT * FROM `users` WHERE `username`=:username AND`verified` = 1");
	$statement->bindParam(':username', $username, PDO::PARAM_STR);
	$statement->execute();
	$count = $statement->rowCount();
	$row = $statement->fetch(PDO::FETCH_ASSOC);
	
	if ($count > 0 && !empty($row))
	{
		$hash = password_verify($passwd, $row["hashpwd"]);
		if ($hash == true) {
		// password check
			$_SESSION['sess_uid'] = $row['uid'];
			header("Location: /$server_location/view/home.php");
		}
		else {
			http_response_code(400);
		}
	}
	else {
		http_response_code(400);
	}
?>