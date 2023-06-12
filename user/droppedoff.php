<?php
include('../db.php');
if (isset($_GET['user_id']) && isset($_GET['seat_id']) && isset($_GET['list'])) {
    $user_id = $_GET['user_id'];
    $seat_id = $_GET['seat_id'];
    $list = $_GET['list'];

    // Update the booking status to 'Dropped-off'
    $updateBookingSql = "UPDATE booking SET booking_status = 'Dropped-off' WHERE user_id = ? AND seat_id = ?";
    $stmt = $db->prepare($updateBookingSql);
    $stmt->bind_param("ii", $user_id, $seat_id);
    $stmt->execute();

    // Retrieve the fare amount and route information
    $getSeatInfoSql = "SELECT s.fare, r.car_id, u.first_name, u.last_name 
                       FROM seat s 
                       INNER JOIN route r ON s.route_id = r.route_id 
                       INNER JOIN car c ON r.car_id = c.car_id
                       INNER JOIN user_profile u ON c.user_id = u.user_id
                       WHERE s.seat_id = ?";
    $stmt = $db->prepare($getSeatInfoSql);
    $stmt->bind_param("i", $seat_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $fare = $row['fare'];
    $car_id = $row['car_id'];
    $driverName = $row['first_name'] . ' ' . $row['last_name'];

    // Update the ticket balance of the driver
    $updateDriverSql = "UPDATE user_profile SET ticket_balance = ticket_balance + ? WHERE user_id = 
                        (SELECT user_id FROM car WHERE car_id = ?)";
    $stmt = $db->prepare($updateDriverSql);
    $stmt->bind_param("ii", $fare, $car_id);
    $stmt->execute();

    // Check if the booking exists
    $checkBookingSql = "SELECT booking_id FROM booking WHERE user_id = ? AND seat_id = ?";
    $stmt = $db->prepare($checkBookingSql);
    $stmt->bind_param("ii", $user_id, $seat_id);
    $stmt->execute();
    $bookingResult = $stmt->get_result();
    $bookingRow = $bookingResult->fetch_assoc();
    $bookingId = $bookingRow['booking_id'];

    if ($bookingId) {
        // Insert payment details into the payment table
        $insertPaymentSql = "INSERT INTO payment (booking_id, ticket_amount, payment_to, payment_status, date) 
                             VALUES (?, ?, ?, 'Paid', CURDATE())";
        $stmt = $db->prepare($insertPaymentSql);
        $stmt->bind_param("ids", $bookingId, $fare, $driverName);
        $stmt->execute();
    }
    header("Location: $list");
    exit();
}
