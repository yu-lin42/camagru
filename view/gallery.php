<?php
require('config/connect.php');
require('view/clean.php');
session_start();
require('view/header.php');

try{
  // The user that logged in
  $stmnt = $conn->prepare("SELECT * FROM `users` WHERE `uid`= ?");
  $stmnt->execute(array($_SESSION['sess_uid']));
  $user = $stmnt->fetch(PDO::FETCH_ASSOC);

  // Page is on
  $page = isset($_GET['page']) ? $_GET['page'] : 1;
  if ($page < 1)
    $page = 1;
  $page_len = 5;
  $offset = (($page - 1) * $page_len);

  // Total posts from this user
  $stmnt = $conn->prepare("SELECT COUNT(*) FROM `gallery` WHERE `uploader_id`=:user");
  $stmnt->bindParam(":user", $_SESSION['sess_uid']);
  $stmnt->execute();
  $total_posts = $stmnt->fetch()[0];
  $has_more_posts = (($offset + $page_len) < $total_posts);

  // Only get 5 images at a time
  $stmnt = $conn->prepare("SELECT `gallery`.* FROM `gallery` WHERE `uploader_id`=:user ORDER BY `gallery`.`date_uploaded` DESC LIMIT $page_len OFFSET :offset");
  $stmnt->bindParam(":user", $_SESSION['sess_uid']);
  $stmnt->bindParam(":offset", $offset, PDO::PARAM_INT);
  $stmnt->execute();

?>
<head>
	<title>My Gallery</title>
  <link rel="stylesheet" href="/<?=$server_location?>/view/home.css">
  <script src="/<?=$server_location?>/controller/delete.js"></script>
</head>
<body>
  <main>
  <div class="profile">
    <strong>Username: </strong><?=$user['username']?>
    <br>
    <strong>Email: </strong><?=$user['email']?>
  </div>
  <hr>
    <div class="content_box">
    <?php
      while($row = $stmnt->fetch(PDO::FETCH_ASSOC))
      {
    ?>
      <div class="gallery-content" id="post-<?=$row['img_id']?>">  
        <div class="content">
          <div class="img_box" id="img-<?=$row['img_id']?>">
            <img src="/<?=$server_location?>/upload/<?=$row['img_path']?>" width="400px">
          </div>
          <div>
            Has 
            <?php
                $like_stmnt = $conn->prepare("SELECT COUNT(*) FROM `likes` WHERE `post_id`=?");
                $like_stmnt->execute(array($row['img_id']));
                echo ($like_stmnt->fetch()[0]);
            ?>
             likes and 
             <?php
                $comment_stmnt = $conn->prepare("SELECT COUNT(*) FROM `comments` WHERE `post_id`=?");
                $comment_stmnt->execute(array($row['img_id']));
                echo ($comment_stmnt->fetch()[0]);
            ?>
            comments
          </div>
          <input type="button" value="Delete Post" onclick="deletePost(<?=$row['img_id']?>)"/>
          <div class="comment_box">
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
        </div>
    </div>
      <?php
        }
      }
      catch(Exception $e) {
        echo $e->getMessage();
      }
      ?>
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
	require "footer.php"
?>