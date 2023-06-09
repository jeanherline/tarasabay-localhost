<?php
include('../db.php');

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $status = "Denied";

    // Retrieve the QR code path from the database
    $getQrCodeQuery = "SELECT qr_code FROM car WHERE user_id = ?";
    $getQrCodeStmt = $db->prepare($getQrCodeQuery);
    $getQrCodeStmt->bind_param("i", $user_id);
    $getQrCodeStmt->execute();
    $qrCodeResult = $getQrCodeStmt->get_result();
    $qrCodeRow = $qrCodeResult->fetch_assoc();
    $qrCodePath = $qrCodeRow['qr_code'];

    // Delete the QR code file from the folder
    if (file_exists($qrCodePath)) {
        unlink($qrCodePath);
    }

    // Update the car status and QR code path in the database
    $stmt = $db->prepare("UPDATE car SET car_status = ?, qr_code = NULL WHERE user_id = ?");
    $stmt->bind_param("si", $status, $user_id);
    $stmt->execute();

    if ($stmt) {
        header('Location: carReg.php?status=Pending');
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
