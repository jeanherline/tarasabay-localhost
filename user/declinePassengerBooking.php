<?php
include('../db.php');

if (isset($_GET['user_id']) && isset($_GET['seat_id']) && isset($_GET['route_id'])) {
    $user_id = $_GET['user_id'];
    $seat_id = $_GET['seat_id'];
    $route_id = $_GET['route_id'];

    $updateBookingSql = "UPDATE booking SET booking_status = 'Declined' WHERE user_id = ? AND seat_id = ?";
    $stmt = $db->prepare($updateBookingSql);
    $stmt->bind_param("ii", $user_id, $seat_id);
    $stmt->execute();

    header("Location: driverBooking.php?list=Pending");
    exit();
}
?>
