<?php
    require('config/connect.php');
    session_start();

    $post_id = $_POST['post_id'];
    $user = $_SESSION['sess_uid'];
    try {
        $sql = $conn->prepare("DELETE FROM `gallery` WHERE `img_id`=:post_id AND `uploader_id`=:user");
        $sql->bindParam(':post_id', $post_id);
        $sql->bindParam(':user', $user);
        $sql->execute();
    }
    catch(Exception $e) {
        echo $e->getMessage();
    }
?>