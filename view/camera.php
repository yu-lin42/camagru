<?php
require('config/connect.php');
session_start();
require('view/clean.php');
require('view/header.php');

try {
	if (isset($_SESSION['sess_uid'])) {
		$stmnt = $conn->prepare("SELECT * FROM `users` WHERE `uid`= ?");
		$stmnt->execute(array($_SESSION['sess_uid']));
		$user = $stmnt->fetch(PDO::FETCH_ASSOC);
		
		$stmnt = $conn->prepare("SELECT `gallery` .* FROM `gallery` WHERE `uploader_id`=:user ORDER BY `gallery`.`date_uploaded` DESC");
		$stmnt->bindParam(":user", $_SESSION['sess_uid']);
		$stmnt->execute();
?>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="/<?=$server_location?>/view/home.css">
	</head>
	<body>
		<main>
			<div class="editor-box">

				<div class="left-box">
					<div class="box-header">
						<strong>Gallery</strong>
					</div>
					<div class="gallery-scroll">
					<?php
					while ($row = $stmnt->fetch(PDO::FETCH_ASSOC))
					{
					?>
						<div class="display">
							<img src="/<?=$server_location?>/upload/<?=$row['img_path']?>">
						</div>
					<?php
					}
					?>
					</div>
				</div>

				<div class="middle-box">
					<div class="camerabooth">
						<video autoplay="true" id="video"></video>
					</div>
					<div class="options">
						<input type="submit" id="capture" value="Capture">
						<input type="submit" id="add_sticker" value="Add Stickers">
						<input type="submit" id="reset" value="Reset">
						<input type="submit" id="save" value="Post">
					</div>
					<div id="preview" class="preview">
						<canvas id="canvas" style="display: none;"></canvas>
						<canvas id="pseudo-canvas" style="display: none;"></canvas>
					</div>
				</div>

				<div class="right-box">
					<div class="box-header">
						<strong>Stickers to add</strong>
					</div>
					<div id="stickers" class="stickers" style="display: none;">
						<img class="sticker-toggle sticker" src="/<?=$server_location?>/stickers/kawaii_dog.png" width="100px">
						<img class="sticker-toggle sticker" src="/<?=$server_location?>/stickers/cat_hug.png" width="100px">
						<img class="sticker-toggle sticker" src="/<?=$server_location?>/stickers/cute_star.png" width="100px">
						<img class="sticker-toggle sticker" src="/<?=$server_location?>/stickers/kawaii_pikachu.png" width="100px">
						<img class="sticker-toggle sticker" src="/<?=$server_location?>/stickers/cute_taco.png" width="100px">
					</div>
				</div>
			</div>

			<script src="/<?=$server_location?>/controller/camera_stuff.js"></script>
		</main>
	</body>
<?php
	}
	else {
		header("location: /$server_location/view/error.php?message=Please+log+in");
	}
}
catch(Exception $e) {
	$e->getMessage();
}
?>