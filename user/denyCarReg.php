<?php
include('../db.php');

if (isset($_GET['user_id']) && isset($_GET['car_id']) && isset($_GET['status'])) {
    $user_id = $_GET['user_id'];
    $car_id = $_GET['car_id'];
    $stat = $_GET['status'];
    $status = "Denied";

    // Retrieve the QR code path from the database
    $getQrCodeQuery = "SELECT qr_code FROM car WHERE car_id = ?";
    $getQrCodeStmt = $db->prepare($getQrCodeQuery);
    $getQrCodeStmt->bind_param("i", $car_id);
    $getQrCodeStmt->execute();
    $qrCodeResult = $getQrCodeStmt->get_result();
    $qrCodeRow = $qrCodeResult->fetch_assoc();
    $qrCodePath = $qrCodeRow['qr_code'];

    // Delete the QR code file from the folder
    if (!empty($qrCodePath) && file_exists($qrCodePath)) {
        unlink($qrCodePath);
    }

    // Update the car status and QR code path in the database
    $stmt = $db->prepare("UPDATE car SET car_status = ?, qr_code = NULL WHERE car_id = ?");
    $stmt->bind_param("si", $status, $car_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header("Location: carReg.php?status=$stat");
        exit();
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
?>
