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
<title>Add Car</title>

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
                        <a href="#">Register A Car</a>
                    </li>
                    <li class="breadcrumb-item active">Add Car</li>
                </ol>
                <hr>
                <div class="card">
                    <div class="card-header">
                        <b>Earn with TaraSabay: Car Registration</b><br>
                        <em>Note: You must sign up with the car that you own</em>
                        <!-- Icon Cards-->
                        <br><br>
                        <div class="row">
                            <div class="col-xl-3 col-sm-6 mb-3">
                                <div class="card text-white" style="background-color: #EAAA00;">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fas fa-fw fa fa-car"></i>
                                        </div>
                                        <?php
                                        // Code for counting the ticket balance by user ID
                                        $result = $db->query("SELECT COUNT(*) FROM car WHERE car_status = 'Pending' AND user_id = '$userid'");
                                        $cars = $result->fetch_row()[0];
                                        ?>
                                        <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><i class="fa fa-car"></i>&nbsp;&nbsp;<?php echo $cars; ?></span> Pending Cars</div>
                                    </div>
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fas fa-fw fa fa-car"></i>
                                        </div>
                                        <?php
                                        // Code for counting the ticket balance by user ID
                                        $result = $db->query("SELECT COUNT(*) FROM car WHERE car_status = 'Approved' AND user_id = '$userid'");
                                        $cars = $result->fetch_row()[0];
                                        ?>
                                        <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><i class="fa fa-car"></i>&nbsp;&nbsp;<?php echo $cars; ?></span> Registered Cars</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php
                        $sql = "SELECT * FROM driver_identification WHERE user_id = '$userid' AND driver_stat = 'Expired'";
                        $result = mysqli_query($db, $sql);
                        $numRows = mysqli_num_rows($result);

                        if ($numRows > 0) {
                            echo '<div style="text-align: center; font-family: \'Poppins\', sans-serif; background-color: #FFFFFF; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;">
                        <img src="../assets/img/cancel.png" alt="Expired License" style="margin-bottom: 20px; width: 100px">
                        <h5 style="color: #FF0000; font-size: 24px; margin-bottom: 20px;">Expired Driver\'s License</h5>
                        <p style="color: #333333; font-size: 16px; margin-bottom: 20px;">Sorry, you cannot add a car with an expired driver\'s license.</p>
                        <p style="color: #333333; font-size: 16px;">Please renew your driver\'s license to proceed or contact our support team at support@tarasabay.com for further assistance.</p>
                    </div>';
                        } else {
                        ?>
                            <form method="POST" autocomplete="on" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="car_photo">Vehicle Photo<span class="text-danger"> *</span></label>
                                    <input type="file" class="form-control" id="car_photo" name="car_photo" accept=".jpg,.jpeg,.png" required>
                                    <br>
                                </div>
                                <small class="form-text text-muted">- The car photo helps others identify your vehicle.<br>Please note that once you submit the car photo, it cannot be changed.
                                    <br>
                                    <br>Guidelines for Uploading Car Photo:
                                    <br>1. Ensure the car is clearly visible, with no obstructions or excessive glare.
                                    <br>2. Capture the photo in good lighting conditions, preferably during daylight.
                                    <br>3. Avoid using filters or excessive editing that alters the car's appearance.
                                    <br>4. Include the entire car within the frame, with all relevant details visible.
                                </small>
                                <br>
                                <div class="form-group">
                                    <label for="brand">Vehicle Brand<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Car Brand" name="brand" required>
                                    <br>
                                </div>

                                <div class="form-group">
                                    <label for="model">Vehicle Model<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Car Model" name="model" required><br>
                                </div>

                                <div class="form-group">
                                    <label for="color">Vehicle Color<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Car Color" name="color" required><br>
                                </div>

                                <div class="form-group">
                                    <label for="type">Vehicle Type<span class="text-danger"> *</span></label>
                                    <select class="form-control" name="type" id="type" required>
                                        <option value="" selected disabled>Select Car Type</option>
                                        <option value="Wagon" data-seats="4">Wagon</option>
                                        <option value="Sedan" data-seats="4">Sedan</option>
                                        <option value="Hatchback" data-seats="4">Hatchback</option>
                                        <option value="Coupe" data-seats="1-3">Coupe</option>
                                        <option value="Crossover" data-seats="4-6">Crossover</option>
                                        <option value="Regular Cab" data-seats="1">Pick-up (Regular Cab)</option>
                                        <option value="Extended Cab" data-seats="3-4">Pick-up (Extended Cab)</option>
                                        <option value="Crew Cab" data-seats="4-5">Pick-up (Crew Cab)</option>
                                        <option value="SUV" data-seats="4-7">SUV (Sports Utility Vehicle)</option>
                                        <option value="MPV" data-seats="5-7">MPV (Multi-Purpose Vehicle)</option>
                                    </select>
                                </div>

                                <div class="form-group" id="seatCountContainer" style="display: none;">
                                    <label for="seatCount">Available Seat Count (Passenger Capacity, Excluding Driver Seat)<span class="text-danger"> *</span></label>
                                    <select class="form-control" name="seatCount" id="seatCount" required>
                                        <option value="" selected disabled>Select Seat Count</option>
                                    </select>
                                    <br>
                                    <small class="form-text text-muted">- Selecting the Seat Count or Passenger Capacity (Excluding Driver Seat)<br>Please note that the chosen seat count cannot be changed once submitted.
                                        <br>
                                        <br>Guidelines for Selecting Seat Count:
                                        <br>1. Include the total number of passengers the vehicle can accommodate, excluding the driver's seat.
                                        <br>2. If applicable, consider the middle seat on the back row as part of the seat count.
                                        <br>3. Ensure the selected seat count accurately represents the maximum number of passengers the vehicle can hold.
                                        <br>4. Take into account the available seating positions, including bench seats or optional third-row seating, if applicable.
                                    </small>
                                </div>

                                <br>
                                <script>
                                    document.getElementById('type').addEventListener('change', function() {
                                        var selectedValue = this.value;
                                        var otherTypeContainer = document.getElementById('otherTypeContainer');
                                        var seatCountContainer = document.getElementById('seatCountContainer');
                                        var otherSeatCountContainer = document.getElementById('otherSeatCountContainer');
                                        var seatCountSelect = document.getElementById('seatCount');

                                        seatCountContainer.style.display = 'block';

                                        var selectedOption = this.options[this.selectedIndex];
                                        var seatCount = selectedOption.dataset.seats;

                                        seatCountSelect.innerHTML = '';

                                        if ((selectedValue === 'Coupe')) {
                                            seatCountSelect.innerHTML = '<option value="' + 1 + '" selected>' + 1 + ' Seats</option>';
                                            seatCountSelect.innerHTML += '<option value="' + 3 + '" selected>' + 3 + ' Seats</option>';
                                        } else if ((selectedValue === 'Extended Cab')) {
                                            seatCountSelect.innerHTML = '<option value="' + 3 + '" selected>' + 3 + ' Seats</option>';
                                            seatCountSelect.innerHTML += '<option value="' + 4 + '" selected>' + 4 + ' Seats</option>';
                                        } else if ((selectedValue === 'Crew Cab')) {
                                            seatCountSelect.innerHTML = '<option value="' + 4 + '" selected>' + 4 + ' Seats</option>';
                                            seatCountSelect.innerHTML += '<option value="' + 5 + '" selected>' + 5 + ' Seats</option>';
                                        } else if ((selectedValue === 'SUV')) {
                                            seatCountSelect.innerHTML = '<option value="' + 4 + '" selected>' + 4 + ' Seats</option>';
                                            seatCountSelect.innerHTML += '<option value="' + 7 + '" selected>' + 7 + ' Seats</option>';
                                        } else if ((selectedValue === 'Crossover')) {
                                            seatCountSelect.innerHTML = '<option value="' + 4 + '" selected>' + 4 + ' Seats</option>';
                                            seatCountSelect.innerHTML += '<option value="' + 6 + '" selected>' + 6 + ' Seats</option>';
                                        } else if ((selectedValue === 'MPV')) {
                                            seatCountSelect.innerHTML = '<option value="' + 5 + '" selected>' + 5 + ' Seats</option>';
                                            seatCountSelect.innerHTML += '<option value="' + 7 + '" selected>' + 7 + ' Seats</option>';
                                        } else {
                                            seatCountSelect.innerHTML = '<option value="' + seatCount + '" selected>' + seatCount + ' Seat/s</option>';
                                        }
                                    });
                                </script>

                                <div class="form-group">
                                    <label for="or_photo">Upload LTO Official Receipt (OR)<span class="text-danger"> *</span></label>
                                    <input type="file" class="form-control" id="or_photo" name="or_photo" accept=".jpg,.jpeg,.png" required>
                                    <br>
                                </div>

                                <div class="form-group">
                                    <label for="or_number">LTO Official Receipt (OR) Number<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" placeholder="Enter OR Number" name="or_number" required><br>
                                </div>

                                <div class="form-group">
                                    <label for="cr_photo">Upload LTO Certificate of Registration (CR)<span class="text-danger"> *</span></label>
                                    <input type="file" class="form-control" id="cr_photo" name="cr_photo" accept=".jpg,.jpeg,.png" required>
                                    <br>
                                </div>

                                <div class="form-group">
                                    <label for="cr_number">LTO Certificate of Registration (CR) Number<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" placeholder="Enter CR Number" name="cr_number" required><br>
                                </div>

                                <div class="form-group">
                                    <label for="sales_invoice">Vehicle Sale Invoice and Delivery Receipt<span class="text-success"> (Optional)</span></label>
                                    <input type="file" class="form-control" id="sales_invoice" name="sales_invoice" accept=".jpg,.jpeg,.png">
                                    <br>
                                </div>

                                <div class="form-group">
                                    <label for="plate_number">License Plate Number<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" placeholder="Enter License Plate Number" name="plate_number" required>
                                    <small class="form-text text-muted">
                                        <br><em>Guidelines for Entering License Plate Number:</em>
                                        <br>1. Enter the license plate number exactly as it appears on your vehicle's registration documents.
                                        <br>2. Include any letters, numbers, and special characters as they are displayed on the license plate.
                                        <br>3. Avoid including spaces or hyphens unless they are part of the official license plate format.
                                        <br>4. Double-check for accuracy and ensure all characters are entered correctly.
                                    </small>
                                </div>

                                <br>
                                <div class="form-group">
                                    <label for="plate_expiration">Registration Expiration Date<span class="text-danger"> *</span></label>
                                    <input type="date" class="form-control" name="plate_expiration" required>
                                    <small class="form-text text-muted">
                                        <br><em>Guidelines for Entering Registration Expiration Date:</em>
                                        <br>1. Enter the registration expiration date exactly as it appears on your vehicle's registration documents.
                                        <br>2. Use the specified date format provided on the registration documents (e.g., MM/DD/YYYY).
                                        <br>3. Ensure the entered date represents the month, day, and year accurately.
                                        <br>4. The registration expiration date should be <b>at least 6 months</b> in the future from the current date.
                                        <br>5. Double-check for accuracy and verify that the registration is valid and meets the 6-month requirement at the time of submission.
                                    </small>
                                </div>
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
                                <hr>

                            </form>
                        <?php
                        }
                        ?>

                        <!-- End Form-->
                    </div>
                </div>
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