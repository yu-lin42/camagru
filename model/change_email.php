<?php
    require('config/connect.php');
    session_start();

    $new_email = $_POST["new_mail"];
    $user = $_SESSION["sess_uid"];

    $fail = false;
    if (empty($new_email)){
        $fail = true;
        echo "Email cannot be empty:";
    }
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        $fail = true;
        echo "That is an invalid email:";
    }
    if ($fail)
    {
        http_response_code(400);
        return;
    }
    try {
        $stmnt = $conn->prepare('UPDATE `users` SET `email`=:new_email WHERE `uid`=:user');
        $stmnt->bindParam(':new_email', $new_email);
        $stmnt->bindParam(':user', $user);
        if ($stmnt->execute()) {
            http_response_code(200);
        }
        else {
            http_response_code(400);
        }
    }
    catch(PDOException $e) {
        echo $e->getMessage;
    }
    ?>