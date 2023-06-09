<?php
include('../db.php');

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $status = "Denied";

    $stmt = $db->prepare("UPDATE car SET car_status = ? WHERE user_id = ?");
    $stmt->bind_param("si", $status, $user_id);
    $stmt->execute();

    if ($stmt) {
        header('Location: carReg.php?status=Pending');
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
