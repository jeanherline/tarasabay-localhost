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
<title>Edit City Admin</title>

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
                        <a href="cityAdmins.php">Manage City Admin</a>
                    </li>
                    <li class="breadcrumb-item active">Create New City Admin</li>
                </ol>
                <hr>
                <div class="card">
                    <div class="card-header">
                        <b>Manage: </b>Edit City Admin Profile
                    </div>
                    <?php
                    if (isset($_GET['user_id'])) {
                        $id = $_GET['user_id'];
                    }

                    $stmt = $db->prepare("SELECT * FROM user_profile WHERE user_id = ?");
                    $stmt->bind_param("s", $id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 1) {
                        $row = $result->fetch_assoc();
                        $id = $row['user_id'];
                        $first_name = $row['first_name'];
                        $last_name = $row['last_name'];
                        $city_id = $row['city_id'];
                    }

                    $stmt = $db->prepare("SELECT * FROM city WHERE city_id = ?");
                    $stmt->bind_param("s", $city_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 1) {
                        $row = $result->fetch_assoc();
                        $city_name = $row['city_name'];
                    }
                    ?>

                    <div class="card-body">
                        <form method="POST" autocomplete="on" enctype="multipart/form-data">
                            <div style="color: gray;">
                                <em>Personal Information</em><br><br>
                            </div>
                            <div class="form-group">
                                <label for="fname">First Name</label>
                                <input type="text" class="form-control" value="<?php echo isset($_POST['fname']) ? $_POST['fname'] : $first_name; ?>" name="fname"><br>
                            </div>

                            <div class="form-group">
                                <label for="lname">Last Name</label>
                                <input type="text" class="form-control" value="<?php echo isset($_POST['lname']) ? $_POST['lname'] : $last_name; ?>" name="lname"><br>
                            </div>

                            <div class="form-group">
                                <label for="city_id">Designated City</label>
                                <select class="form-control" name="city_id">
                                    <?php if (!empty($city_id)) { ?>
                                        <option value="<?php echo isset($_POST['city_id']) ? $_POST['city_id'] : $city_id; ?>" selected><?php echo isset($_POST['city_name']) ? $_POST['city_name'] : $city_name; ?></option>
                                    <?php } else { ?>
                                        <option value="" selected disabled>Select City</option>
                                    <?php } ?>
                                    <?php
                                    $stmt = $db->prepare("SELECT city_id, city_name FROM city");
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()) {
                                        $cityId = $row['city_id'];
                                        $cityName = $row['city_name'];
                                    ?>
                                        <option value="<?php echo $cityId; ?>" <?php if ($cityId == $city_id) echo 'selected'; ?>><?php echo $cityName; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <br>
                            </div>
                            <?php
                            if (isset($_POST['submit'])) {
                                $fname = $db->real_escape_string($_POST['fname']);
                                $lname = $db->real_escape_string($_POST['lname']);
                                $city = $db->real_escape_string($_POST['city_id']);

                                $stmt = $db->prepare("UPDATE user_profile SET first_name = ?, last_name = ?, city_id = ? WHERE user_id = ?");
                                $stmt->bind_param("ssss", $fname, $lname, $city, $id);

                                if ($stmt->execute()) {
                                    echo '<div style="text-align: center; font-size: 10px;">
                                            <h5 style="color: green">Profile updated successfully!</h5>
                                            </div><br>';
                                    // Retrieve the updated data
                                    $first_name = $fname;
                                    $last_name = $lname;
                                    $city_id = $city;
                                } else {
                                    echo '<div style="text-align: center; font-size: 10px;">
                                            <h5 style="color: red">Error updating profile: </h5>' . $stmt->error . '
                                            </div><br>';
                                }
                            }
                            ?>
                            <button type="submit" style="float:right; margin-right: 1%;" name="submit" id="submit" class="btn btn-success">Save Edit</button>

                        </form>
                    </div>

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