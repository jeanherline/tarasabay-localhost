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

        $first_name = $row['first_name'];
        $last_name = $row['last_name'];
        $city_id = $row['city_id'];
        $email = $row['email'];
        $pswd = $row['password'];
        $id = $row['identity_type'];
        $idnum = $row['user_identity_num'];
        $identity_expiration = $row['identity_expiration'];
        $role = "City Admin";

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

        session_start();
        $_SESSION['user_id'] = $user_id;

        header("Location: http://localhost/tarasabay-localhost/index.php");
        exit;
    } else {
        header("Location: http://localhost/tarasabay-localhost/notif/notif-failed.php?token=$token");
        exit;
    }
}
