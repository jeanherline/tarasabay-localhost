<?php
include('db.php');

if (isset($_GET['owner_id'])) {
    $id = $_GET['owner_id'];

    $status = "declined";

    $stmt = $db->prepare("UPDATE car SET status = ? WHERE owner_id = ?");
    $stmt->bind_param("ss", $status, $id);
    $result = $stmt->execute();

    if ($result) {
        header('Location: admin.php');
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
