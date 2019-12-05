<?php
    require('config/connect.php');
    session_start();

    $new_name = $_POST["new_name"];
    $user = $_SESSION["sess_uid"];

    $fail = false;
    if (empty($new_name)){
        $fail = true;
        echo "Username cannot be empty:";
    }
    if (!preg_match("/^[a-zA-Z0-9]*$/", $new_name)) {
        $fail = true;
        echo "Username can only contain letters and numbers:";
    }
    if (!preg_match("/^.{5,}$/", $new_name))
    {
        $fail = true;
        echo "Username Must be 5 or more characters long:";
    }
    if ($fail)
    {
        http_response_code(400);
        return;
    }
    try {
        $stmnt = $conn->prepare('UPDATE `users` SET `username`=:new_name WHERE `uid`=:user');
        $stmnt->bindParam(':new_name', $new_name);
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