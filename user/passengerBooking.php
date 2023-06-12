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
                if (isset($_GET['list']) && $_GET['list'] === 'Booked') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Bookings</a>
                        </li>
                        <li class="breadcrumb-item active">Booked Routes</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            All Booked Routes
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Pick-up Location</th>
                                            <th>Drop-off Location</th>
                                            <th>Seat Type</th>
                                            <th>Fare</th>
                                            <th>Route Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];
                                        $ret = "SELECT s.*, b.*, up.*, r.*
                                                FROM seat s
                                                INNER JOIN booking b ON s.seat_id = b.seat_id
                                                INNER JOIN user_profile up ON b.user_id = up.user_id
                                                INNER JOIN route r ON s.route_id = r.route_id
                                                WHERE b.user_id = ? AND (b.booking_status = 'Pending' OR b.booking_status = 'Approved'
                                                OR r.route_status = 'Start')";
                                        $stmt = $db->prepare($ret);
                                        $stmt->bind_param("i", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            $seat_id = $row['seat_id'];
                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td>" . substr($row['pickup_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . substr($row['dropoff_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . $row['seat_type'] . "</td>";
                                            echo "<td>" . $row['fare'] . "</td>";
                                            echo "<td>" . $row['route_status'] . "</td>";
                                            echo "<td>";

                                            if ($row['route_status'] == 'Start' && $row['booking_status'] == 'Picked-up') {
                                                echo "
                                                                        <a href='viewRoute.php?route_id=" . $row['route_id'] . "&list=Booked&car_id=" . $row['car_id'] . "'>
                                                                            <button>&nbsp;&nbsp;<i class='fa fa-eye'></i>&nbsp;View&nbsp;&nbsp;</button>
                                                                        </a>
                                                                        <a href='droppedoff.php?seat_id=" . $seat_id . "&user_id=" . $user_id . "&list=passengerBooking.php?list=Booked" . "&car_id=" . $row['car_id'] . "' onclick=\"return confirm('Are you sure you want to mark as picked-up?')\">
                                                                            <button>&nbsp;&nbsp;<i class='fa fa-check' style='color: green;'></i>&nbsp;Dropped-Off&nbsp;&nbsp;</button>
                                                                        </a>
                                                                        ";
                                            } else if ($row['route_status'] == 'Start' && $row['booking_status'] == 'Dropped-off') {
                                                echo "
                                                                        <a href='viewRoute.php?route_id=" . $row['route_id'] . "&list=Booked&car_id=" . $row['car_id'] . "'>
                                                                            <button>&nbsp;&nbsp;<i class='fa fa-eye'></i>&nbsp;View&nbsp;&nbsp;</button>
                                                                        </a>
                                                                        ";
                                            } else if ($row['route_status'] == 'Start' && $row['booking_status'] == 'Approved') {
                                                echo "
                                                                        <a href='viewRoute.php?route_id=" . $row['route_id'] . "&list=Booked&car_id=" . $row['car_id'] . "'>
                                                                            <button>&nbsp;&nbsp;<i class='fa fa-eye'></i>&nbsp;View&nbsp;&nbsp;</button>
                                                                        </a>
                                                                        <a href='pickedup.php?seat_id=" . $seat_id . "&user_id=" . $user_id . "&list=passengerBooking.php?list=Booked" . "&car_id=" . $row['car_id'] . "' onclick=\"return confirm('Are you sure you want to mark as picked-up?')\">
                                                                        <button>&nbsp;&nbsp;<i class='fa fa-check' style='color: green;'></i>&nbsp;Picked-Up&nbsp;&nbsp;</button>
                                                                    </a>";
                                            } else if ($row['route_status'] == 'Done' && $row['booking_status'] == 'Dropped-off') {
                                                echo "
                                                                        <a href='viewRoute.php?route_id=" . $row['route_id'] . "&list=Booked&car_id=" . $row['car_id'] . "'>
                                                                            <button>&nbsp;&nbsp;<i class='fa fa-eye'></i>&nbsp;View&nbsp;&nbsp;</button>
                                                                    </a>";
                                            } else {
                                                echo "
                                                                        <a href='viewRoute.php?route_id=" . $row['route_id'] . "&list=Booked&car_id=" . $row['car_id'] . "'>
                                                                            <button>&nbsp;&nbsp;<i class='fa fa-eye'></i>&nbsp;View&nbsp;&nbsp;</button>
                                                                        </a>
                                                                        <a href='cancelPassengerRoute.php?seat_id=" . $seat_id . "&user_id=" . $user_id . "&route_id=" . $row['route_id'] . "&list=Booked&car_id=" . $row['car_id'] . "' onclick=\"return confirm('Are you sure you want to cancel?')\">
                                                                            <button>&nbsp;&nbsp;<i class='fa fa-ban'></i>&nbsp;Cancel&nbsp;&nbsp;</button>
                                                                        </a>";
                                            }
                                            echo "</td>";
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
                } else if (isset($_GET['list']) && $_GET['list'] === 'Previous') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Routes</a>
                        </li>
                        <li class="breadcrumb-item active">Previous Bookings</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Previous Bookings
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Pick-up Location</th>
                                            <th>Drop-off Location</th>
                                            <th>Seat Type</th>
                                            <th>Fare</th>
                                            <th>Route Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];
                                        $ret = "SELECT s.*, b.*, up.*, r.*
                                                FROM seat s
                                                INNER JOIN booking b ON s.seat_id = b.seat_id
                                                INNER JOIN user_profile up ON b.user_id = up.user_id
                                                INNER JOIN route r ON s.route_id = r.route_id
                                                WHERE b.user_id = ? AND r.route_status = 'Done'";
                                        $stmt = $db->prepare($ret);
                                        $stmt->bind_param("i", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            $booking_id = $row['booking_id'];

                                            $review_query = "SELECT * FROM review WHERE booking_id = ?";
                                            $review_stmt = $db->prepare($review_query);
                                            $review_stmt->bind_param("i", $booking_id);
                                            $review_stmt->execute();
                                            $review_result = $review_stmt->get_result();
                                            $review_exists = $review_result->num_rows > 0;

                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td>" . substr($row['pickup_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . substr($row['dropoff_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . $row['seat_type'] . "</td>";
                                            echo "<td>" . $row['fare'] . "</td>";
                                            echo "<td>" . $row['route_status'] . "</td>";
                                            echo "<td>";

                                            // Output different button based on review existence
                                            if ($review_exists) {
                                                echo "<a href='viewRoute.php?route_id=" . $row['route_id'] . "&list=Booked&car_id=" . $row['car_id'] . "'>
                                                <button>&nbsp;&nbsp;<i class='fa fa-eye'></i>&nbsp;View&nbsp;&nbsp;</button>
                                            </a>
                                            <button disabled>&nbsp;&nbsp;<i class='fa fa-check' style='color: green;'></i>&nbsp;Write A Driver Review&nbsp;&nbsp;</button>";
                                            } else {
                                                echo "
                                                <a href='viewRoute.php?route_id=" . $row['route_id'] . "&list=Booked&car_id=" . $row['car_id'] . "'>
                                                    <button>&nbsp;&nbsp;<i class='fa fa-eye'></i>&nbsp;View&nbsp;&nbsp;</button>
                                                </a>
                                                <a href='writeReview.php?booking_id=" . $row['booking_id']  . "'>
                                                    <button>&nbsp;&nbsp;<i class='fa fa-check' style='color: green;'></i>&nbsp;Write A Driver Review&nbsp;&nbsp;</button>
                                                </a>";
                                            }

                                            echo "</td>";
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
                } else if (isset($_GET['list']) && $_GET['list'] === 'Cancelled') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Routes</a>
                        </li>
                        <li class="breadcrumb-item active">Cancelled Bookings</li>
                    </ol>
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Cancelled Bookings
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Pick-up Location</th>
                                            <th>Drop-off Location</th>
                                            <th>Seat Type</th>
                                            <th>Fare</th>
                                            <th>Booking Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];
                                        $ret = "SELECT s.*, b.*, up.*, r.*
                                                FROM seat s
                                                INNER JOIN booking b ON s.seat_id = b.seat_id
                                                INNER JOIN user_profile up ON b.user_id = up.user_id
                                                INNER JOIN route r ON s.route_id = r.route_id
                                                WHERE b.user_id = ? AND b.booking_status = 'Cancelled'";
                                        $stmt = $db->prepare($ret);
                                        $stmt->bind_param("i", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $cnt = 1;
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $cnt . "</td>";
                                            echo "<td>" . substr($row['pickup_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . substr($row['dropoff_loc'], 0, 15) . "...</td>";
                                            echo "<td>" . $row['seat_type'] . "</td>";
                                            echo "<td>" . $row['fare'] . "</td>";
                                            echo "<td>" . $row['booking_status'] . "</td>";
                                            echo "<td>
                                                <a href='viewRoute.php?route_id=" . $row['route_id'] . "&list=Booked&car_id=" . $row['car_id'] . "'>
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