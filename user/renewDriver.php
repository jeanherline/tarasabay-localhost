<?php
include('../db.php');

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $status = "Active";

    $stmt = $db->prepare("UPDATE driver_identification SET driver_stat = ? WHERE user_id = ?");
    $stmt->bind_param("si", $status, $user_id);
    $stmt->execute();

    if ($stmt) {
        header('Location: renewal.php?list=Driver');
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
