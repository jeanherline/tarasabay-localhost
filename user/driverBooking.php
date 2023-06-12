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

    <title>Booking</title>

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
                if (isset($_GET['list']) && $_GET['list'] === 'Pending') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="">Route</a>
                        </li>
                        <li class="breadcrumb-item active">Pending Booking</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Manage Booking
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Name</th>
                                            <th>Seat Type</th>
                                            <th>Fare</th>
                                            <th>Pickup Location</th>
                                            <th>Drop-Off Location</th>
                                            <th>Departure</th>
                                            <th>Est Arrival Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];

                                        $ret = "SELECT r.*, up.first_name, up.last_name, s.* FROM route r
                                                INNER JOIN car c ON r.car_id = c.car_id
                                                INNER JOIN user_profile up ON c.user_id = up.user_id
                                                INNER JOIN seat s ON r.route_id = s.route_id
                                                INNER JOIN booking b ON s.seat_id = b.seat_id
                                                WHERE c.user_id = ?  AND b.booking_status = 'Pending'";

                                        $stmt = $db->prepare($ret);
                                        $stmt->bind_param("i", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;

                                        while ($row = $result->fetch_assoc()) {
                                            $seat_id = $row['seat_id'];

                                            $passenger_user_id = null;
                                            $seat_user_id_query = "SELECT user_id FROM booking WHERE seat_id = ?";
                                            $seat_stmt = $db->prepare($seat_user_id_query);
                                            $seat_stmt->bind_param("i", $seat_id);
                                            $seat_stmt->execute();
                                            $seat_result = $seat_stmt->get_result();
                                            if ($seat_row = $seat_result->fetch_assoc()) {
                                                $passenger_user_id = $seat_row['user_id'];
                                            }

                                            $passenger_profile_query = "SELECT * FROM user_profile WHERE user_id = ?";
                                            $passenger_profile_stmt = $db->prepare($passenger_profile_query);
                                            $passenger_profile_stmt->bind_param("i", $passenger_user_id);
                                            $passenger_profile_stmt->execute();
                                            $passenger_profile_result = $passenger_profile_stmt->get_result();
                                            if ($passenger_profile_row = $passenger_profile_result->fetch_assoc()) {
                                                $passenger_first_name = $passenger_profile_row['first_name'];
                                                $passenger_last_name = $passenger_profile_row['last_name'];
                                            }
                                
                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td>" .  $passenger_first_name . " " . $passenger_last_name . "</td>";;
                                            echo "<td>" . $row['seat_type'] . "</td>";
                                            echo "<td>" . $row['fare'] . "</td>";
                                            echo "<td>" . substr($row['pickup_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . substr($row['dropoff_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . date('F j, Y h:i A', strtotime($row['departure'])) . "</td>";
                                            echo "<td>" . date('h:i A', strtotime($row['est_arrival_time'])) . "</td>";
                                            echo "<td>
                                                <a href='viewRoute.php?user_id=" . $user_id . "&route_id=" . $row['route_id'] . "&list=PendingBooking&car_id=" . $row['car_id'] . "'>
                                                    <button>&nbsp;&nbsp;<i class='fa fa-eye'></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                                <a href='approvePassengerBooking.php?user_id=" . $passenger_user_id . "&route_id=" . $row['route_id'] . "&list=PendingBooking&seat_id=" . $seat_id . "'>
                                                    <button>&nbsp;&nbsp;<i class='fa fa-check'></i>&nbsp;Approve&nbsp;&nbsp;</button>
                                                </a>
                                                <a href='declinePassengerBooking.php?user_id=" . $passenger_user_id . "&route_id=" . $row['route_id'] . "&list=PendingBooking&seat_id=" . $seat_id . "'>
                                                <button>&nbsp;&nbsp;<i class='fa fa-ban'></i>&nbsp;Decline&nbsp;&nbsp;</button>
                                            </a>
                                            </td>";
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
                } elseif (isset($_GET['list']) && $_GET['list'] === 'Approved') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="">Route</a>
                        </li>
                        <li class="breadcrumb-item active">Approved Booking</li>
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
                                            <th>Name</th>
                                            <th>Seat Type</th>
                                            <th>Fare</th>
                                            <th>Pickup Location</th>
                                            <th>Drop-Off Location</th>
                                            <th>Departure</th>
                                            <th>Est Arrival Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];

                                        $ret = "SELECT r.*, up.first_name, up.last_name, s.* FROM route r
                                                INNER JOIN car c ON r.car_id = c.car_id
                                                INNER JOIN user_profile up ON c.user_id = up.user_id
                                                INNER JOIN seat s ON r.route_id = s.route_id
                                                INNER JOIN booking b ON s.seat_id = b.seat_id
                                                WHERE c.user_id = ? AND b.booking_status = 'Approved'";

                                        $stmt = $db->prepare($ret);
                                        $stmt->bind_param("i", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;

                                        while ($row = $result->fetch_assoc()) {
                                            $seat_id = $row['seat_id'];

                                            $passenger_user_id = null;
                                            $seat_user_id_query = "SELECT user_id FROM booking WHERE seat_id = ?";
                                            $seat_stmt = $db->prepare($seat_user_id_query);
                                            $seat_stmt->bind_param("i", $seat_id);
                                            $seat_stmt->execute();
                                            $seat_result = $seat_stmt->get_result();
                                            if ($seat_row = $seat_result->fetch_assoc()) {
                                                $passenger_user_id = $seat_row['user_id'];
                                            }

                                            $passenger_profile_query = "SELECT * FROM user_profile WHERE user_id = ?";
                                            $passenger_profile_stmt = $db->prepare($passenger_profile_query);
                                            $passenger_profile_stmt->bind_param("i", $passenger_user_id);
                                            $passenger_profile_stmt->execute();
                                            $passenger_profile_result = $passenger_profile_stmt->get_result();
                                            if ($passenger_profile_row = $passenger_profile_result->fetch_assoc()) {
                                                $passenger_first_name = $passenger_profile_row['first_name'];
                                                $passenger_last_name = $passenger_profile_row['last_name'];
                                            }

                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td>" .  $passenger_first_name . " " . $passenger_last_name . "</td>";
                                            echo "<td>" . $row['seat_type'] . "</td>";
                                            echo "<td>" . $row['fare'] . "</td>";
                                            echo "<td>" . substr($row['pickup_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . substr($row['dropoff_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . date('F j, Y h:i A', strtotime($row['departure'])) . "</td>";
                                            echo "<td>" . date('h:i A', strtotime($row['est_arrival_time'])) . "</td>";
                                            echo "<td>
                                                <a href='viewRoute.php?user_id=" . $user_id . "&route_id=" . $row['route_id'] . "&list=PendingBooking&car_id=" . $row['car_id'] . "'>
                                                    <button>&nbsp;&nbsp;<i class='fa fa-eye'></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                            </td>";
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
                <?php include("vendor/inc/footer.php");
                } elseif (isset($_GET['list']) && $_GET['list'] === 'Previous') {
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
                                            <th>Name</th>
                                            <th>Seat Type</th>
                                            <th>Fare</th>
                                            <th>Pickup Location</th>
                                            <th>Drop-Off Location</th>
                                            <th>Departure</th>
                                            <th>Est Arrival Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];

                                        $ret = "SELECT r.*, up.first_name, up.last_name, s.* FROM route r
                                                INNER JOIN car c ON r.car_id = c.car_id
                                                INNER JOIN user_profile up ON c.user_id = up.user_id
                                                INNER JOIN seat s ON r.route_id = s.route_id
                                                INNER JOIN booking b ON s.seat_id = b.seat_id
                                                WHERE c.user_id = ? AND r.route_status = 'Done'";

                                        $stmt = $db->prepare($ret);
                                        $stmt->bind_param("i", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;

                                        while ($row = $result->fetch_assoc()) {
                                            $seat_id = $row['seat_id'];

                                            $passenger_user_id = null;
                                            $seat_user_id_query = "SELECT user_id FROM booking WHERE seat_id = ?";
                                            $seat_stmt = $db->prepare($seat_user_id_query);
                                            $seat_stmt->bind_param("i", $seat_id);
                                            $seat_stmt->execute();
                                            $seat_result = $seat_stmt->get_result();
                                            if ($seat_row = $seat_result->fetch_assoc()) {
                                                $passenger_user_id = $seat_row['user_id'];
                                            }

                                            $passenger_profile_query = "SELECT * FROM user_profile WHERE user_id = ?";
                                            $passenger_profile_stmt = $db->prepare($passenger_profile_query);
                                            $passenger_profile_stmt->bind_param("i", $passenger_user_id);
                                            $passenger_profile_stmt->execute();
                                            $passenger_profile_result = $passenger_profile_stmt->get_result();
                                            if ($passenger_profile_row = $passenger_profile_result->fetch_assoc()) {
                                                $passenger_first_name = $passenger_profile_row['first_name'];
                                                $passenger_last_name = $passenger_profile_row['last_name'];
                                            }

                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td>" . $passenger_profile_row['first_name'] . " " . $passenger_profile_row['last_name'] . "</td>";
                                            echo "<td>" . $row['seat_type'] . "</td>";
                                            echo "<td>" . $row['fare'] . "</td>";
                                            echo "<td>" . substr($row['pickup_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . substr($row['dropoff_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . date('F j, Y h:i A', strtotime($row['departure'])) . "</td>";
                                            echo "<td>" . date('h:i A', strtotime($row['est_arrival_time'])) . "</td>";
                                            echo "<td>
                                                <a href='viewRoute.php?user_id=" . $user_id . "&route_id=" . $row['route_id'] . "&list=PreviousBooking&car_id=" . $row['car_id'] . "'>
                                                    <button>&nbsp;&nbsp;<i class='fa fa-eye'></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                            </td>";
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
                } elseif (isset($_GET['list']) && $_GET['list'] === 'Declined') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="">Route</a>
                        </li>
                        <li class="breadcrumb-item active">Declined Route</li>
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
                                            <th>Name</th>
                                            <th>Seat Type</th>
                                            <th>Fare</th>
                                            <th>Pickup Location</th>
                                            <th>Drop-Off Location</th>
                                            <th>Departure</th>
                                            <th>Est Arrival Time</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];

                                        $ret = "SELECT r.*, up.first_name, up.last_name, s.* FROM route r
                                                INNER JOIN car c ON r.car_id = c.car_id
                                                INNER JOIN user_profile up ON c.user_id = up.user_id
                                                INNER JOIN seat s ON r.route_id = s.route_id
                                                INNER JOIN booking b ON s.seat_id = b.seat_id
                                                WHERE c.user_id = ? AND b.booking_status = 'Declined'";

                                        $stmt = $db->prepare($ret);
                                        $stmt->bind_param("i", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;

                                        while ($row = $result->fetch_assoc()) {
                                            $seat_id = $row['seat_id'];

                                            // Fetch the passenger's user_id from the seat table
                                            $passenger_user_id = null;
                                            $seat_user_id_query = "SELECT user_id FROM booking WHERE seat_id = ?";
                                            $seat_stmt = $db->prepare($seat_user_id_query);
                                            $seat_stmt->bind_param("i", $seat_id);
                                            $seat_stmt->execute();
                                            $seat_result = $seat_stmt->get_result();
                                            if ($seat_row = $seat_result->fetch_assoc()) {
                                                $passenger_user_id = $seat_row['user_id'];
                                            }

                                            $passenger_profile_query = "SELECT * FROM user_profile WHERE user_id = ?";
                                            $passenger_profile_stmt = $db->prepare($passenger_profile_query);
                                            $passenger_profile_stmt->bind_param("i", $passenger_user_id);
                                            $passenger_profile_stmt->execute();
                                            $passenger_profile_result = $passenger_profile_stmt->get_result();
                                            if ($passenger_profile_row = $passenger_profile_result->fetch_assoc()) {
                                                $passenger_first_name = $passenger_profile_row['first_name'];
                                                $passenger_last_name = $passenger_profile_row['last_name'];
                                            }
                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td>" .  $passenger_first_name . " " . $passenger_last_name . "</td>";;
                                            echo "<td>" . $row['seat_type'] . "</td>";
                                            echo "<td>" . $row['fare'] . "</td>";
                                            echo "<td>" . substr($row['pickup_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . substr($row['dropoff_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . date('F j, Y h:i A', strtotime($row['departure'])) . "</td>";
                                            echo "<td>" . date('h:i A', strtotime($row['est_arrival_time'])) . "</td>";
                                            echo "<td>
                                                <a href='viewRoute.php?user_id=" . $user_id . "&route_id=" . $row['route_id'] . "&list=PendingBooking&car_id=" . $row['car_id'] . "'>
                                                    <button>&nbsp;&nbsp;<i class='fa fa-eye'></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                            </td>";
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