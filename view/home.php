<?php
require('config/connect.php');
require('view/clean.php');
session_start();
?>
<head>
	<title>Camagru</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="/<?=$server_location?>/view/home.css">
  <script src="/<?=$server_location?>/controller/comments.js"></script>
  <script src="/<?=$server_location?>/controller/likes.js"></script>
</head>
<body>
  <?php require('header.php'); ?>
  <main>
    <div class="content_box">
      <?php
        try {
          $page = isset($_GET['page']) ? $_GET['page'] : 1;
          if ($page < 1)
            $page = 1;
          $page_len = 5;
          $offset = (($page - 1) * $page_len);

          $stmnt = $conn->prepare("SELECT COUNT(*) FROM `gallery`");
          $stmnt->execute();
          $total_posts = $stmnt->fetch()[0];
          $has_more_posts = (($offset + $page_len) < $total_posts);

          $stmnt = $conn->prepare("SELECT `gallery`.* FROM `gallery` ORDER BY `gallery`.`date_uploaded` DESC LIMIT $page_len OFFSET :offset");
          $stmnt->bindParam(":offset", $offset, PDO::PARAM_INT);
          $stmnt->execute();

          // getting the time uploaded
          $stmt = $conn->prepare("SELECT `gallery`.*, `users`.`username` FROM `gallery` LEFT JOIN `users` ON `users`.`uid`=`gallery`.`uploader_id` ORDER BY `gallery`.`date_uploaded` DESC LIMIT $page_len OFFSET " . (($page - 1) * $page_len));
          $stmt->execute();
          while($row = $stmt->fetch(PDO::FETCH_ASSOC))
          {
            $date = new DateTime($row['date_uploaded']);
      ?>
      <!-- All images are posted to -->
        <div class="media-content" id="post-<?=$row['img_id']?>">
            <div class="content">
                <strong><?php echo "@" . $row["username"];?></strong>
                <small><?php echo $date->format("d M Y H:i");?></small>
                <br>
                <div class="img_box">
                  <img src="<?="/" . $server_location . "/upload/" . $row['img_path']?>" width="400px">
                </div>

                <div>
                  <a <?php if (!isset($_SESSION['sess_uid'])) 
                    echo "style=\"pointer-events: none\""; ?> onclick="like(<?=$row['img_id']?>)"><img id="like" src="/<?=$server_location?>/view/img/like_heart.png" width="20px"></a>
                  <span id="like_count-<?=$row['img_id']?>">
                    <?php
                      $like_stmnt = $conn->prepare("SELECT COUNT(*) FROM `likes` WHERE `post_id`=?");
                      $like_stmnt->execute(array($row['img_id']));
                      echo ($like_stmnt->fetch()[0]);
                      ?>
                  </span>
                </div>
                <!-- Where the comments are displayed after submit -->
                <div class="comment_box" id="comment-box-<?=$row['img_id']?>">
                  <?php
                    $comment_stmnt = $conn->prepare("SELECT `comments`.*, `users`.`username` FROM `comments` LEFT JOIN `users` ON `users`.`uid`=`comments`.`commentor_id` WHERE `comments`.`post_id`=? ORDER BY `comments`.`date_commented` DESC");
                    $comment_stmnt->execute(array($row['img_id']));
                    while($comment = $comment_stmnt->fetch(PDO::FETCH_ASSOC))
                    {
                  ?>
                    <p>
                      <strong>@<?=$comment['username']?> says: </strong> <?=$comment['comment']?>
                    </p>
                  <?php
                    }
                  ?>
                </div>
                <!-- where people comment -->
                <?php
                  if (isset($_SESSION['sess_uid']))
                  {
                  ?>
                <div class="commentor_input_box">
                  <input type="textarea" id="comment-input-<?=$row['img_id']?>"/>
                </div>
                <div class="comment_button">
                  <input type="button" id="submit" value="submit" onclick="comment(<?=$row['img_id']?>)"/>
                </div>
                  <?php
                  }
                ?>
            </div>
        </div>
        <br>
      <?php
        }
      } catch (Exception $e) {
        echo $e->getMessage();
      }?>
    </div>
    <div class="paging">
      <?php if ($page > 1) { ?>
        <button><a href="?page=<?=$page - 1?>">prev</a></button>
      <?php } ?>
      <?php if ($has_more_posts) { ?>
        <button><a href="?page=<?=$page + 1?>">next</a></button>
      <?php } ?>
    </div>
  </main>
</body>
<?php
	require "footer.php";
?>