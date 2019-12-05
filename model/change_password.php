<?php
    require('config/connect.php');
    session_start();

    $old_pwd = $_POST["old_pwd"];
    $new_pwd = $_POST["new_pwd"];
    $user = $_SESSION["sess_uid"];
    $new_pwdhash = password_hash($new_pwd, PASSWORD_BCRYPT);
    try {
        $sql = $conn->prepare('SELECT * FROM `users` WHERE `uid`=?');
        $sql->execute(array($user));
        $found=$sql->fetch(PDO::FETCH_ASSOC);
        if (password_verify($old_pwd, $found['hashpwd'])) {
            $stmnt = $conn->prepare('UPDATE `users` SET `hashpwd`=:new_pwdhash WHERE `uid`=:user');
            $stmnt->bindParam(':new_pwdhash', $new_pwdhash);
            $stmnt->bindParam(':user', $user);
            if ($stmnt->execute()) {
                http_response_code(200);
            }
            else {
                http_response_code(400);
            }
        }
        else {
            http_response_code(400);
        }
    }
    catch(PDOException $e) {
        echo $e->getMessage;
    }
    ?>