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
<title>Add Route</title>

<link rel="icon" href="../assets/img/logo.png" type="images" />

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
                        <a href="#">Register A Route</a>
                    </li>
                    <li class="breadcrumb-item active">Add Route</li>
                </ol>
                <hr>
                <div class="card">
                    <div class="card-header">
                        <b>Earn with TaraSabay: Route Registration</b><br>
                        Turn your car into a source of income by registering your route with TaraSabay.
                        <br>Connect with passengers, set your schedule, and maximize your earning potential.
                        <br>
                        <em><br>Join our community of drivers and start earning today!</em>
                        <!-- Icon Cards-->
                        <br><br>
                        <div class="row">
                            <div class="col-xl-3 col-sm-6 mb-3">
                                <div class="card text-white" style="background-color: #EAAA00;">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fas fa-route"></i>
                                        </div>
                                        <?php
                                        // Code for counting the ticket balance by user ID
                                        $result = $db->query("SELECT COUNT(*) FROM car INNER JOIN route ON car.user_id = route.car_id WHERE car.user_id = '$userid' AND route.route_status = 'Active'");
                                        $route = $result->fetch_row()[0];
                                        ?>
                                        <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><i class="fas fa-route"></i>&nbsp;&nbsp;<?php echo $route; ?></span> Active Routes</div>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        // Code for counting the ticket balance by user ID
                                        $result = $db->query("SELECT COUNT(*) FROM car INNER JOIN route ON car.user_id = route.car_id WHERE car.user_id = '$userid' AND route.route_status = 'Previous'");
                                        $route = $result->fetch_row()[0];
                                        ?>
                                        <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><i class="fas fa-route"></i>&nbsp;&nbsp;<?php echo $route; ?></span> Previous Routes</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" autocomplete="on" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="car_id">Choose Which Car to Use<span class="text-danger"> *</span></label>
                                <select class="form-control" name="car_id" id="car_id" required>
                                    <option value="" selected disabled>Select Vehicle</option>
                                    <?php
                                    $sql = "SELECT * FROM car WHERE user_id = '$userid' AND car_status = 'Active'";
                                    $result = mysqli_query($db, $sql);
                                    $numRows = mysqli_num_rows($result);

                                    if ($numRows > 0) {
                                        while ($row = mysqli_fetch_assoc($result)) {
                                            $car_id  = $row['car_id'];
                                            $type = $row['type'];
                                            $brand = $row['brand'];
                                            $model = $row['model'];
                                            $seat_count = $row['seat_count'];
                                    ?>
                                            <option value="<?php echo $car_id; ?>" <?php echo isset($_POST['car_id']) && $_POST['car_id'] == $car_id ? 'selected' : ''; ?>><?php echo $type . " " . $brand . " " . $model; ?></option>
                                    <?php
                                        }
                                    }
                                    ?>
                                </select>
                                <small class="form-text text-muted">
                                    <br><em>Guidelines for Selecting Car to Use:</em>
                                    <br>1. Choose the car you intend to use for the route from the dropdown list.
                                    <br>2. The available options will include active cars associated with your user account.
                                    <br>3. Select the car that best suits your needs for the specific route.
                                    <br>4. Ensure the car is in good condition and meets the requirements for the journey.
                                </small>
                                <br>
                            </div>

                            <div class="form-group">
                                <label for="pickup_loc">Pick-Up Location<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" placeholder="Enter Pick-Up Location" name="pickup_loc" required>
                                <br>
                            </div>

                            <div class="form-group">
                                <label for="dropoff_loc">Drop-off Location<span class="text-danger"> *</span></label>
                                <input type="text" class="form-control" placeholder="Enter Drop-off Location" name="dropoff_loc" required>
                                <br>
                            </div>

                            <div class="form-group">
                                <label for="departure">Departure Date and Time<span class="text-danger"> *</span></label>
                                <input type="datetime-local" class="form-control" placeholder="Enter Departure Date and Time" name="departure" required><br>
                            </div>

                            <div class="form-group">
                                <label for="est_arrival_time">Estimated Arrival Time<span class="text-danger"> *</span></label>
                                <input type="time" class="form-control" placeholder="Enter Estimated Arrival Time" name="est_arrival_time" required><br>
                            </div>


                            <?php
                            if($type == 'Coupe' AND $seat_count == '1') {

                            } else if($type == 'Coupe' AND $seat_count == '3') {

                            } else if($type == 'Crossover' AND $seat_count == '4') {

                            } else if($type == 'Crossover' AND $seat_count == '6') {
                                
                            } else if($type == 'Regular Cab' AND $seat_count == '1') {

                            } else if($type == 'Extended Cab' AND $seat_count == '3') {

                            } else if($type == 'Extended Cab' AND $seat_count == '4') {

                            } else if($type == 'Crew Cab' AND $seat_count == '4') {

                            } else if($type == 'Crew Cab' AND $seat_count == '5') {

                            } else if($type == 'SUV' AND $seat_count == '4') {

                            } else if($type == 'SUV' AND $seat_count == '7') {

                            } else if($type == 'MPV' AND $seat_count == '5') {

                            } else if($type == 'MPV' AND $seat_count == '7') {
                            }
                            ?>
                            <br>

                            <?php
                            if (isset($_POST['submit'])) {

                                $brand = $_POST["brand"];
                                $model = $_POST["model"];
                                $color = $_POST["color"];

                                if ($_POST['type'] == 'Other') {
                                    $type = isset($_POST['otherType']) ? $_POST['otherType'] : '';
                                    $seat_count = isset($_POST['otherSeatCount']) ? $_POST['otherSeatCount'] : '';
                                } else {
                                    $type = $_POST['type'];
                                    $seat_count = $_POST['seatCount'];
                                }

                                // Process and move uploaded files
                                $car_photo_path = $_FILES["car_photo"]["tmp_name"];
                                $car_photo_filename = $_FILES["car_photo"]["name"];
                                $car_photo_destination = "../assets/img/car/" . $car_photo_filename;
                                if (file_exists($car_photo_path)) {
                                    move_uploaded_file($car_photo_path, $car_photo_destination);
                                }

                                $or_photo_path = $_FILES["or_photo"]["tmp_name"];
                                $or_photo_filename = $_FILES["or_photo"]["name"];
                                $or_photo_destination = "../assets/img/or/" . $or_photo_filename;
                                if (file_exists($or_photo_path)) {
                                    move_uploaded_file($or_photo_path, $or_photo_destination);
                                }

                                $cr_photo_path = $_FILES["cr_photo"]["tmp_name"];
                                $cr_photo_filename = $_FILES["cr_photo"]["name"];
                                $cr_photo_destination = "../assets/img/cr/" . $cr_photo_filename;
                                if (file_exists($cr_photo_path)) {
                                    move_uploaded_file($cr_photo_path, $cr_photo_destination);
                                }

                                $sales_invoice = isset($_FILES["sales_invoice"]["name"]) ? $_FILES["sales_invoice"]["name"] : '';
                                if (!empty($sales_invoice)) {
                                    $sales_invoice_path = $_FILES["sales_invoice"]["tmp_name"];
                                    $sales_invoice_filename = $_FILES["sales_invoice"]["name"];
                                    $sales_invoice_destination = "../assets/img/sales-invoice/" . $sales_invoice_filename;
                                    if (file_exists($sales_invoice_path)) {
                                        move_uploaded_file($sales_invoice_path, $sales_invoice_destination);
                                    }
                                }


                                // Retrieve other form data
                                $or_number = $_POST["or_number"];
                                $cr_number = $_POST["cr_number"];
                                $plate_number = $_POST["plate_number"];
                                $plate_expiration = $_POST["plate_expiration"];

                                // Insert car data into the database
                                $car_sql = "INSERT INTO car (user_id, car_photo, brand, model, color, type, seat_count, car_status)
                                            VALUES ('{$_SESSION['user_id']}', '$car_photo_filename', '$brand', '$model', '$color', '$type', '$seat_count', 'Pending')";
                                if ($db->query($car_sql) === TRUE) {
                                    $last_car_id = $db->insert_id;

                                    // Insert car identification data into the database
                                    $identification_sql = "INSERT INTO car_identification (car_id, or_photo, or_number, cr_photo, cr_number, sales_invoice, plate_number, plate_expiration)
                                                        VALUES ('$last_car_id', '$or_photo_filename', '$or_number', '$cr_photo_filename', '$cr_number', '$sales_invoice', '$plate_number', '$plate_expiration')";
                                    if ($db->query($identification_sql) === TRUE) {
                                        // Send verification email
                                        $email = $_SESSION['email'];
                                        $verification_message = "Dear valued user,\n\nThank you for choosing our service. To ensure the security of your account, we need to verify your email address before you can start using our app.\n\nPlease follow the instructions provided in the verification email to complete the process.\n\nBest regards,\nYour App Team";

                                        // Additional email sending logic goes here
                                        // Send verification email
                                        $mail = new PHPMailer(true);

                                        // Fetch the email from the user_profile table
                                        $stmt = $db->prepare("SELECT email FROM user_profile WHERE user_id = ?");
                                        $stmt->bind_param("i", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $row = $result->fetch_assoc();
                                        $email = $row['email'];

                                        $stmt->close();
                                        $result->close();


                                        $city = $_SESSION['city_name'];

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
                                            $mail->Subject = 'Car Verification';
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
                                                <p>Thank you for choosing TaraSabay to find rides or offer your own. To ensure the security of your account, we need to verify your car before you can start using the app as a driver.</p>
                                                <p>Please visit and submit the following requirements to the nearest TaraSabay office in <b>$city</b> to deliver the necessary documents for verification. Our representatives will assist you with the process.</p>
                                                <ul>
                                                    <li>Original copy of the Certificate of Registration (CR)</li>
                                                    <li>Original copy of the Official Receipt (OR)</li>
                                                    <li>Photocopy of Driver's License</li>
                                                    <li>Photocopy of the TIN (Tax Identification Number) ID</li>
                                                    <li>Photocopy of Owner's Government ID with 3 Original Specimen Signatures</li>
                                                    <li>Vehicle Sale Invoice and Delivery Receipt (Optional)</li>
                                                    <li>LTFRB Documents (If applicable):
                                                        <ul>
                                                            <li>Provision Authority (PA)</li>
                                                            <li>Certificate of Public Convenience (CPC)</li>
                                                            <li>Motion for extension of PA</li>
                                                        </ul>
                                                        If any of the above, please provide the following of the Document:
                                                        <ul>
                                                            <li>Page 1</li>
                                                            <li>Page 2</li>
                                                        </ul>
                                                    </li>
                                                    <li>PAMI (Optional)</li>
                                                </ul>
                                                <p>If you have any questions or concerns, please don't hesitate to contact us at support@tarasabay.com.</p>
                                                <p>Best regards,</p>
                                                <p>TaraSabay PH Team</p>
                                                </div>
                                                </body>
                                                </html>";

                                            $mail->AltBody = 'Car Verification Required for Your TaraSabay App';

                                            $mail->send();

                                            echo "<div style=\"text-align: center; font-family: 'Poppins', sans-serif; background-color: #FFFFFF; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;\">
                                                    <img src=\"../assets/img/checked.png\" alt=\"Car Registration\" style=\"margin-bottom: 20px; width: 100px\">
                                                    <h5 style=\"color: #4CAF50; font-size: 24px; margin-bottom: 20px;\">Car registration requirements received!</h5>
                                                    <p style=\"color: #333333; font-size: 16px; margin-bottom: 20px;\">An email has been sent to your email address with the requirements needed to be submitted to the nearest TaraSabay office in your city.</p>
                                                    <p style=\"color: #333333; font-size: 16px;\">These requirements are necessary to become an official driver of the TaraSabay app.</p>
                                                </div>";
                                        } catch (Exception $e) {
                                            echo '<div style="text-align: center;">
                                                <h5 style="color: red">Error sending verification email: </h5>' . $mail->ErrorInfo . '
                                            </div>';
                                        }
                                    }
                                } else {
                                    echo "Error: " . $car_sql . "<br>" . $db->error;
                                }
                                // Close database connection
                                $db->close();
                            }
                            ?>
                            <button type="submit" name="submit" class="btn btn-success">Register</button>
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
        <!--INject Sweet alert js-->
        <script src="vendor/js/swal.js"></script>

</body>

</html>