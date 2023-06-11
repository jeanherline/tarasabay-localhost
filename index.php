<?php
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include('db.php');
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>TaraSabay</title>
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
  <link rel="icon" href="assets/img/logo.png" type="images" />

  <style>
    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800&display=swap");

    .input-wrap {
      position: relative;
      height: 35px;
      margin-bottom: 2rem;
    }

    .input-field {
      font-family: "Poppins", sans-serif;
      position: absolute;
      width: 100%;
      height: 100%;
      background: none;
      border: none;
      outline: none;
      padding: 0;
      font-size: 0.95rem;
      transition: 0.4s;
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
    }

    .input-wrap:focus {
      color: #000 !important;
    }

    .input-field::-ms-expand {
      display: none;
    }

    .input-field option {
      color: #333;
    }

    .input-field:focus {
      border-bottom: 1px solid #0077cc;
    }
  </style>

</head>

<body>
  <main>
    <div class="box">
      <div class="inner-box">
        <div class="forms-wrap">
          <form action="" method="POST" autocomplete="off" class="sign-in-form">
            <div class="logo">
              <img src="assets/img/logo.png" alt="TaraSabay" />
              <h4>TaraSabay</h4>
            </div>

            <div class="heading">
              <h2>Welcome Back</h2>
              <h6>Not registred yet?</h6>
              <a href="#" class="toggle">Sign up</a>
            </div>

            <div class="actual-form">
              <div class="input-wrap">
                <input type="text" id="email" name="email" minlength="4" class="input-field" autocomplete="off" required />
                <label id="email">Email</label>
              </div>

              <div class="input-wrap">
                <input type="password" id="pswd" name="pswd" minlength="4" class="input-field" autocomplete="off" required />
                <label id="pswd">Password</label>
              </div>
              <?php
              if (isset($_SESSION['user_id'])) {
                header('Location: index.php');
              }

              if (isset($_POST['sign-in'])) {
                require 'db.php';

                $email = trim($_POST['email']);
                $pswd = trim($_POST['pswd']);

                // Check if email exists in the database
                $stmt = $db->prepare("SELECT * FROM user_profile WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                  $user = $result->fetch_assoc();

                  // Check if password is correct
                  if (password_verify($pswd, $user['password'])) {
                    $_SESSION['user_id'] = $user['user_id'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['city_id'] = $user['city_id'];

                    $stmt = $db->prepare("SELECT * FROM city WHERE city_id = ?");
                    $stmt->bind_param("i", $user['city_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows == 1) {
                      $city = $result->fetch_assoc();
                      $_SESSION['city_name'] = $city['city_name'];
                    }

                    $stmt = $db->prepare("SELECT phone FROM emergency WHERE user_id = ?");
                    $stmt->bind_param("i", $user['user_id']);
                    $stmt->execute();
                    $emergencyResult = $stmt->get_result();
                    if ($emergencyResult->num_rows == 1) {
                      $emergency = $emergencyResult->fetch_assoc();
                      $_SESSION['emergency_phone'] = $emergency['phone'];
                    }

                    $stmt = $db->prepare("SELECT * FROM user_identification WHERE user_id = ?");
                    $stmt->bind_param("i", $user['user_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows == 1) {
                      $id = $result->fetch_assoc();
                      $_SESSION['identity_type'] = $id['identity_type'];
                    }

                    // Check if driver's license is expired
                    $stmt = $db->prepare("SELECT * FROM driver_identification WHERE user_id = ?");
                    $stmt->bind_param("i", $user['user_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows == 1) {
                      $driver = $result->fetch_assoc();
                      $licenseExpirationDate = $driver['license_expiration'];
                      $currentDate = date('Y-m-d');
                      if ($licenseExpirationDate < $currentDate) {
                        $stmt = $db->prepare("UPDATE driver_identification SET driver_stat = 'Expired' WHERE user_id = ?");
                        $stmt->bind_param("i", $user['user_id']);
                        $stmt->execute();

                        // Mark all active routes of the driver as Cancelled with cancellation reason "Expired Driver's License"
                        $stmt = $db->prepare("UPDATE route SET route_status = 'Cancelled', cancellation_reason = 'Expired Driver\'s License' WHERE car_id IN (SELECT car_id FROM car WHERE user_id = ?) AND route_status = 'Active'");
                        $stmt->bind_param("i", $user['user_id']);
                        $stmt->execute();

                        // Cancel all associated seats
                        $stmt = $db->prepare("UPDATE seat SET seat_status = 'Cancelled' WHERE route_id IN (SELECT route_id FROM route WHERE car_id IN (SELECT car_id FROM car WHERE user_id = ?))");
                        $stmt->bind_param("i", $user['user_id']);
                        $stmt->execute();
                      }
                    }

                    // Check if car license plate is expired
                    $stmt = $db->prepare("SELECT * FROM car_identification WHERE car_id IN (SELECT car_id FROM car WHERE user_id = ?)");
                    $stmt->bind_param("i", $user['user_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                      while ($row = $result->fetch_assoc()) {
                        $plateExpirationDate = $row['plate_expiration'];
                        $currentDate = date('Y-m-d');
                        if ($plateExpirationDate < $currentDate) {
                          $stmt = $db->prepare("UPDATE car SET car_status = 'Expired' WHERE car_id = ?");
                          $stmt->bind_param("i", $row['car_id']);
                          $stmt->execute();

                          // Mark all active routes of the car as Cancelled with cancellation reason "Expired Car License Plate"
                          $stmt = $db->prepare("UPDATE route SET route_status = 'Cancelled', cancellation_reason = 'Expired Car License Plate' WHERE car_id = ? AND route_status = 'Active'");
                          $stmt->bind_param("i", $row['car_id']);
                          $stmt->execute();

                          // Cancel all associated seats
                          $stmt = $db->prepare("UPDATE seat SET seat_status = 'Cancelled' WHERE route_id IN (SELECT route_id FROM route WHERE car_id = ?)");
                          $stmt->bind_param("i", $row['car_id']);
                          $stmt->execute();
                        }
                      }
                    }

                    header('Location: user/dashboard.php');
                    exit();
                  } else {
                    echo '<div style="text-align: center;">
                  <h5 style="color: red">Invalid Password</h5>
              </div><br>';
                  }
                } else {
                  echo '<div style="text-align: center;">
              <h5 style="color: red">Invalid Email</h5>
          </div><br>';
                }
              }


              if (isset($_POST['register'])) {
                $fname = $db->real_escape_string($_POST['fname']);
                $lname = $db->real_escape_string($_POST['lname']);
                $email = $db->real_escape_string($_POST['email']);
                $pswd = $db->real_escape_string(password_hash($_POST['pswd'], PASSWORD_DEFAULT));
                $city = $db->real_escape_string($_POST['city']);
                $id = $db->real_escape_string($_POST['id']);
                $idnum = $db->real_escape_string($_POST['idnum']);
                $identity_expiration = $db->real_escape_string($_POST['expiration']);
                $referralCode = $db->real_escape_string($_POST['referral_code']);
                $token = bin2hex(random_bytes(16));

                // Check if the email already exists in the user_profile table
                $sql_email_check = "SELECT * FROM user_profile WHERE email='$email'";
                $result = $db->query($sql_email_check);

                if ($result->num_rows > 0) {
                  // Email already exists in the user_profile table
                  echo '<div style="text-align: center;">
                            <h5 style="color: red">Email address already exists. Please use a different email or log in.</h5>
                          </div><br>';
                } else {
                  // Check if the referral code exists in the database
                  $sql_referral_check = "SELECT * FROM code WHERE referral_code='$referralCode' AND refer_status = 'Unused'";
                  $result = $db->query($sql_referral_check);

                  if ($result->num_rows > 0) {
                    // Referral code exists
                    // Insert the user data into the `user_temp` table
                    $sql = "INSERT INTO user_temp (first_name, last_name, email, password, token, city_id, identity_type, user_identity_num, identity_expiration, referral_code) 
                                VALUES ('$fname', '$lname', '$email', '$pswd', '$token', '$city', '$id', '$idnum', '$identity_expiration', '$referralCode')";
                  } else {
                    // Referral code doesn't exist
                    // Insert the user data into the `user_temp` table with a null referral code
                    $sql = "INSERT INTO user_temp (first_name, last_name, email, password, token, city_id, identity_type, user_identity_num, identity_expiration) 
                                VALUES ('$fname', '$lname', '$email', '$pswd', '$token', '$city', '$id', '$idnum', '$identity_expiration')";
                  }

                  if ($db->query($sql) === TRUE) {
                    $mail = new PHPMailer(true);

                    try {
                      // Replace the placeholders with your actual Gmail email and password
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
                                        <h1>Verification Required for Your TaraSabay App Registration</h1>
                                        <p>Dear valued user,</p>
                                        <p>Thank you for choosing TaraSabay to find rides or offer your own. To ensure the security of your account, we need to verify your email address before you can start using the app.</p>
                                        <p>Please click on the button below to verify your email address and finalize your registration:</p>
                                        <p><a href=\"http://localhost:8080/tarasabay-localhost/verify.php?token=" . urlencode($token) . " \" style=\"display:inline-block; padding: 10px 20px; background-color: #0072C6; color: #fff; font-weight: bold; text-decoration: none;\">Verify Your Email Address</a></p>
                                        <p>If you have any questions or concerns, please don't hesitate to contact us at support@tarasabay.com.</p>
                                        <p>Best regards,</p>
                                        <p>TaraSabay PH Team</p>
                                    </div>
                                </body>
                                </html>";

                      $mail->send();
                      echo '<div style="text-align: center;">
                                    <h5 style="color: green">Check email to verify registration</h5><br>
                                  </div>';
                    } catch (Exception $e) {
                      echo '<div style="text-align: center;">
                                    <h5 style="color: red">Error sending verification email: </h5>' . $mail->ErrorInfo . '
                                  </div>';
                    }
                  } else {
                    echo '<div style="text-align: center;">
                                <h5 style="color: red">Error:</h5>
                              </div>
                              <div style="text-align: center;">' . $sql . "<br>" . $db->error . '</div>';
                  }
                }
              }
              ?>


              <input type="submit" id="sign-in" name="sign-in" value="Sign In" class="sign-btn" />

              <p class="text">
                Forgotten your password or you login datails?
                <a href="#">Get help</a> signing in
              </p>
            </div>
          </form>

          <form action="" method="POST" autocomplete="off" class="sign-up-form">


            <div class="heading">
              <div class="logo">
                <img src="assets/img/logo.png" alt="easyclass" />
                <h2>Get Started</h2>
              </div>
              <h6>Already have an account?</h6>
              <a href="#" class="toggle">Sign in</a>
            </div>
            <br>

            <div class="actual-form">
              <div class="input-wrap">
                <input type="text" id="fname" name="fname" class="input-field" autocomplete="off" required />
                <label id="fname">First Name</label>
              </div>

              <div class="input-wrap">
                <input type="text" id="lname" name="lname" class="input-field" autocomplete="off" required />
                <label id="lname">Last Name</label>
              </div>

              <div class="input-wrap">
                <input type="email" id="email" name="email" class="input-field" autocomplete="off" required />
                <label id="email">Email</label>
              </div>

              <div class="input-wrap">
                <input type="password" id="pswd" name="pswd" minlength="4" class="input-field" autocomplete="off" required />
                <label id="pswd">Password</label>
              </div>
              <div class="input-wrap">
                <select class="input-field" id="city" name="city" required>
                  <option value="" class="disabled-option" disabled selected>Select a City</option>
                  <?php
                  $sql = "SELECT * FROM city ORDER BY city_name ASC";
                  $result = $db->query($sql);

                  if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                      $city_id = $row['city_id'];
                      $city_name = $row['city_name'];
                      $province = $row['province'];
                      $region = $row['region'];
                  ?>
                      <option value="<?php echo $city_id; ?>"><?php echo $city_name; ?></option>
                  <?php
                    }
                  } else {
                    echo "No cities found.";
                  }
                  ?>

                </select>
              </div>
              <div class="input-wrap">
                <select class="input-field" id="id" name="id" required>
                  <option value="" style="color: #bbb !important;" disabled selected>Select a Valid ID</option>
                  <option value="Passport">Passport</option>
                  <option value="Driver's License">Driver's License</option>
                  <option value="National ID">National ID</option>
                  <option value="SSS">SSS (Social Security System ID)</option>
                  <option value="GSIS">GSIS (Government Service Insurance System ID)</option>
                  <option value="Postal ID">Philippine Postal ID</option>
                  <option value="Voter's ID">Voter's ID</option>
                  <option value="PRC">PRC (Professional Regulation Commission ID)</option>
                  <option value="UMID">UMID (Unified Multi-Purpose ID)</option>
                  <option value="TIN">TIN (Tax Identification Number ID)</option>
                  <option value="PhilHealth">PhilHealth ID</option>
                  <option value="SeniorCitizen">Senior Citizen ID</option>
                  <option value="PWD">PWD (Person with Disability) ID</option>
                  <option value="OFW">OFW (Overseas Filipino Worker) ID</option>
                  <option value="AFP">AFP (Armed Forces of the Philippines) ID</option>
                  <option value="IBP">IBP (Integrated Bar of the Philippines) ID</option>
                </select>
              </div>

              <div class="input-wrap">
                <input type="text" id="idnum" name="idnum" class="input-field" autocomplete="off" required />
                <label id="idnum">Valid ID Number</label>
              </div>
              <a href="" style="  color: #151111;
                                    text-decoration: none;
                                    font-size: 0.75rem;
                                    font-weight: 500;">
                Valid ID Expiration</a>
              <div class="input-wrap">
                <input type="date" id="expiration" name="expiration" class="input-field" autocomplete="off" required />
              </div>


              <div class="input-wrap">
                <input type="text" id="referral_code" name="referral_code" class="input-field" autocomplete="off" />
                <label id="referral_code">Referral Code (Optional)</label>
              </div>

              <p class="text" style="font-size: 11px; color: green; text-align: center; padding-bottom:5%">
                You may apply as a driver if you input a valid <strong>driver's license.</strong>
              </p>

              <div class="input-wrap">
                <p class="text" style="color:#000">
                  <input type="checkbox" id="agreementCheckbox" required !important>
                  By signing up, I agree to the Terms of Services and Privacy Policy
                </p>
              </div>

              <input type="submit" id="register" name="register" value="Sign Up" class="sign-btn" />

            </div>
          </form>
        </div>

        <div class="carousel">
          <div class="images-wrapper">
            <img src="assets/img/image1.jpg" class="image img-1 show" alt="" width="" />
          </div>
        </div>
      </div>
    </div>
  </main>


  <!-- Javascript file -->
  <script src="assets/js/app.js"></script>
</body>

</html>