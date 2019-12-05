<?php
    require('config/connect.php');
    session_start();

    $post_id = $_POST['post_id'];
    $user = $_SESSION['sess_uid'];
    try {
        $selectLike = $conn->prepare("SELECT * FROM likes WHERE `liker_id`=:user AND `post_id`=:post");
        $selectLike->bindParam(':user', $user);
        $selectLike->bindParam(':post', $post_id);
        $selectLike->execute();
        $result = $selectLike->fetch();

        if (!$result)
        {
            $createLike = $conn->prepare("INSERT INTO likes (`post_id`, `liker_id`)
            VALUES (?, ?)");
            $createLike->execute(array($post_id, $user));
            http_response_code(201);

            $stmt = $conn->prepare("SELECT `users`.`email`, `users`.`username` FROM `gallery` LEFT JOIN `users` ON `gallery`.`uploader_id`=`users`.`uid` WHERE `users`.`notified`=1 AND `gallery`.`img_id`=?");
            $stmt->execute(array($post_id));
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC))
            {
                $email=$row['email'];

                $to = $email;
                $subject = 'Notifications';
                $message = '
                Hey, '.$row['username'].',
                Someone liked your post.
                ';

                $header = 'From:noreply@camagru.com'."\r\n";
                mail($to, $subject, $message, $header);
            }
        }
        else 
        {
            $deleteLike = $conn->prepare("DELETE FROM likes WHERE `liker_id`=:user AND `post_id`=:post");
            $deleteLike->bindParam(':user', $user);
            $deleteLike->bindParam(':post', $post_id);
            $deleteLike->execute();
            http_response_code(204);
        }
        $selectLikeCount = $conn->prepare("SELECT COUNT(*) FROM likes WHERE `post_id`=:post");
        $selectLikeCount->bindParam(':post', $post_id);
        $selectLikeCount->execute();
        $count = $selectLikeCount->fetch()[0];

        echo $count;
    }
    catch(Exception $e) {
        http_response_code(400);
        echo $e->getMessage();
    }
?>