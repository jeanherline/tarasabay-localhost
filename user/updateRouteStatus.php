<?php
include('../db.php');

if (isset($_GET['route_id']) && isset($_GET['status'])) {
    $route_id = $_GET['route_id'];
    $status = $_GET['status'];

    // Update the route status in the database
    $updateRouteSql = "UPDATE route SET route_status = ? WHERE route_id = ?";
    $stmt = $db->prepare($updateRouteSql);
    $stmt->bind_param("si", $status, $route_id);
    $stmt->execute();
}
