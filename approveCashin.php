<?php
include('db.php');

if (isset($_GET['wallet_id']) && isset($_GET['amount'])) {
    $walletId = $_GET['wallet_id'];
    $amount = $_GET['amount'];
    $confee = "";

    // Retrieve the user_id associated with the wallet_id
    $stmt = $db->prepare("SELECT user_id FROM cico WHERE wallet_id = ?");
    $stmt->bind_param("i", $walletId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $userId = $row['user_id'];

    $status = "Approved";

    // Update the status of cico table
    $stmt = $db->prepare("UPDATE cico SET status = ?, convenience_fee = ? WHERE wallet_id = ?");
    $stmt->bind_param("ssi", $status, $confee, $walletId);
    $stmt->execute();

    // Update the acc_balance of user_profile table
    $stmt = $db->prepare("UPDATE user_profile SET acc_balance = acc_balance + ? WHERE user_id = ?");
    $stmt->bind_param("di", $amount, $userId);
    $result = $stmt->execute();

    if ($result) {
        header('Location: user/cash-in-manage.php');
    } else {
        echo '<div style="text-align: center;"><h5 style="color: red">Failed</h5></div>';
    }
}
?>
