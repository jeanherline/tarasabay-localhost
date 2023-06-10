<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
}

include('../db.php');

$userid = $_SESSION['user_id'];
$role = $_SESSION['role'];

$stmt = $db->prepare("SELECT * FROM user_profile WHERE user_id = ?");
$stmt->bind_param("s", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $id = $row['user_id'];
    $first_name = $row['first_name'];
    $last_name = $row['last_name'];
    $email = $row['email'];
    $pswd = $row['password'];
}

$stmt = $db->prepare("SELECT * FROM user_identification WHERE user_id = ?");
$stmt->bind_param("s", $userid);
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

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Vehicle Booking System Transport Saccos, Matatu Industry">
    <meta name="author" content="MartDevelopers">
    <link rel="icon" href="../assets/img/logo.png" type="images" />

    <title>Cars</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="vendor/css/sb-admin.css" rel="stylesheet">

</head>

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
                <?php
                if (isset($_GET['list']) && $_GET['list'] === 'Registered') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Driver</a>
                        </li>
                        <li class="breadcrumb-item active">Registered Cars</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Manage Cars
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Car Photo</th>
                                            <th>Vehicle Type</th>
                                            <th>Brand, Model, & Color</th>
                                            <th>OR No.</th>
                                            <th>CR No.</th>
                                            <th>Plate No.</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];

                                        $ret = "SELECT car.*, user_profile.*, car_identification.*, city.city_name
                                        FROM car
                                        INNER JOIN user_profile ON car.user_id = user_profile.user_id
                                        INNER JOIN car_identification ON car.car_id = car_identification.car_id
                                        INNER JOIN city ON user_profile.city_id = city.city_id
                                        WHERE car.car_status = 'Active' AND user_profile.user_id = '$user_id'";
                                        $stmt = $db->prepare($ret);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;

                                        while ($row = $result->fetch_assoc()) {
                                            $id = $row['user_id'];
                                            $car_id = $row['car_id'];
                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td><img src='../assets/img/car/" . $row['car_photo'] . "' alt='Profile Photo' width='50' height='50'></td>";
                                            echo "<td>" . $row['type'] . "</td>";
                                            echo "<td>" . $row['brand'] . " " . $row['model'] . " " . $row['color'] . "</td>";
                                            echo "<td>" . $row['or_number'] . "</td>";
                                            echo "<td>" . $row['cr_number'] . "</td>";
                                            echo "<td>" . $row['plate_number'] . "</td>";
                                        ?>
                                            <td>
                                                <a href="viewDriverCars.php?user_id=<?php echo $id; ?>&status=Registered&car_id=<?php echo $car_id; ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-eye"></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                            </td>

                                        <?php
                                        }
                                        ?>

                                        <?php
                                        echo "</tr>";
                                        $cnt++;

                                        ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="card-footer small text-muted">
                            <?php
                            date_default_timezone_set("Africa/Nairobi");
                            echo "Generated : " . date("h:i:sa");
                            ?>
                        </div>
                    </div>
                    <?php

                    ?>

                <?php
                }
                if (isset($_GET['list']) && $_GET['list'] === 'Pending') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Driver</a>
                        </li>
                        <li class="breadcrumb-item active">Pending Cars</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Manage Cars
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Car Photo</th>
                                            <th>Vehicle Type</th>
                                            <th>Brand, Model, & Color</th>
                                            <th>OR No.</th>
                                            <th>CR No.</th>
                                            <th>Plate No.</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];

                                        $ret = "SELECT car.*, user_profile.*, car_identification.*, city.city_name
                                            FROM car
                                            INNER JOIN user_profile ON car.user_id = user_profile.user_id
                                            INNER JOIN car_identification ON car.car_id = car_identification.car_id
                                            INNER JOIN city ON user_profile.city_id = city.city_id
                                            WHERE car.car_status = 'Pending' AND user_profile.user_id = '$user_id'";
                                        $stmt = $db->prepare($ret);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;

                                        while ($row = $result->fetch_assoc()) {
                                            $id = $row['user_id'];
                                            $car_id = $row['car_id'];
                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td><img src='../assets/img/car/" . $row['car_photo'] . "' alt='Profile Photo' width='50' height='50'></td>";
                                            echo "<td>" . $row['type'] . "</td>";
                                            echo "<td>" . $row['brand'] . " " . $row['model'] . " " . $row['color'] . "</td>";
                                            echo "<td>" . $row['or_number'] . "</td>";
                                            echo "<td>" . $row['cr_number'] . "</td>";
                                            echo "<td>" . $row['plate_number'] . "</td>";
                                        ?>
                                            <td>
                                                <a href="viewDriverCars.php?user_id=<?php echo $id; ?>&status=Registered&car_id=<?php echo $car_id; ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-eye"></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                            </td>

                                        <?php
                                        }
                                        ?>

                                        <?php
                                        echo "</tr>";
                                        $cnt++;

                                        ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="card-footer small text-muted">
                            <?php
                            date_default_timezone_set("Africa/Nairobi");
                            echo "Generated : " . date("h:i:sa");
                            ?>
                        </div>
                    </div>
                <?php

                } //denied
                if (isset($_GET['list']) && $_GET['list'] === 'Declined') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Driver</a>
                        </li>
                        <li class="breadcrumb-item active">Declined Cars</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Manage Cars
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Car Photo</th>
                                            <th>Vehicle Type</th>
                                            <th>Brand, Model, & Color</th>
                                            <th>OR No.</th>
                                            <th>CR No.</th>
                                            <th>Plate No.</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];

                                        $ret = "SELECT car.*, user_profile.*, car_identification.*, city.city_name
                                            FROM car
                                            INNER JOIN user_profile ON car.user_id = user_profile.user_id
                                            INNER JOIN car_identification ON car.car_id = car_identification.car_id
                                            INNER JOIN city ON user_profile.city_id = city.city_id
                                            WHERE car.car_status = 'Denied' AND user_profile.user_id = '$user_id'";
                                        $stmt = $db->prepare($ret);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;

                                        while ($row = $result->fetch_assoc()) {
                                            $id = $row['user_id'];
                                            $car_id = $row['car_id'];
                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td><img src='../assets/img/car/" . $row['car_photo'] . "' alt='Profile Photo' width='50' height='50'></td>";
                                            echo "<td>" . $row['type'] . "</td>";
                                            echo "<td>" . $row['brand'] . " " . $row['model'] . " " . $row['color'] . "</td>";
                                            echo "<td>" . $row['or_number'] . "</td>";
                                            echo "<td>" . $row['cr_number'] . "</td>";
                                            echo "<td>" . $row['plate_number'] . "</td>";
                                        ?>
                                            <td>
                                                <a href="viewDriverCars.php?user_id=<?php echo $id; ?>&status=Registered&car_id=<?php echo $car_id; ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-eye"></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                            </td>

                                        <?php
                                        }
                                        ?>

                                        <?php
                                        echo "</tr>";
                                        $cnt++;

                                        ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="card-footer small text-muted">
                            <?php
                            date_default_timezone_set("Africa/Nairobi");
                            echo "Generated : " . date("h:i:sa");
                            ?>
                        </div>
                    </div>
                <?php
                }
                if (isset($_GET['list']) && $_GET['list'] === 'Expired') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Driver</a>
                        </li>
                        <li class="breadcrumb-item active">Expired Cars</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Manage Cars
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Car Photo</th>
                                            <th>Vehicle Type</th>
                                            <th>Brand, Model, & Color</th>
                                            <th>OR No.</th>
                                            <th>CR No.</th>
                                            <th>Plate No.</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];

                                        $ret = "SELECT car.*, user_profile.*, car_identification.*, city.city_name
                                                FROM car
                                                INNER JOIN user_profile ON car.user_id = user_profile.user_id
                                                INNER JOIN car_identification ON car.car_id = car_identification.car_id
                                                INNER JOIN city ON user_profile.city_id = city.city_id
                                                WHERE car.car_status = 'Expired' AND user_profile.user_id = '$user_id'";
                                        $stmt = $db->prepare($ret);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;

                                        while ($row = $result->fetch_assoc()) {
                                            $id = $row['user_id'];
                                            $car_id = $row['car_id'];
                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td><img src='../assets/img/car/" . $row['car_photo'] . "' alt='Profile Photo' width='50' height='50'></td>";
                                            echo "<td>" . $row['type'] . "</td>";
                                            echo "<td>" . $row['brand'] . " " . $row['model'] . " " . $row['color'] . "</td>";
                                            echo "<td>" . $row['or_number'] . "</td>";
                                            echo "<td>" . $row['cr_number'] . "</td>";
                                            echo "<td>" . $row['plate_number'] . "</td>";
                                        ?>
                                            <td>
                                                <a href="viewDriverCars.php?user_id=<?php echo $id; ?>&status=Registered&car_id=<?php echo $car_id; ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-eye"></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                                <a href="viewDriverCars.php?user_id=<?php echo $id; ?>&status=Registered&car_id=<?php echo $car_id; ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-car"></i>&nbsp;Renew&nbsp;&nbsp;</button>
                                                </a>
                                            </td>

                                        <?php
                                        }
                                        ?>

                                        <?php
                                        echo "</tr>";
                                        $cnt++;

                                        ?>
                                    </tbody>

                                </table>
                            </div>
                        </div>
                        <div class="card-footer small text-muted">
                            <?php
                            date_default_timezone_set("Africa/Nairobi");
                            echo "Generated : " . date("h:i:sa");
                            ?>
                        </div>
                    </div>
                <?php
                }
                ?>


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

</body>

</html>