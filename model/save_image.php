<?php
require('config/connect.php');
require('model/clean.php');
session_start();

$user_id = $_SESSION["sess_uid"];
$img_data = $_POST['image'];

$stickers = explode(',', $_POST['stickers']);
$image_data = $img_data;
$image = imagecreatefromstring(base64_decode($image_data));
foreach ($stickers as $sticker) {
    $stickerData = imagecreatefrompng($sticker);
    imagealphablending($image, true);
	imagesavealpha($image, true);
	imagesavealpha($stickerData, true);
	$stickerData = imagescale($stickerData, 100, 100);
	imagecopy($image, $stickerData, 0, 0, 0, 0, 100, 100);
}

$timestamp = time();
$name = "$timestamp";

// if not create directory
$dir = "../upload/";
if (!file_exists($dir) && !is_dir($dir)){
    mkdir($dir);
}
$imagepath = "../upload/".$name. ".png";
imagepng($image, $imagepath);
try {
    $statement = $conn->prepare("INSERT INTO gallery(`img_path`, `uploader_id`) VALUES (?, ?)");
    $statement->execute(array($name . ".png", $user_id));
}
catch(Exception $e) {
    echo $e->getMessage();
}
?>