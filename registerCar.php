<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();
if (!isset($_SESSION['user_id'])) {
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
    input[type=number],
    input[type=date] {
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

    h1,
    p {
      text-align: center;
    }
  </style>
</head>

<body>
  <form action="" method="post">
    <h1>Car Registration</h1>
    <p>Please fill in this form to register your car.</p>
    <br>
    <hr>

    <br>

    <label for="brand"><b>Car Brand</b></label>
    <input type="text" placeholder="Enter Car Brand" name="brand" required><br>

    <label for="model"><b>Car Model</b></label>
    <input type="text" placeholder="Enter Car Model" name="model" required><br>

    <label for="color"><b>Car Color</b></label>
    <input type="text" placeholder="Enter Car Color" name="color" required><br>

    <label for="seat_count"><b>Seat Count</b></label>
    <input type="number" placeholder="Enter Seat Count" name="seat_count" required><br>

    <label for="car_identity_num"><b>License Plate Number</b></label>
    <input type="text" placeholder="Enter Plate Number" name="car_identity_num" required><br>

    <label for="cr_number"><b>LTO Certificate of Registration (CR) Number</b></label>
    <input type="text" placeholder="Enter CR Number" name="cr_number" required><br>

    <label for="or_number"><b>LTO Official Receipt (OR) Number</b></label>
    <input type="text" placeholder="Enter OR number" name="or_number" required><br>

    <label for="reg_exp_date"><b>Registration Expiration Date</b></label>
    <input type="date" placeholder="Enter Registration Expiration Date" name="reg_exp_date" required><br>

    <br>

    <?php

    if (isset($_POST['submit'])) {
      include('db.php');

      // Get form data
      $brand = $_POST["brand"];
      $model = $_POST["model"];
      $color = $_POST["color"];
      $seat_count = $_POST["seat_count"];

      $car_identity_num = $_POST["car_identity_num"];

      $cr_number = !empty($_POST["cr_number"]) ? $_POST["cr_number"] : "";


      $or_number = $_POST["or_number"];
      $reg_exp_date = $_POST["reg_exp_date"];

      // Insert user_identification data
      // Insert car data
      $sql = "INSERT INTO car (owner_id, brand, model, color, seat_count)
    VALUES ('{$_SESSION['user_id']}', '$brand', '$model', '$color', '$seat_count')";
      if ($db->query($sql) === TRUE) {
        $last_car_id = $db->insert_id;

        // Insert car_identification data
        $sql = "INSERT INTO car_identification (car_id, car_identity_num, cr_number, or_number, reg_exp_date) 
        VALUES ('$last_car_id', '$car_identity_num', '$cr_number', '$or_number', '$reg_exp_date')";
        if ($db->query($sql) === TRUE) {
          $mail = new PHPMailer(true);

          $email = $_SESSION['email'];

          try {

            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'carpoolapp01@gmail.com';
            $mail->Password = 'wzspvmmnnxhtbuxd';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('noreply@tarasabay.com', 'TaraSabay PH');
            $mail->addAddress($email);
            $mail->addCustomHeader('X-Priority', '1');
            $mail->addCustomHeader('Importance', 'High');

            $mail->isHTML(true);
            $mail->Subject = 'Email Verification';
            $mail->Body = "
                                    <html>
                                    <head>
                                      <style>
                                        body {
                                          font-family: Arial, sans-serif;
                                          font-size: 16px;
                                          line-height: 1.6;
                                          color: #444;
                                        }
                                        h1 {
                                          font-size: 24px;
                                          font-weight: bold;
                                          color: #333;
                                          margin: 0 0 30px;
                                          text-align: center;
                                        }
                                        p {
                                          margin: 0 0 20px;
                                        }
                                        a {
                                          color: #0072C6;
                                          text-decoration: none;
                                        }
                                        a:hover {
                                          text-decoration: underline;
                                        }
                                        .container {
                                          max-width: 600px;
                                          margin: 0 auto;
                                        }
                                      </style>
                                    </head>
                                    <body>
                                      <div class=\"container\">
                                      <h1>Car Verification Required for Your TaraSabay App</h1>
                                      <p>Dear valued user,</p>
                                      <p>Thank you for choosing TaraSabay to find rides or offer your own. To ensure the security of your account, we need to verify your email address before you can start using the app.</p>
                                      <p>Please go to this certain location: Location$213@12 on May 10, 2023 at 10:00 AM to deliver the necessary documents as verification listed below: </p><br>
                                      <ul>
                                      <li>Original copy of the Certificate of Registration (CR)</li>
                                      <li>Original copy of the Official Receipt (OR)</li>
                                      <li>Photocopy of the driver's valid government-issued ID</li>
                                      <li>Photocopy of the TIN (Tax Identification Number) ID</li>
                                      </ul>
                                      <p>If you have any questions or concerns, please don't hesitate to contact us at support@tarasabay.com.</p>
                                      <p>Best regards,</p>
                                      <p>TaraSabay PH Team</p>
                                      </div>
                                      </body>
                  
                                      </html>";
            $mail->send();
            echo "<div style=\"text-align: center; font-family: 'Poppins', sans-serif; background-color: #FFFFFF; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;\">
            <h5 style=\"color: #4CAF50; font-size: 24px; margin-bottom: 20px;\">Car registration successful!</h5>
            <p style=\"color: #333333; font-size: 16px; margin-bottom: 20px;\">An email has been sent to your email address to verify your car registration.</p>
            <p style=\"color: #333333; font-size: 16px; margin-bottom: 10px;\">Please bring the following documents for car registration:</p>
            <ul style=\"list-style: disc; margin-left: 30px; color: #333333; font-size: 16px;\">
                <li>Original copy of the Certificate of Registration (CR)</li>
                <li>Original copy of the Official Receipt (OR)</li>
                <li>Photocopy of the driver's valid government-issued ID</li>
                <li>Photocopy of the TIN (Tax Identification Number) ID</li>
            </ul>
        </div>";
          } catch (Exception $e) {
            echo '<div style="text-align: center;">
                          <h5 style="color: red">Error sending verification email: </h5>' . $mail->ErrorInfo . '
                        </div>';
          }
        } else {
          echo "Error: " . $sql . "<br>" . $db->error;
        }
      } else {
        echo "Error: " . $sql . "<br>" . $db->error;
      }
      $db->close();
    }
    ?>
    <br>
    <hr>
    <br>
    <p style="font-size: 18px"><a href="registeredCars.php">List of Registered Car Status</a></p><br>
    <br>
    <p style="font-size: 14px">By creating an account and registering your car, you agree to our <a href="#">Terms & Privacy</a>.</p><br>

    <button type="submit" id="submit" name="submit" class="registerbutton"><strong>Register</strong></button>
    <p>Already have an account? <a href="login.php">Sign in</a>.</p>

    <br>
  </form>
  <br><br>


</body>

</html>