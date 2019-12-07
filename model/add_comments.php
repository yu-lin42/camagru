<?php
    require('config/connect.php');
    session_start();
    
    $commentor_id = $_SESSION['sess_uid'];
    $comment = htmlspecialchars($_POST['comment_input']);
    $post_id = $_POST['post_id'];

    try {
        $sql = $conn->prepare("INSERT INTO comments(`post_id`, `commentor_id`, `comment`)
        VALUES (?, ?, ?)");
        $sql->execute(array($post_id, $commentor_id, $comment));

        $stmnt = $conn->prepare("SELECT * FROM users WHERE `uid`=:commentor_id");
        $stmnt->bindParam(':commentor_id', $commentor_id, PDO::PARAM_STR);
        $stmnt->execute();
        $results = $stmnt->fetch(PDO::FETCH_ASSOC);
        echo ("<strong>@". htmlspecialchars($results["username"]) . " says: </strong> " . htmlspecialchars($comment));

        $stmt = $conn->prepare("SELECT `users`.`email`, `users`.`username` FROM `gallery` LEFT JOIN `users` ON `gallery`.`uploader_id`=`users`.`uid` WHERE `users`.`notified`=1 AND `gallery`.`img_id`=?");
        $stmt->execute(array($post_id));
        if ($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
            $email=$row['email'];

            $to = $email;
            $subject = 'Notifications';
            $message = '
            Hey, '.$row['username'].',
            Someone commented on your post.
            ';

            $header = 'From:noreply@camagru.com'."\r\n";
            mail($to, $subject, $message, $header);
        }
    }
    catch(Exception $e) {
        echo $e->getMessage();
    }
?>