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

    <title>City Users</title>

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
                if (isset($_GET['list']) && $_GET['list'] === 'All') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Route</a>
                        </li>
                        <li class="breadcrumb-item active">View All Routes</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            All Routes
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
                                        $ret = "SELECT * FROM route WHERE route_status = 'Active' OR route_status = 'Fully Booked'";
                                        $stmt = $db->prepare($ret);
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

                                            // Check if the user has already booked the route
                                            $checkBookingSql = "SELECT * FROM booking WHERE user_id = ? AND seat_id IN (SELECT seat_id FROM seat WHERE route_id = ?) AND booking_status = 'Pending' OR booking_status = 'Booked'";
                                            $stmt = $db->prepare($checkBookingSql);
                                            $stmt->bind_param("ii", $user_id, $route_id);
                                            $stmt->execute();
                                            $existingBooking = $stmt->get_result()->fetch_assoc();

                                            if ($existingBooking) {
                                                echo "<td>Booked</td>";
                                            } else {
                                                echo "<td>" . $row['route_status'] . "</td>";
                                            }
                                        ?>
                                            <td>
                                                <a href="viewRoute.php?route_id=<?php echo $row['route_id']; ?>&list=All&car_id=<?php echo $car_id ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-eye"></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                                <?php if ($existingBooking) { ?>
                                                    <button disabled>&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;Booked&nbsp;&nbsp;</button>
                                                <?php } else { ?>
                                                    <a href="bookRoute.php?route_id=<?php echo $row['route_id']; ?>&list=All&car_id=<?php echo $car_id ?>">
                                                        <button>&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;Book&nbsp;&nbsp;</button>
                                                    </a>
                                                <?php } ?>
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


                <?php
                } else if (isset($_GET['list']) && $_GET['list'] === 'From') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Routes</a>
                        </li>
                        <li class="breadcrumb-item active">From My City</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Routes From My City
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
                                        $city_name = $_SESSION['city_name'];
                                        $ret = "SELECT * FROM route WHERE pickup_loc LIKE '%$city_name%'";
                                        $stmt = $db->prepare($ret);
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
                                                <a href="viewRoute.php?route_id=<?php echo $row['route_id']; ?>&list=From&car_id=<?php echo $car_id ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-eye"></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                                <a href="bookRoute.php?route_id=<?php echo $row['route_id']; ?>&list=From&car_id=<?php echo $car_id ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;Book&nbsp;&nbsp;</button>
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
                } else if (isset($_GET['list']) && $_GET['list'] === 'To') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Routes</a>
                        </li>
                        <li class="breadcrumb-item active">To My City</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Routes To My City
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
                                        $city_name = $_SESSION['city_name'];
                                        $ret = "SELECT * FROM route WHERE dropoff_loc LIKE '%$city_name%'";
                                        $stmt = $db->prepare($ret);
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
                                                <a href="viewRoute.php?route_id=<?php echo $row['route_id']; ?>&list=To&car_id=<?php echo $car_id ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-eye"></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                                <a href="bookRoute.php?route_id=<?php echo $row['route_id']; ?>&list=To&car_id=<?php echo $car_id ?>">
                                                    <button>&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;Book&nbsp;&nbsp;</button>
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
                }
                ?>
                <!-- /.container-fluid -->

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

</body>

</html>