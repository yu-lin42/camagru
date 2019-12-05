<?php
require "config/database.php";
session_start();

preg_match("/.*htdocs\/(.*)\/config.*/", $_SERVER["SCRIPT_FILENAME"], $matches);
$server_location = $matches[1];
try {
	$conn = new PDO("mysql:host=$DB_HOST", $DB_USER, $DB_PASSWORD);
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$statement = $conn->prepare("CREATE DATABASE IF NOT EXISTS camagru");
	$statement->execute();
	echo "Successfully created the database<br>";
	$statement = $conn->prepare("CREATE TABLE IF NOT EXISTS `camagru`.`users` (".
		"`uid` INT AUTO_INCREMENT NOT NULL,".
		"`username` VARCHAR(30) NOT NULL UNIQUE,".
		"`email` VARCHAR(250) NOT NULL UNIQUE,".
		"`hashpwd` VARCHAR(256) NOT NULL,".
		"`token` VARCHAR(255) NOT NULL UNIQUE,".
		"`verified` BOOL NOT NULL,".
		"`notified` BOOL NOT NULL,".
		"PRIMARY KEY(`uid`)".
		");"
	);
	$statement->execute();
	echo "Successfully created the table `users`<br>";
}
catch(PDOException $e)
{
	echo "Database Connection failed: ".$e->getMessage();
}

try {

	$statement=$conn->prepare("CREATE TABLE IF NOT EXISTS `camagru`.`gallery` (".
		"`img_id` INT(11) AUTO_INCREMENT NOT NULL,".
		"`img_path` VARCHAR(255) NOT NULL,".
		"`uploader_id` INT NOT NULL,".
		"`description` VARCHAR(255),".
		"`date_uploaded` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,".
		"PRIMARY KEY(`img_id`)".
		");"
	);
	$statement->execute();
	echo "Successfully created the table `gallery`<br>";
}
catch(PDOException $e) {
	echo "Failed to create table `gallery`<br>";
}

try {

	$statement=$conn->prepare("CREATE TABLE IF NOT EXISTS `camagru`.`comments` (".
		"`id` INT(11) AUTO_INCREMENT NOT NULL,".
		"`post_id` INT(11) NOT NULL,".
		"`commentor_id` INT NOT NULL,".
		"`comment` VARCHAR(255),".
		"`date_commented` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,".
		"PRIMARY KEY(`id`)".
		");"
	);
	$statement->execute();
	echo "Successfully created the table `comments`<br>";
}
catch(PDOException $e) {
	echo "Failed to create table `gallery`<br>";
}

try {

	$statement=$conn->prepare("CREATE TABLE IF NOT EXISTS `camagru`.`likes` (".
		"`id` INT(11) AUTO_INCREMENT NOT NULL,".
		"`post_id` INT(11) NOT NULL,".
		"`liker_id` INT NOT NULL,".
		"PRIMARY KEY(`id`),".
		"FOREIGN KEY(`post_id`) REFERENCES `gallery`(`img_id`) ON DELETE CASCADE,".
		"FOREIGN KEY(`liker_id`) REFERENCES `users`(`uid`) ON DELETE CASCADE".
		");"
	);
	$statement->execute();
	$statement=$conn->prepare("ALTER TABLE `camagru`.`likes` ADD CONSTRAINT UNIQUE(`post_id`, `liker_id`);"
);
$statement->execute();
	echo "Successfully created the table `likes`<br>";
}
catch(PDOException $e) {
	echo "Failed to create table `likes` because: ".$e->getMessage()."<br>";
}
?>