<?php
include('../db.php');

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $role = "Previous City Admin";

    $stmt = $db->prepare("UPDATE user_profile SET role = ? WHERE user_id = ?");
    $stmt->bind_param("si", $role, $user_id);
    $stmt->execute();

    if ($stmt) {
        header('Location: cityAdmins.php');
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
