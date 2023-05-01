<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header('Location: login.php');
}
?>

<!DOCTYPE html>
<html>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Car Registration</title>
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
            max-width: 750px;
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
            width: 100%;
        }

        h1,
        p {
            text-align: center;
        }
    </style>
</head>

<body>
    <form action="action.php" method="post">
        <h1>Car Registration</h1>
        <p>Please fill in this form to register your car.</p>
        <br>
        <hr>

        <br>
        <label for="driver_license"><b>Driver License</b></label>
        <input type="text" placeholder="Format: L12-3456789123" name="driver_license" pattern="^[LNP]\d{2}-\d{9}$" required><br>

        <label for="brand"><b>Car Brand</b></label>
        <input type="text" placeholder="Enter Car Brand" name="brand" required><br>

        <label for="model"><b>Car Model</b></label>
        <input type="text" placeholder="Enter Car Model" name="model" required><br>

        <label for="color"><b>Car Color</b></label>
        <input type="text" placeholder="Enter Car Color" name="color" required><br>

        <label for="seat_count"><b>Seat Count</b></label>
        <input type="number" placeholder="Enter Seat Count" name="seat_count" required><br>

        <label for="car_identity_num"><b>License Plate</b></label>
        <input type="text" placeholder="Format: ABC-1234" name="car_identity_num" pattern="^[A-Za-z]{3}-\d{4}$" required><br>

        <br>
        <hr>
        <br>
        <p>By creating an account and registering your car, you agree to our <a href="#">Terms & Privacy</a>.</p>

        <button type="submit" id="submit" name="submit" class="registerbutton"><strong>Register</strong></button>

        <p>Already have an account? <a href="#">Sign in</a>.</p>

        <br>
    </form>
    <br><br>
    <?php

    if (isset($_POST['submit'])) {
        include('db.php');

        // Get form data
        $driver_license = $_POST["driver_license"];
        $brand = $_POST["brand"];
        $model = $_POST["model"];
        $color = $_POST["color"];
        $seat_count = $_POST["seat_count"];
        $car_identity_num = $_POST["car_identity_num"];

        // Insert user_identification data
        $sql = "INSERT INTO user_identification (user_id, identity_type, user_identity_num) VALUES ('{$_SESSION['user_id']}', 'driver_license', '$driver_license')";
        if ($conn->query($sql) === TRUE) {
            // Insert car data
            $sql = "INSERT INTO car (owner_id, brand, model, color, seat_count) VALUES ('{$_SESSION['user_id']}', '$brand', '$model', '$color', '$seat_count')";
            if ($conn->query($sql) === TRUE) {
                $last_car_id = $conn->insert_id;

                // Insert car_identification data
                $sql = "INSERT INTO car_identification (car_id, car_identity_num) VALUES ('$last_car_id', '$car_identity_num')";
                if ($conn->query($sql) === TRUE) {
                    // Registration success
                    header("Location: success.php");
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
    ?>


</body>

</html>