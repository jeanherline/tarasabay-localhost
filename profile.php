<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
}

include('db.php');

$userid = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT * FROM user_profile WHERE user_id = ?");
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $id = $row['user_id'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $email = $row['email'];
    $pswd = $row['pswd'];
}

$stmt = $db->prepare("SELECT * FROM user_identification WHERE user_id = ?");
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $idtype = $row['identity_type'];
    $validid = $row['user_identity_num'];
}

if (isset($_POST['submit'])) {
    $pswd1 = $db->real_escape_string($_POST['pswd1']);
    $pswd2 = password_hash($_POST['pswd2'], PASSWORD_DEFAULT);

    $stmt = $db->prepare("SELECT * FROM user_profile WHERE user_id = ?");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($pswd1, $user['pswd'])) {
            $stmt = $db->prepare("UPDATE user_profile SET pswd = ? WHERE user_id = ?");
            $stmt->bind_param("ss", $pswd2, $userid);
            $result = $stmt->execute();

            if ($result) {
                echo '<div style="text-align: center;"><h5 style="color: green">Password Changed</h5></div>';
            } else {
                echo '<div style="text-align: center;"><h5 style="color: red">Password Change Failed</h5></div>';
            }
        } else {
            echo '<div style="text-align: center;"><h5 style="color: red">Current Password is Incorrect</h5></div>';
        }
    }
}

?>

<!DOCTYPE html>
<html>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>User Profile</title>
<link rel="icon" href="assets/img/logo.png" type="images" />

<head>
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

        *,
        *::before,
        *::after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body,
        input {
            font-family: "Poppins", sans-serif;
            margin: 0 auto;
            max-width: 2000px;
            max-height: 2000px;
            margin-top: 50px;
            margin-bottom: 5px;
            padding: 0px 15px 0 15px;
            background-color: #EAAA00;
        }

        form {
            background-color: #FFFFFF;
            padding: 20px;
            border-radius: 10px;
        }

        input[type=text],
        input[type=password],
        input[type=number] {
            width: 97%;
            padding: 10px;
            margin: 5px 0 22px 0;
            display: inline-block;
            border: none;
            background: #F5F5F5;
        }

        hr {
            border: 1px solid #e6e6e6;
            margin-bottom: 5px;
        }

        .registerbutton {
            background-color: black;
            color: white;
            padding: 15px 20px;
            margin: 10px 0px;
            border: none;
            cursor: pointer;
            width: 70%;
            font-size: 18px;
            margin-left: 15%;
        }

        .logout {
            background-color: red;
            color: white;
            padding: 15px 20px;
            margin: 10px 0px;
            border: none;
            cursor: pointer;
            width: 30%;
            font-size: 18px;
            margin-left: 35%;
        }

        button a {
            color: white;
            text-decoration: none;
        }

        h1,
        p {
            text-align: center;
        }
    </style>
</head>

<body>
    <form action="" method="post">
        <h1>User Profile</h1>
        <p>Account Credentials</p>

        <br>
        <hr>
        <?php
        include('db.php');
        $userid = $_SESSION['user_id'];

        $stmt = $db->prepare("SELECT * FROM user_profile WHERE user_id = ?");
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $id = $row['user_id'];
            $first_name = $row['first_name'];
            $last_name = $row['last_name'];
            $email = $row['email'];
            $_SESSION['email'] = $row['email'];
            $pswd = $row['pswd'];
        }

        $stmt = $db->prepare("SELECT * FROM user_identification WHERE user_id = ?");
        $stmt->bind_param("s", $userid);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $idtype = $row['identity_type'];
            $_SESSION['idtype'] = $row['identity_type'];
            $validid = $row['user_identity_num'];
        }
        ?>
        <label for="first_name"><b>First Name</b></label>
        <input type="text" value="<?php echo $first_name ?>" name="first_name" disabled><br>

        <label for="last_name"><b>Last Name</b></label>
        <input type="text" value="<?php echo $last_name ?>" name="last_name" disabled><br>

        <label for="email"><b>Email</b></label>
        <input type="text" value="<?php echo $email ?>" name="email" disabled><br>

        <br>
        <label for="pswd1"><b>Change Password</b></label>
        <input type="password" placeholder="Type current password" name="pswd1"><br>

        <label for="pswd2"><b>Retype Password</b></label>
        <input type="password" placeholder="Confirm Password" name="pswd2"><br>


        <label for="id"><b>Provided Valid ID</b></label>
        <input type="text" value="<?php echo $idtype ?>" name="id" disabled><br>

        <label for="idnum"><b>Valid ID Number</b></label>
        <input type="text" value="<?php echo $validid ?>" name="idnum" disabled><br>

        <br>



        <hr>
        <br>
        <?php
        if ($idtype = "Driver's License" or $_SESSION['role'] == "driver" or $_SESSION['role'] == "user") {
        ?>
            <p style="font-size: 14px">Since you provided a Driver's License, You may:</p><br>
            <p style="font-size: 18px"><a href="registerCar.php">Apply as a Driver</a></p><br>
        <?php
        }
        ?>


        <button type="submit" id="submit" name="submit" class="registerbutton"><strong>Save</strong></button>
        <button type="logout" id="logout" name="logout" class="logout"><strong><a href="logout.php">Logout</a></strong></button>

        <br>
    </form>
    <br><br>

</body>

</html>