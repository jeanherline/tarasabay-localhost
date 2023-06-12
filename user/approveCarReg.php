<?php
include('../db.php');
include('../phpqrcode/qrlib.php'); // Include the QR code library

if (isset($_GET['user_id']) && isset($_GET['car_id']) && isset($_GET['status'])) {
    $user_id = $_GET['user_id'];
    $car_id = $_GET['car_id'];
    $status = $_GET['status'];

    $newStatus = "Active";

    $stmt = $db->prepare("UPDATE car SET car_status = ? WHERE user_id = ? AND car_id = ?");
    $stmt->bind_param("sii", $newStatus, $user_id, $car_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Generate QR code image
        $qrCodeData = $car_id; // You can modify the data to include any relevant information
        $qrCodePath = "../assets/img/qr_codes/" . $car_id . '.png'; // Specify the path where the QR code image will be saved
        QRcode::png($qrCodeData, $qrCodePath, QR_ECLEVEL_L, 5); // Generate the QR code image and save it to the specified path

        $dbcar = $car_id . ".png";
        // Update the car entry with the QR code path
        $updateQrCodeQuery = "UPDATE car SET qr_code = ? WHERE user_id = ? AND car_id = ?";
        $updateQrCodeStmt = $db->prepare($updateQrCodeQuery);
        $updateQrCodeStmt->bind_param("sii", $dbcar, $user_id, $car_id);
        $updateQrCodeStmt->execute();

        header("Location: carReg.php?status=$status");
        exit();
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
?>
