<?php
require('config/connect.php');
require('model/clean.php');
try {
    $email = trim($_POST['mail']);
    $result = 0;

    $sql = "SELECT * FROM `users` WHERE `email`=:email";
    $statement = $conn->prepare($sql);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();
    $result = $statement->rowCount();
    $row = $statement->fetch(PDO::FETCH_ASSOC);
    if ($result > 0 && !empty($row))
    {
        $token = bin2hex(random_bytes(10));
        $stmnt = $conn->prepare("UPDATE `users` SET `token`=:token WHERE `email`=:email");
        $stmnt->bindParam(":email", $email);
        $stmnt->bindParam(":token", $token);
        $stmnt->execute();

        $to      = $email; 
        $subject = 'Camagru | Forgot Password';
        $message = '
        Dear '.$username.',<br>
        You are receiving this email because you requested a password reset for your account.<br>
        <br>
        Please click on the link below to reset your password:<br>
        http://localhost:8080/'.$server_location.'/model/reset_password.php?email='.$email.'&token='.$token.'<br>
        ';

        $headers = "From: <noreply@camagru.com>"."\r\n";
        if (mail($to, $subject, $message, $headers)) {
            http_response_code(200);
        }
        else{
            http_response_code(400);
        }
    }
    else{
        http_response_code(400);
    }
}
catch(PDOException $e) {
    echo $statement."<br>".$e->getMessage();
}
?>