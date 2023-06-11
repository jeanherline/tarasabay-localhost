<?php
include('../db.php');

if (isset($_GET['user_id']) && isset($_GET['car_id']) && isset($_GET['route_id'])) {
    $user_id = $_GET['user_id'];
    $car_id = $_GET['car_id'];
    $route_id = $_GET['route_id'];
    $status = "Done";

    $stmt = $db->prepare("UPDATE route SET route_status = ? WHERE car_id = ? AND route_id = ?");
    $stmt->bind_param("sii", $status, $car_id, $route_id);
    $stmt->execute();

    header("Location: driverRoute.php?status=Active");
    exit();
}
