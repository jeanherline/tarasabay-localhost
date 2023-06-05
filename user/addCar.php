<?php
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
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
                        <a href="#">Apply</a>
                    </li>
                    <li class="breadcrumb-item active">Add Car</li>
                </ol>
                <hr>
                <div class="card">
                    <div class="card-header">
                        <b>Car Registration</b>
                    </div>
                    <div class="card-body">
                        <form method="POST" autocomplete="on" enctype="multipart/form-data">
                            <div id="emailHelp" class="form-text">
                                <b>Vehicle Ownership</b><br><br>
                                <em>You must sign up with the car that you own.</em><br><br>
                            </div>
                            <br>
                            <div class="form-group">
                                <label for="car_photo">Vehicle Photo<span class="text-danger"> *</span></label>
                                <input type="file" class="form-control" id="car_photo" name="car_photo" accept=".jpg,.jpeg,.png" required>
                                <br>
                            </div>

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
                                    <option value="Sedan" data-seats="4">Sedan</option>
                                    <option value="Hatchback" data-seats="4">Hatchback</option>
                                    <option value="Coupe" data-seats="2">Coupe</option>
                                    <option value="Convertible" data-seats="2">Convertible</option>
                                    <option value="Wagon" data-seats="5">Wagon</option>
                                    <option value="Other">Other</option>
                                </select>
                                <br>
                            </div>

                            <div class="form-group" id="otherTypeContainer" style="display: none;">
                                <label for="otherType">Indicate Other Type</label>
                                <input type="text" class="form-control" name="otherType" id="otherType">
                            </div>

                            <div class="form-group" id="seatCountContainer" style="display: none;">
                                <label for="seatCount">Seat Count<span class="text-danger"> *</span></label>
                                <select class="form-control" name="seatCount" id="seatCount" required>
                                    <option value="" selected disabled>Select Seat Count</option>
                                </select>
                                <br>
                            </div>

                            <div class="form-group" id="otherSeatCountContainer" style="display: none;">
                                <label for="otherSeatCount">Indicate Seat Count<span class="text-danger"> *</span></label>
                                <input type="number" class="form-control" name="otherSeatCount" id="otherSeatCount" required>
                                <br>
                            </div>

                            <script>
                                document.getElementById('type').addEventListener('change', function() {
                                    var selectedValue = this.value;
                                    var otherTypeContainer = document.getElementById('otherTypeContainer');
                                    var seatCountContainer = document.getElementById('seatCountContainer');
                                    var otherSeatCountContainer = document.getElementById('otherSeatCountContainer');
                                    var seatCountSelect = document.getElementById('seatCount');

                                    if (selectedValue === 'Other') {
                                        otherTypeContainer.style.display = 'block';
                                        seatCountContainer.style.display = 'none';
                                        otherSeatCountContainer.style.display = 'block';
                                        seatCountSelect.innerHTML = '<option value="" selected disabled>Select Seat Count</option>';
                                    } else {
                                        otherTypeContainer.style.display = 'none';
                                        seatCountContainer.style.display = 'block';
                                        otherSeatCountContainer.style.display = 'none';

                                        var selectedOption = this.options[this.selectedIndex];
                                        var seatCount = selectedOption.dataset.seats;

                                        seatCountSelect.innerHTML = '<option value="' + seatCount + '" selected>' + seatCount + ' Seats</option>';
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
                                <input type="text" class="form-control" placeholder="Enter License Plate Number" name="plate_number" required><br>
                            </div>

                            <div class="form-group">
                                <label for="plate_expiration">Registration Expiration Date<span class="text-danger"> *</span></label>
                                <input type="date" class="form-control" name="plate_expiration" required><br>
                            </div>

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
                                $car_photo_destination = "../assets/img/photos/" . $car_photo_filename;
                                move_uploaded_file($car_photo_path, $car_photo_destination);

                                $or_photo_path = $_FILES["or_photo"]["tmp_name"];
                                $or_photo_filename = $_FILES["or_photo"]["name"];
                                $or_photo_destination = "../assets/img/photos/" . $or_photo_filename;
                                move_uploaded_file($or_photo_path, $or_photo_destination);

                                $cr_photo_path = $_FILES["cr_photo"]["tmp_name"];
                                $cr_photo_filename = $_FILES["cr_photo"]["name"];
                                $cr_photo_destination = "../assets/img/photos/" . $cr_photo_filename;
                                move_uploaded_file($cr_photo_path, $cr_photo_destination);

                                // Retrieve other form data
                                $or_number = $_POST["or_number"];
                                $cr_number = $_POST["cr_number"];
                                $sales_invoice = isset($_FILES["sales_invoice"]["name"]) ? $_FILES["sales_invoice"]["name"] : '';
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

                                        echo "<div style=\"text-align: center; font-family: 'Poppins', sans-serif; background-color: #FFFFFF; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;\">
                                            <h5 style=\"color: #4CAF50; font-size: 24px; margin-bottom: 20px;\">Car registration successful!</h5>
                                            <p style=\"color: #333333; font-size: 16px; margin-bottom: 20px;\">An email has been sent to your email address to verify your car registration.</p>
                                            <p style=\"color: #333333; font-size: 16px; margin-bottom: 10px;\">Please bring the following documents for car registration:</p>
                                            <ul style=\"list-style: disc; margin-left: 30px; color: #333333; font-size: 16px;\">
                                                <li>Original copy of the Certificate of Registration (CR)</li>
                                                <li>Original copy of the Official Receipt (OR)</li>
                                                <li>Photocopy of the driver's valid government-issued ID</li>
                                                <li>Photocopy of the TIN (Tax Identification Number) ID</li>
                                                <li>Photocopy of Owner's Government ID with 3 Original Specimen Signatures</li>
                                                <li>Vehicle Official Receipt</li>
                                                <li>Vehicle Certificate of Registration</li>
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
                                        </div>";
                                    } else {
                                        echo "Error: " . $identification_sql . "<br>" . $db->error;
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
        <script src="vendor/js/demo/chart-area-demo.js"></script>
        <!--INject Sweet alert js-->
        <script src="vendor/js/swal.js"></script>

</body>

</html>