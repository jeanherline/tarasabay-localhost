<?php
include('../db.php');

if (isset($_GET['user_id']) && isset($_GET['seat_id']) && isset($_GET['list'])) {
    $user_id = $_GET['user_id'];
    $seat_id = $_GET['seat_id'];
    $list = $_GET['list'];

    $updateBookingSql = "UPDATE booking SET booking_status = 'Picked-up' WHERE user_id = ? AND seat_id = ?";
    $stmt = $db->prepare($updateBookingSql);
    $stmt->bind_param("ii", $user_id, $seat_id);
    $stmt->execute();

    header("Location: $list");
    exit();
}
