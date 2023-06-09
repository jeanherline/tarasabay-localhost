<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
}

include('../db.php');

$stmt = $db->prepare("SELECT * FROM city WHERE city_id = ?");
$stmt->bind_param("s", $city_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $city_name = $row['city_name'];
}


$stmt = $db->prepare("SELECT * FROM user_identification WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $idtype = $row['identity_type'];
    $validid = $row['user_identity_num'];
}

?>
<!DOCTYPE html>
<html lang="en">
<title>Edit Car Registration</title>
<link href="vendor/css/user.css" rel="stylesheet">
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
                        <?php
                        if (isset($_SESSION['role']) == "City Admin") {
                        ?>
                            <?php
                            $list = '';

                            if (isset($_GET['status'])) {
                                $listOption = $_GET['status'];

                                // Check the value of the list option and set the corresponding value for $list
                                if ($listOption === 'Pending' || $listOption === 'Active' || $listOption === 'Denied') {
                                    $list = $listOption;
                                }
                            }
                            ?>
                            <a href="carReg.php?status=<?php echo $list; ?>">Registrations</a>
                        <?php
                        }
                        ?>
                    </li>
                    <li class="breadcrumb-item active">Edit Pending</li>
                </ol>
                <hr>

                <?php
                if (isset($_GET['user_id'])) {
                    $user_id = $_GET['user_id'];
                }
                $ret = "SELECT car.*, car_identification.*, user_profile.*, driver_identification.*, city.city_name
                        FROM car
                        INNER JOIN user_profile ON car.user_id = user_profile.user_id
                        INNER JOIN driver_identification ON car.user_id = driver_identification.user_id
                        INNER JOIN car_identification ON car.user_id = driver_identification.user_id
                        INNER JOIN city ON user_profile.city_id = city.city_id
                        WHERE car.car_status = 'Pending' AND user_profile.user_id = '$user_id'";
                $stmt = $db->prepare($ret);
                $stmt->execute();
                $result = $stmt->get_result();
                $cnt = 1;

                while ($row = $result->fetch_assoc()) {
                    $car_id = $row['car_id'];
                    $brand = $row['brand'];
                    $model = $row['model'];
                    $color = $row['color'];
                    $type = $row['type'];
                    $seat_count = $row['seat_count'];

                    $or_photo = $row['or_photo'];
                    $or_number = $row['or_number'];
                    $cr_photo = $row['cr_photo'];
                    $cr_number = $row['cr_number'];
                    $sales_invoice = $row['sales_invoice'];
                    $plate_number = $row['plate_number'];
                    $plate_expiration = $row['plate_expiration'];
                }
                ?>


                <div class="card">
                    <div class="card-header">
                        <b>View Profile</b>
                    </div>

                    <div class="card-body">
                        <!--Add User Form-->
                        <form method="POST">
                            <style>
                                .disabled-input {
                                    color: black;
                                }
                            </style>

                            <div id="emailHelp" class="form-text">
                                <em>Car Submitted for Registration </em>
                            </div><br>

                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $_POST['brand'] ?? $brand; ?>" name="brand"><br>
                            </div>

                            <div class="form-group">
                                <label for="model">Model</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $_POST['model'] ?? $model; ?>" name="model"><br>
                            </div>

                            <div class="form-group">
                                <label for="color">Color</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $_POST['color'] ?? $color; ?>" name="color"><br>
                            </div>

                            <div class="form-group">
                                <label for="or_number">OR Number</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $_POST['or_number'] ?? $or_number; ?>" name="or_number"><br>
                            </div>

                            <div class="form-group">
                                <label for="cr_number">CR Number</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $_POST['cr_number'] ?? $cr_number; ?>" name="cr_number"><br>
                            </div>

                            <div class="form-group">
                                <label for="plate_number">Plate Number</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $_POST['plate_number'] ?? $plate_number; ?>" name="plate_number"><br>
                            </div>

                            <div class="form-group">
                                <label for="plate_expiration">Plate Expiration</label>
                                <input type="date" class="form-control disabled-input" value="<?php echo $_POST['plate_expiration'] ?? $plate_expiration; ?>" name="plate_expiration"><br>
                            </div>

                            <?php
                            if (isset($_POST['submit'])) {
                                $brand = $_POST['brand'];
                                $model = $_POST['model'];
                                $color = $_POST['color'];
                                $or_number = $_POST['or_number'];
                                $cr_number = $_POST['cr_number'];
                                $plate_number = $_POST['plate_number'];
                                $plate_expiration = $_POST['plate_expiration'];

                                // Update the car details in the `car` table
                                $updateCarQuery = "UPDATE car 
                                                SET brand = ?, model = ?, color = ?
                                                WHERE user_id = ? AND car_id = ?";

                                $updateCarStmt = $db->prepare($updateCarQuery);
                                $updateCarStmt->bind_param("sssss", $brand, $model, $color, $user_id, $car_id);
                                $updateCarStmt->execute();

                                // Update the car identification details in the `car_identification` table
                                $updateCarIdentificationQuery = "UPDATE car_identification
                                         SET or_number = ?, cr_number = ?,
                                             plate_number = ?, plate_expiration = ?
                                         WHERE car_id = (SELECT car_id FROM car WHERE user_id = ? AND car_id = ?)";

                                $updateCarIdentificationStmt = $db->prepare($updateCarIdentificationQuery);
                                $updateCarIdentificationStmt->bind_param("ssssss", $or_number, $cr_number, $plate_number, $plate_expiration, $user_id, $car_id);
                                $updateCarIdentificationStmt->execute();

                                echo '<div style="text-align: center;"><h5 style="color: green; font-size:16px;">Updated</h5></div>';
                            }
                            ?>
                            <button type="submit" name="submit" style="float:right; margin-right: 1%;" class="btn btn-success">Save</button>
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