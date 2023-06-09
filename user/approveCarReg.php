<?php
include('../db.php');
include('../phpqrcode/qrlib.php'); // Include the QR code library

if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    $status = "Active";

    $stmt = $db->prepare("UPDATE car SET car_status = ? WHERE user_id = ?");
    $stmt->bind_param("si", $status, $user_id);
    $stmt->execute();

    if ($stmt) {
        // Generate QR code image
        $qrCodeData = $user_id; // You can modify the data to include any relevant information
        $qrCodePath = '../assets/img/qr_codes/car_' . $user_id . '.png'; // Specify the path where the QR code image will be saved
        QRcode::png($qrCodeData, $qrCodePath, QR_ECLEVEL_L, 5); // Generate the QR code image and save it to the specified path

        // Update the car entry with the QR code path
        $updateQrCodeQuery = "UPDATE car SET qr_code = ? WHERE user_id = ?";
        $updateQrCodeStmt = $db->prepare($updateQrCodeQuery);
        $updateQrCodeStmt->bind_param("si", $qrCodePath, $user_id);
        $updateQrCodeStmt->execute();

        header('Location: carReg.php?status=Pending');
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
