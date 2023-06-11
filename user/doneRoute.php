<?php
include('../db.php');

if (isset($_GET['user_id']) && isset($_GET['car_id']) && isset($_GET['list']) &&  isset($_GET['route_id'])) {
    $user_id = $_GET['user_id'];
    $car_id = $_GET['car_id'];
    $route_id = $_GET['route_id'];
    $list = $_GET['list'];

    $updateBookingSql = "UPDATE route SET route_status = 'Done' WHERE car_id = ? AND route_id = ?";
    $stmt = $db->prepare($updateBookingSql);
    $stmt->bind_param("ii", $car_id, $route_id);
    $stmt->execute();

    header("Location: $list");
    exit();
}
