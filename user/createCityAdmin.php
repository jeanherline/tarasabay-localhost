<?php
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
}

include('../db.php');

$userid = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<title>Create New City Admin</title>

<link rel="icon" href="../assets/img/logo.png" type="images" />
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php include('vendor/inc/head.php'); ?>

<body id="page-top">
    <!--Start Navigation Bar-->
    <?php include("vendor/inc/nav.php"); ?>
    <!--Navigation Bar-->

    <div id="wrapper">

        <!-- Sidebar -->
        <?php include("vendor/inc/sidebar.php"); ?>
        <!--End Sidebar-->
        <div id="content-wrapper">

            <div class="container-fluid">
                <?php if (isset($succ)) { ?>
                    <!--This code for injecting an alert-->
                    <script>
                        setTimeout(function() {
                                swal("Success!", "<?php echo $succ; ?>!", "success");
                            },
                            100);
                    </script>

                <?php } ?>
                <?php if (isset($err)) { ?>
                    <!--This code for injecting an alert-->
                    <script>
                        setTimeout(function() {
                                swal("Failed!", "<?php echo $err; ?>!", "Failed");
                            },
                            100);
                    </script>

                <?php } ?>

                <!-- Breadcrumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">City Admin</a>
                    </li>
                    <li class="breadcrumb-item active">Create New City Admin</li>
                </ol>
                <hr>
                <!-- Icon Cards-->
                <div class="row">
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card text-white" style="background-color: #EAAA00;">
                            <div class="card-body">
                                <div class="card-body-icon">
                                    <i class="fas fa-user-tie"></i>
                                </div>
                                <?php
                                // Code for counting the ticket balance by user ID
                                $result = $db->query("SELECT COUNT(*) FROM user_profile WHERE role = 'City Admin'");
                                $cityadmin = $result->fetch_row()[0];
                                ?>
                                <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><i class="fa fa-ticket"></i>&nbsp;&nbsp;<?php echo $cityadmin; ?></span> City Admins</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <b>Manage: </b>Create Account
                    </div>
                    <div class="card-body">
                        <form method="POST" autocomplete="on" enctype="multipart/form-data">
                            <div style="color: gray;">
                                <em>Personal Information</em><br><br>
                            </div>
                            <div class="form-group">
                                <label for="fname">First Name</label>
                                <input type="text" class="form-control" placeholder="Enter First Name" name="fname"><br>
                            </div>

                            <div class="form-group">
                                <label for="lname">Last Name</label>
                                <input type="text" class="form-control" placeholder="Enter Last Name" name="lname"><br>
                            </div>

                            <div class="form-group">
                                <label for="city_id">City Designation</label>
                                <select class="form-control" name="city_id">
                                    <option value="" selected disabled>Select City</option>
                                    <?php
                                    $stmt = $db->prepare("SELECT city_id, city_name FROM city");
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()) {
                                        $cityId = $row['city_id'];
                                        $cityName = $row['city_name'];
                                    ?>
                                        <option value="<?php echo $cityId; ?>"><?php echo $cityName; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <br>
                            </div>

                            <div class="form-group">
                                <label for="id">Select Valid ID</label>
                                <select class="form-control" id="id" name="id" required>
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
                            <br>

                            <div class="form-group">
                                <label for="idnum">Valid ID Number</label>
                                <input type="text" id="idnum" name="idnum" class="form-control" placeholder="Enter Valid ID Number" autocomplete="off" required />
                            </div>
                            <br>

                            <div class="form-group">
                                <label for="expiration">Valid ID Expiration Date</label>
                                <input type="date" class="form-control" id="expiration" name="expiration" class="input-field" autocomplete="off" required />
                            </div>
                            <br>

                            <div style="color: gray;">
                                <em>Create Account</em><br><br>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" class="form-control" placeholder="Enter Email Address" id="email" name="email">
                                <br>
                            </div>

                            <div class="form-group">
                                <label for="password">Create Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" placeholder="Enter Created Password" id="password" name="password" required>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary show-password" type="button" onclick="togglePasswordVisibility('password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <script>
                                function togglePasswordVisibility(inputId) {
                                    var input = document.getElementById(inputId);
                                    var button = document.querySelector('[onclick="togglePasswordVisibility(\'' + inputId + '\')"]');

                                    if (input.type === "password") {
                                        input.type = "text";
                                        button.innerHTML = '<i class="fas fa-eye-slash"></i>';
                                    } else {
                                        input.type = "password";
                                        button.innerHTML = '<i class="fas fa-eye"></i>';
                                    }
                                }
                            </script>
                            <?php
                            if (isset($_POST['submit'])) {
                                $fname = $db->real_escape_string($_POST['fname']);
                                $lname = $db->real_escape_string($_POST['lname']);
                                $email = $db->real_escape_string($_POST['email']);
                                $pswd = $db->real_escape_string(password_hash($_POST['password'], PASSWORD_DEFAULT));
                                $city = $db->real_escape_string($_POST['city_id']);
                                $id = $db->real_escape_string($_POST['id']);
                                $idnum = $db->real_escape_string($_POST['idnum']);
                                $identity_expiration = $db->real_escape_string($_POST['expiration']);
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
                                    $sql = "INSERT INTO user_temp (first_name, last_name, email, password, token, city_id, identity_type, user_identity_num, identity_expiration) 
                VALUES ('$fname', '$lname', '$email', '$pswd', '$token', '$city', '$id', '$idnum', '$identity_expiration')";

                                    if ($db->query($sql) === TRUE) {
                                        $mail = new PHPMailer(true);

                                        try {
                                            // Replace the placeholders with your actual SMTP email settings
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
                                            $mail->Subject = 'City Admin Email Verification';
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
                                                    <h1>Verify Your City Admin Account for TaraSabay App</h1>
                                                    <p>Dear City Admin,</p>
                                                    <p>Thank you for joining TaraSabay App as a City Admin. To claim your City Admin account, we need to verify your email address.</p>
                                                    <p>Please click on the button below to verify your email address and complete your City Admin registration:</p>
                                                    <p><a href=\"http://localhost/tarasabay-localhost/verifyAdmin.php?token=" . urlencode($token) . " \" style=\"display:inline-block; padding: 10px 20px; background-color: #0072C6; color: #fff; font-weight: bold; text-decoration: none;\">Verify Your Email Address</a></p>
                                                    <p>Your account details:</p>
                                                    <p>Email: $email</p>
                                                    <p>Password: " . $_POST['password'] . "</p>
                                                    <p>If you have any questions or need assistance, please don't hesitate to contact us at support@tarasabay.com.</p>
                                                    <p>Best regards,</p>
                                                    <p>TaraSabay PH Team</p>
                                                </div>
                                            </body>
                                            </html>";


                                            $mail->send();
                                            echo '<div style="text-align: center; font-family: \'Poppins\', sans-serif; background-color: #FFFFFF; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;">
                    <img src="../assets/img/checked.png" alt="City Admin Registration" style="margin-bottom: 20px; width: 100px">
                    <h5 style="color: #4CAF50; font-size: 24px; margin-bottom: 20px;">City Admin registration successful!</h5>
                    <p style="color: #333333; font-size: 16px; margin-bottom: 20px;">Thank you for adding a new City Admin for TaraSabay App. The registration has been successfully completed.</p>
                    <p style="color: #333333; font-size: 16px; margin-bottom: 20px;">To activate the City Admin account, please notify the new employee toverify the email address by clicking the verification link sent to the email submitted in the form.</p>
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

                            <button type="submit" style="float:right; margin-right: 1%;" name="submit" id="submit" class="btn btn-success">Create Account</button>
                        </form>
                        <!-- End Form-->
                    </div>

                </div>

                <hr>
                <!-- Sticky Footer -->
                <?php include("vendor/inc/footer.php"); ?>

            </div>
            <!-- /.content-wrapper -->

        </div>
        <!-- /#wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Logout Modal-->
        <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                        <a class="btn btn-danger" href="../logout.php">Logout</a>
                    </div>
                </div>
            </div>
        </div>



        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

        <!-- Page level plugin JavaScript-->
        <script src="vendor/chart.js/Chart.min.js"></script>
        <script src="vendor/datatables/jquery.dataTables.js"></script>
        <script src="vendor/datatables/dataTables.bootstrap4.js"></script>

        <!-- Custom scripts for all pages-->
        <script src="vendor/js/sb-admin.min.js"></script>

        <!-- Demo scripts for this page-->
        <script src="vendor/js/demo/datatables-demo.js"></script>
        <script src="vendor/js/demo/chart-area-demo.js"></script>
        <!--INject Sweet alert js-->
        <script src="vendor/js/swal.js"></script>

</body>

</html>