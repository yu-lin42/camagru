<?php
require('config/connect.php');
session_start();

$user_id = $_SESSION['sess_uid'];
$notify = $_POST['notifi'];
try {
    $stmnt = $conn->prepare("UPDATE users SET `notified`=:notify WHERE `uid`=:user_id");
    $stmnt->bindParam(':user_id', $user_id);
    $stmnt->bindParam(':notify', $notify);
    $stmnt->execute();
}
catch(PDOException $e) {
    $e->getMessage();
}
?>