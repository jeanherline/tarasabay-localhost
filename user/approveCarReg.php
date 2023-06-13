<?php
include('../db.php');
include('../phpqrcode/qrlib.php');

if (isset($_GET['user_id']) && isset($_GET['car_id']) && isset($_GET['status'])) {
    $user_id = $_GET['user_id'];
    $car_id = $_GET['car_id'];
    $status = $_GET['status'];

    $newStatus = "Active";

    $stmt = $db->prepare("UPDATE car SET car_status = ? WHERE user_id = ? AND car_id = ?");
    $stmt->bind_param("sii", $newStatus, $user_id, $car_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Check if the user's role is not "Driver"
        $checkRoleQuery = "SELECT role FROM user_profile WHERE user_id = ?";
        $checkRoleStmt = $db->prepare($checkRoleQuery);
        $checkRoleStmt->bind_param("i", $user_id);
        $checkRoleStmt->execute();
        $checkRoleResult = $checkRoleStmt->get_result();

        if ($checkRoleRow = $checkRoleResult->fetch_assoc()) {
            $role = $checkRoleRow['role'];
            if ($role !== "Driver") {
                // Update the role to "Driver"
                $updateRoleQuery = "UPDATE user_profile SET role = 'Driver' WHERE user_id = ?";
                $updateRoleStmt = $db->prepare($updateRoleQuery);
                $updateRoleStmt->bind_param("i", $user_id);
                $updateRoleStmt->execute();
            }
        }

        // Generate QR code image
        $qrCodeData = $car_id;
        $qrCodePath = "../assets/img/qr_codes/" . $car_id . '.png';
        QRcode::png($qrCodeData, $qrCodePath, QR_ECLEVEL_L, 5);

        $dbcar = $car_id . ".png";
        $updateQrCodeQuery = "UPDATE car SET qr_code = ? WHERE user_id = ? AND car_id = ?";
        $updateQrCodeStmt = $db->prepare($updateQrCodeQuery);
        $updateQrCodeStmt->bind_param("sii", $dbcar, $user_id, $car_id);
        $updateQrCodeStmt->execute();

        // Check if it is the first car registered by the driver
        $checkFirstCarQuery = "SELECT COUNT(*) AS car_count FROM car WHERE user_id = ?";
        $checkFirstCarStmt = $db->prepare($checkFirstCarQuery);
        $checkFirstCarStmt->bind_param("i", $user_id);
        $checkFirstCarStmt->execute();
        $checkFirstCarResult = $checkFirstCarStmt->get_result();

        if ($checkFirstCarRow = $checkFirstCarResult->fetch_assoc()) {
            $carCount = $checkFirstCarRow['car_count'];
            if ($carCount === 1) {
                // Add 40 tickets
                $addTicketsQuery = "UPDATE user_profile SET ticket_balance = ticket_balance + 40 WHERE user_id = ?";
                $addTicketsStmt = $db->prepare($addTicketsQuery);
                $addTicketsStmt->bind_param("i", $user_id);
                $addTicketsStmt->execute();
            }
        }

        // Update driver_stat to "Active"
        $updateDriverStatQuery = "UPDATE driver_identification SET driver_stat = 'Active' WHERE user_id = ?";
        $updateDriverStatStmt = $db->prepare($updateDriverStatQuery);
        $updateDriverStatStmt->bind_param("i", $user_id);
        $updateDriverStatStmt->execute();

        header("Location: carReg.php?status=$status");
        exit();
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
?>
