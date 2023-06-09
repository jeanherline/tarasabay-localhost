<?php
include('db.php');

if (isset($_GET['cico_id']) && isset($_GET['amount'])) {
    $walletId = $_GET['cico_id'];
    $amount = $_GET['amount'];

    // Retrieve the user_id and ticket_balance associated with the wallet_id
    $stmt = $db->prepare("SELECT user_id, ticket_balance FROM cico WHERE cico_id = ?");
    $stmt->bind_param("i", $walletId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $userId = $row['user_id'];
    $ticketBalance = $row['ticket_balance'];

    $status = "Declined";

    // Update the status of cico table
    $stmt = $db->prepare("UPDATE cico SET status = ? WHERE cico_id = ?");
    $stmt->bind_param("si", $status, $walletId);
    $stmt->execute();

    if ($result) {
        header('Location: user/cash-out-manage.php');
        exit();
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
