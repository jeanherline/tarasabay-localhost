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

    <title>Route</title>

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
                if (isset($_GET['status']) && $_GET['status'] === 'Active') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="">Route</a>
                        </li>
                        <li class="breadcrumb-item active">Active Route</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Manage Routes
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Pickup Location</th>
                                            <th>Drop-Off Location</th>
                                            <th>Departure</th>
                                            <th>Est Arrival Time</th>
                                            <th>Route Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];
                                        $ret = "SELECT * FROM route INNER JOIN car ON route.car_id = car.car_id WHERE (car.user_id = ? AND route.route_status = 'Active')";
                                        $stmt = $db->prepare($ret);
                                        $stmt->bind_param("s", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            $car_id = $row['car_id'];
                                            $route_id = $row['route_id'];
                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td>" . substr($row['pickup_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . substr($row['dropoff_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . date('F j, Y h:i A', strtotime($row['departure'])) . "</td>";
                                            echo "<td>" . date('h:i A', strtotime($row['est_arrival_time'])) . "</td>";
                                            echo "<td>" . $row['route_status'] . "</td>";
                                        ?>
                                            <td>
                                                <a href="viewRoute.php?user_id=<?php echo $row['user_id']; ?>&list=Active&route_id=<?php echo $route_id ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-eye"></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                                <a href="#" onclick="confirmMarkAsDone(<?php echo $row['user_id']; ?>, <?php echo $car_id; ?>, <?php echo $route_id; ?>);">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;Done&nbsp;&nbsp;</button>
                                                </a>

                                                <script>
                                                    function confirmMarkAsDone(user_id, car_id, route_id) {
                                                        if (confirm("Are you sure you want to mark this as done?")) {
                                                            window.location.href = "previousRoute.php?user_id=" + user_id + "&car_id=" + car_id + "&route_id=" + route_id;
                                                        }
                                                    }
                                                </script>

                                                <a href="cancelRoute.php?user_id=<?php echo $row['user_id']; ?>&car_id=<?php echo $car_id ?>&route_id=<?php echo $route_id ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-ban"></i>&nbsp;Cancel&nbsp;&nbsp;</button>
                                                </a>
                                            </td>
                                        <?php
                                            echo "</tr>";
                                            $cnt++;
                                        }
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
                    <!-- /.container-fluid -->

                    <!-- Sticky Footer -->
                    <?php include("vendor/inc/footer.php"); ?>

                <?php
                } elseif (isset($_GET['status']) && $_GET['status'] === 'Previous') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="">Route</a>
                        </li>
                        <li class="breadcrumb-item active">Previous Route</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Manage Routes
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Pickup Location</th>
                                            <th>Drop-Off Location</th>
                                            <th>Departure</th>
                                            <th>Est Arrival Time</th>
                                            <th>Route Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];
                                        $ret = "SELECT * FROM route INNER JOIN car ON route.car_id = car.car_id WHERE (car.user_id = ? AND route.route_status = 'Previous')";
                                        $stmt = $db->prepare($ret);
                                        $stmt->bind_param("s", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            $car_id = $row['car_id'];
                                            $route_id = $row['route_id'];
                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td>" . substr($row['pickup_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . substr($row['dropoff_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . date('F j, Y h:i A', strtotime($row['departure'])) . "</td>";
                                            echo "<td>" . date('h:i A', strtotime($row['est_arrival_time'])) . "</td>";
                                            echo "<td>" . $row['route_status'] . "</td>";
                                        ?>
                                            <td>
                                                <a href="viewRoute.php?user_id=<?php echo $row['user_id']; ?>&list=Previous&route_id=<?php echo $route_id ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-eye"></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                            </td>
                                        <?php
                                            echo "</tr>";
                                            $cnt++;
                                        }
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
                    <!-- /.container-fluid -->

                    <!-- Sticky Footer -->
                    <?php include("vendor/inc/footer.php"); ?>
                <?php
                } elseif (isset($_GET['status']) && $_GET['status'] === 'Cancelled') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="">Route</a>
                        </li>
                        <li class="breadcrumb-item active">Cancelled Route</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Manage Routes
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Pickup Location</th>
                                            <th>Drop-Off Location</th>
                                            <th>Departure</th>
                                            <th>Est Arrival Time</th>
                                            <th>Route Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];
                                        $ret = "SELECT * FROM route INNER JOIN car ON route.car_id = car.car_id WHERE (car.user_id = ? AND route.route_status = 'Cancelled')";
                                        $stmt = $db->prepare($ret);
                                        $stmt->bind_param("s", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            $car_id = $row['car_id'];
                                            $route_id = $row['route_id'];
                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td>" . substr($row['pickup_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . substr($row['dropoff_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . date('F j, Y h:i A', strtotime($row['departure'])) . "</td>";
                                            echo "<td>" . date('h:i A', strtotime($row['est_arrival_time'])) . "</td>";
                                            echo "<td>" . $row['route_status'] . "</td>";
                                        ?>
                                            <td>
                                                <a href="viewRoute.php?user_id=<?php echo $row['user_id']; ?>&list=Cancelled&route_id=<?php echo $route_id ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-eye"></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                            </td>
                                        <?php
                                            echo "</tr>";
                                            $cnt++;
                                        }
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
                    <!-- /.container-fluid -->

                    <!-- Sticky Footer -->
                    <?php include("vendor/inc/footer.php"); ?>
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