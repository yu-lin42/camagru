<?php
require "config/database.php";
try {
	$conn = new PDO("mysql:dbname=$DB_NAME;host=$DB_HOST", $DB_USER, $DB_PASSWORD); //DB_DSN
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e)
{
	echo "Database Connection failed: ".$e->getMessage();
}
?>