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
<title>Registrations</title>
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
                                if ($listOption === 'Pending' || $listOption === 'Active' || $listOption === 'Denied' || $listOption === 'Expired') {
                                    $list = $listOption;
                                }
                            }
                            ?>
                            <?php
                            if ($list == 'Expired') {
                            ?>
                                <a href="renewal.php?list=Plate">Renewal</a>

                            <?php
                            } else {
                            ?>
                                <a href="carReg.php?status=<?php echo $list; ?>">Active Cars</a>

                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>
                    </li>
                    <li class="breadcrumb-item active">View <?php echo $list ?></li>
                </ol>
                <hr>
                <br><br>
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
                        WHERE car.car_status = '$list' AND user_profile.user_id = '$user_id'";
                $stmt = $db->prepare($ret);
                $stmt->execute();
                $result = $stmt->get_result();
                $cnt = 1;

                while ($row = $result->fetch_assoc()) {
                    $profile_photo = $row['profile_photo'];
                    $first_name = $row['first_name'];
                    $middle_name = $row['middle_name'];
                    $last_name = $row['last_name'];
                    $role = $row['role'];
                    $car_status = $row['car_status'];

                    $disability = $row['disability'];
                    $pwd_docx = $row['pwd_docx'];
                    $license_front = $row['license_front'];
                    $license_back = $row['license_back'];
                    $license_expiration = $row['license_expiration'];
                    $nbi_police_cbi = $row['nbi_police_cbi'];
                    $cbi_date_issued = $row['cbi_date_issued'];
                    $years_experience = $row['years_experience'];

                    $car_photo = $row['car_photo'];
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
                <div class="profile-photo">
                    <?php if (!empty($profile_photo)) { ?>
                        <img src="../assets/img/profile-photo/<?php echo $profile_photo; ?>" alt="Profile Photo" class="photo-preview">
                    <?php } ?>

                </div>
                <br>
                <p class="role" style="font-size: 22px; font-weight: bold;
                                                text-align: center; color: #000;">
                    <?php echo $first_name . " " . $last_name; ?> ✔️
                </p>
                <p class="role" style="font-size: 18px; font-weight: bold;
                                                text-align: center; color: green;">
                    <?php echo $role; ?>
                </p>
                <br>
                <style>
                    .form-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 10vh;
                    }
                </style>

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
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $first_name ?>" name="first_name" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $middle_name ?>" name="middle_name" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $last_name ?>" name="last_name" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="disability">Disability</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $disability ?>" name="disability" disabled><br>
                            </div>

                            <div class="form-group text-center">
                                <label for="pwd_docx">PWD ID / Certificate:</label>
                                <br>
                                <div class="vax-card" style="width:200px;">
                                    <?php if (!empty($disability)) { ?>
                                        <img src="../assets/img/pwd/<?php echo $pwd_docx; ?>" alt="PWD" class="card-preview">
                                    <?php } else { ?>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="form-group text-center">
                                <label for="license_front">License Front:</label>
                                <br>
                                <div class="license" style="width: 300px; margin: 0 auto;">
                                    <img src="../assets/img/license/<?php echo $license_front; ?>" alt="license_front" class="card-preview">
                                </div>
                            </div>


                            <div class="form-group text-center">
                                <label for="license_front">License Back:</label>
                                <br>
                                <div class="license" style="width: 300px; margin: 0 auto;">
                                    <img src="../assets/img/license/<?php echo $license_back; ?>" alt="license_back" class="card-preview">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="license_expiration">License Expiration</label>
                                <input type="date" class="form-control disabled-input" value="<?php echo $license_expiration ?>" name="license_expiration" disabled><br>
                            </div>

                            <div class="form-group text-center">
                                <label for="pwd_docx">NBI / Police / CBI:</label>
                                <br>
                                <div class="vax-card" style="width:200px;">
                                    <img src="../assets/img/docx/<?php echo $nbi_police_cbi; ?>" alt="nbi_police_cbi" class="card-preview">
                                </div>
                            </div>

                            <div class=" form-group">
                                <label for="cbi_date_issued"><em><b>IF:</b></em> CBI Date of Issuance</label>
                                <input type="date" class="form-control disabled-input" value="<?php echo $cbi_date_issued ?>" name="cbi_date_issued" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="years_experience">Years of Experience</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $years_experience ?>" name="years_experience" disabled><br>
                            </div>

                            <div id="emailHelp" class="form-text">
                                <em>Car Submitted for Registration </em>
                            </div><br>

                            <div class="form-group text-center">
                                <label for="pwd_docx">Car Photo:</label>
                                <br>
                                <div class="vax-card" style="width: 300px; margin: 0 auto;">
                                    <img src="../assets/img/car/<?php echo $car_photo; ?>" alt="car_photo" class="card-preview">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $brand ?>" name="brand" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="model">Model</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $model ?>" name="model" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="color">Color</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $color ?>" name="color" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="type">Type</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $type ?>" name="type" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="seat_count">Seat Count</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $seat_count ?>" name="seat_count" disabled><br>
                            </div>

                            <div class="form-group text-center">
                                <label for="or_photo">OR Photo:</label>
                                <br>
                                <div class="license" style="width: 300px; margin: 0 auto;">
                                    <img src="../assets/img/or/<?php echo $or_photo; ?>" alt="or_photo" class="card-preview">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="or_number">OR Number</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $or_number ?>" name="or_number" disabled><br>
                            </div>

                            <div class="form-group text-center">
                                <label for="cr_photo">Cr Photo:</label>
                                <br>
                                <div class="license" style="width: 300px; margin: 0 auto;">
                                    <img src="../assets/img/cr/<?php echo $cr_photo; ?>" alt="cr_photo" class="card-preview">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="cr_number">CR Number</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $cr_number ?>" name="cr_number" disabled><br>
                            </div>

                            <?php
                            if (!empty($sales_invoice)) {
                            ?>
                                <div class="form-group text-center">
                                    <label for="sales_invoice">Sales Invoice:</label>
                                    <br>
                                    <div class="license" style="width: 300px; margin: 0 auto;">
                                        <img src="../assets/img/sales-invoice/<?php echo $sales_invoice; ?>" alt="sales_invoice" class="card-preview">
                                    </div>
                                </div>
                            <?php
                            }
                            ?>

                            <div class="form-group">
                                <label for="plate_number">Plate Number</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $plate_number ?>" name="plate_number" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="plate_expiration">Plate Expiration</label>
                                <input type="date" class="form-control disabled-input" value="<?php echo $plate_expiration ?>" name="plate_expiration" disabled><br>
                            </div>

                            <?php
                            if ($car_status == 'Pending') {
                            ?>
                                <a href="approveCarReg.php"><button style="float:right; margin-right: 1%;" class="btn btn-success">Approve</button></a>
                                <a href="denyCarReg.php"><button style="float:right; margin-right: 1%;" class="btn btn-success">Deny</button></a>
                            <?php
                            }

                            ?>

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