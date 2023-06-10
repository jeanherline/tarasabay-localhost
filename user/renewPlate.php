<?php
include('../db.php');

if (isset($_GET['user_id']) && isset($_GET['car_id'])) {
    $user_id = $_GET['user_id'];
    $car_id = $_GET['car_id'];
    $status = "Active";

    $stmt = $db->prepare("UPDATE car SET car_status = ? WHERE user_id = ? AND car_id = ?");
    $stmt->bind_param("sii", $status, $user_id, $car_id);
    $stmt->execute();

    if ($stmt) {
        header('Location: renewal.php?list=Plate');
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
