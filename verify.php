<?php
include('db.php');

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Find the record in the `user_temp` table
    $sql = "SELECT * FROM user_temp WHERE token=?";
    $stmt1 = $db->prepare($sql);
    $stmt1->bind_param("s", $token);
    $stmt1->execute();
    $result = $stmt1->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Move the user data to the `user_profile` table
        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $city_id = $row['city_id'];
        $email = $row['email'];
        $pswd = $row['password'];
        $id = $row['identity_type'];
        $idnum = $row['user_identity_num'];
        $identity_expiration = $row['identity_expiration'];
        $referral_code = $row['referral_code'];
        $role = "Passenger";

        $sql_insert_profile = "INSERT INTO user_profile (first_name, last_name, city_id, email, password, role) VALUES (?,?,?,?,?,?)";
        $stmt2 = $db->prepare($sql_insert_profile);
        $stmt2->bind_param("ssssss", $first_name, $last_name, $city_id, $email, $pswd, $role);
        $stmt2->execute();
        if ($stmt2->error) {
            die("Error executing profile query: " . $stmt2->error);
        }

        $user_id = $stmt2->insert_id;

        $stmt2->close();

        $sql_insert_identification = "INSERT INTO user_identification (user_id, identity_type, user_identity_num, identity_expiration) VALUES (?,?,?,?)";
        $stmt3 = $db->prepare($sql_insert_identification);
        $stmt3->bind_param("isss", $user_id, $id, $idnum, $identity_expiration);
        $stmt3->execute();
        if ($stmt3->error) {
            die("Error executing identification query: " . $stmt3->error);
        }

        $stmt3->close();

        $sql_delete = "DELETE FROM user_temp WHERE token=?";
        $stmt4 = $db->prepare($sql_delete);
        $stmt4->bind_param("s", $token);
        $stmt4->execute();
        if ($stmt4->error) {
            die("Error deleting token: " . $stmt4->error);
        }

        $stmt4->close();

        // Add 10 free tickets to the user
        $sql_update_tickets = "UPDATE user_profile SET ticket_balance = ticket_balance + 10 WHERE user_id = ?";
        $stmt5 = $db->prepare($sql_update_tickets);
        $stmt5->bind_param("i", $user_id);
        $stmt5->execute();
        if ($stmt5->error) {
            die("Error updating ticket balance: " . $stmt5->error);
        }

        $stmt5->close();

        // Check if the referral code exists in the database
        $sql_check_referral = "SELECT COUNT(*) FROM code WHERE referral_code = ?";
        $stmt6 = $db->prepare($sql_check_referral);
        $stmt6->bind_param("s", $referral_code);
        $stmt6->execute();
        $stmt6->bind_result($referralCount);
        $stmt6->fetch();
        $stmt6->close();

        if ($referralCount > 0) {
            // Fetch the referrer_id
            $sql_fetch_referrer = "SELECT referrer_id FROM code WHERE referral_code = ? AND refer_status = 'Unused'";
            $stmt9 = $db->prepare($sql_fetch_referrer);
            $stmt9->bind_param("s", $referral_code);
            $stmt9->execute();
            $stmt9->bind_result($referrer_id);
            $stmt9->fetch();
            $stmt9->close();

            // If referral code exists, add 50 additional tickets to the referrer
            $sql_update_tickets = "UPDATE user_profile SET ticket_balance = ticket_balance + 50 WHERE user_id = ?";
            $stmt7 = $db->prepare($sql_update_tickets);
            $stmt7->bind_param("i", $referrer_id);
            $stmt7->execute();
            if ($stmt7->error) {
                die("Error updating ticket balance for referrer: " . $stmt7->error);
            }
            $stmt7->close();

            // Update the refer_status column in the code table
            $sql_update_refer_status = "UPDATE code SET refer_status = 'Used', referred_id=? WHERE referral_code = ? AND refer_status = 'Unused'";
            $stmt8 = $db->prepare($sql_update_refer_status);
            $stmt8->bind_param("is", $user_id, $referral_code);
            $stmt8->execute();
            if ($stmt8->error) {
                die("Error updating refer_status: " . $stmt8->error);
            }
            $stmt8->close();
        }



        session_start();
        $_SESSION['user_id'] = $user_id;

        header("Location: http://localhost:8080/tarasabay-localhost/login.php");
        exit;
    } else {
        header("Location: http://localhost:8080/tarasabay-localhost/notif/notif-failed.php?token=$token");
        exit;
    }
}
