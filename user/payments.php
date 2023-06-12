<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
}

include('../db.php');

$userid = $_SESSION['user_id'];

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

if (isset($_POST['submit'])) {
    $pswd1 = $db->real_escape_string($_POST['pswd1']);
    $pswd2 = password_hash($_POST['pswd2'], PASSWORD_DEFAULT);

    $stmt = $db->prepare("SELECT * FROM user_profile WHERE user_id = ?");
    $stmt->bind_param("s", $userid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($pswd1, $user['pswd'])) {
            $stmt = $db->prepare("UPDATE user_profile SET pswd = ? WHERE user_id = ?");
            $stmt->bind_param("ss", $pswd2, $userid);
            $result = $stmt->execute();

            if ($result) {
                echo '<div style="text-align: center;"><h5 style="color: green">Password Changed</h5></div>';
            } else {
                echo '<div style="text-align: center;"><h5 style="color: red">Password Change Failed</h5></div>';
            }
        } else {
            echo '<div style="text-align: center;"><h5 style="color: red">Current Password is Incorrect</h5></div>';
        }
    }
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

    <title>Dashboard</title>

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
                if ($_SESSION['role'] == "City Admin" || $_SESSION['role'] == "Main Admin") {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Transactions</a>
                        </li>
                        <li class="breadcrumb-item active">Payments</li>
                    </ol>

                    <!--Bookings-->
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Payments History
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Transaction #</th>
                                            <th>Payment From</th>
                                            <th>Destination</th>
                                            <th>Ticket Amount</th>
                                            <th>Payment To</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $query = "SELECT p.*, b.booking_id, u.first_name, u.last_name, r.dropoff_loc
                                        FROM payment p
                                        INNER JOIN booking b ON p.booking_id = b.booking_id
                                        INNER JOIN seat s ON b.seat_id = s.seat_id
                                        INNER JOIN route r ON s.route_id = r.route_id
                                        INNER JOIN user_profile u ON b.user_id = u.user_id
                                        WHERE p.payment_status = 'Paid'
                                        ORDER BY p.created_at ASC";

                                        $stmt = $db->prepare($query);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $count = 1;

                                        $totalPesoAmount = 0;
                                        $totalTicketAmount = 0;
                                        $totalConvenienceFee = 0;

                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $count . "</td>";
                                            echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                                            echo "<td>" . $row['dropoff_loc'] . "</td>";
                                            echo "<td>" . number_format($row['ticket_amount'], 2) . "</td>";
                                            echo "<td>" . $row['payment_to'] . "</td>";
                                            echo "<td>" . $row['payment_status'] . "</td>";
                                            echo "<td>" . $row['date'] . "</td>";
                                            echo "</tr>";

                                            $totalTicketAmount += $row['ticket_amount'];
                                            $count++;
                                        }
                                        ?>

                                        <!-- ... your HTML code ... -->

                                    <tfoot>
                                        <tr>
                                            <td colspan="3"><b>Total Amount</b></td>
                                            <td colspan="1"><?php echo number_format($totalTicketAmount, 2); ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>

                                </table>

                            </div>
                        </div>
                        <div class="card-footer small text-muted">
                            <?php
                            date_default_timezone_set("Africa/Nairobi");
                            echo "Generated: " . date("h:i:sa");
                            ?>
                        </div>
                    </div>

                <?php
                } else if ($_SESSION['role'] == 'Passenger') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Transactions</a>
                        </li>
                        <li class="breadcrumb-item active">Payments</li>
                    </ol>

                    <!--Bookings-->
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Payments History
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Transaction #</th>
                                            <th>Payment From</th>
                                            <th>Destination</th>
                                            <th>Ticket Amount</th>
                                            <th>Payment To</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];
                                        $query = "SELECT p.*, b.booking_id, u.first_name, u.last_name, r.dropoff_loc
                                               FROM payment p
                                               INNER JOIN booking b ON p.booking_id = b.booking_id
                                               INNER JOIN seat s ON b.seat_id = s.seat_id
                                               INNER JOIN route r ON s.route_id = r.route_id
                                               INNER JOIN user_profile u ON b.user_id = u.user_id
                                               WHERE p.payment_status = 'Paid' AND b.user_id = $user_id
                                               ORDER BY p.created_at ASC";


                                        $stmt = $db->prepare($query);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $count = 1;

                                        $totalPesoAmount = 0;
                                        $totalTicketAmount = 0;
                                        $totalConvenienceFee = 0;

                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $count . "</td>";
                                            echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                                            echo "<td>" . $row['dropoff_loc'] . "</td>";
                                            echo "<td>" . number_format($row['ticket_amount'], 2) . "</td>";
                                            echo "<td>" . $row['payment_to'] . "</td>";
                                            echo "<td>" . $row['payment_status'] . "</td>";
                                            echo "<td>" . $row['date'] . "</td>";
                                            echo "</tr>";

                                            $totalTicketAmount += $row['ticket_amount'];
                                            $count++;
                                        }
                                        ?>

                                        <!-- ... your HTML code ... -->

                                    <tfoot>
                                        <tr>
                                            <td colspan="3"><b>Total Amount</b></td>
                                            <td colspan="1"><?php echo number_format($totalTicketAmount, 2); ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>

                                </table>

                            </div>
                        </div>
                        <div class="card-footer small text-muted">
                            <?php
                            date_default_timezone_set("Africa/Nairobi");
                            echo "Generated: " . date("h:i:sa");
                            ?>
                        </div>
                    </div>

                <?php
                } else if ($_SESSION['role'] == 'Driver') {
                ?>
                    <!-- Breadcrumbs-->
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="#">Transactions</a>
                        </li>
                        <li class="breadcrumb-item active">Payments</li>
                    </ol>

                    <!--Bookings-->
                    <div class="card mb-3">
                        <div class="card-header">
                            <i class="fas fa-table"></i>
                            Payments History
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Transaction #</th>
                                            <th>Payment From</th>
                                            <th>Destination</th>
                                            <th>Ticket Amount</th>
                                            <th>Payment To</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $user_id = $_SESSION['user_id'];
                                        $query = "SELECT p.*, b.booking_id, u.first_name, u.last_name, r.dropoff_loc
                                        FROM payment p
                                        INNER JOIN booking b ON p.booking_id = b.booking_id
                                        INNER JOIN user_profile u ON b.user_id = u.user_id
                                        INNER JOIN seat s ON b.seat_id = s.seat_id
                                        INNER JOIN route r ON s.route_id = r.route_id
                                        INNER JOIN car c ON r.car_id = c.car_id
                                        WHERE p.payment_status = 'Paid' AND c.user_id = $user_id
                                        ORDER BY p.created_at ASC";

                                        $stmt = $db->prepare($query);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $count = 1;

                                        $totalPesoAmount = 0;
                                        $totalTicketAmount = 0;
                                        $totalConvenienceFee = 0;

                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $count . "</td>";
                                            echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                                            echo "<td>" . $row['dropoff_loc'] . "</td>";
                                            echo "<td>" . number_format($row['ticket_amount'], 2) . "</td>";
                                            echo "<td>" . $row['payment_to'] . "</td>";
                                            echo "<td>" . $row['payment_status'] . "</td>";
                                            echo "<td>" . $row['date'] . "</td>";
                                            echo "</tr>";

                                            $totalPesoAmount += $row['ticket_amount'];
                                            $count++;
                                        }
                                        ?>

                                    <tfoot>
                                        <tr>
                                            <td colspan="3"><b>Total Amount</b></td>
                                            <td colspan="1"><?php echo number_format($totalTicketAmount, 2); ?></td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                    </tfoot>

                                </table>

                            </div>
                        </div>
                        <div class="card-footer small text-muted">
                            <?php
                            date_default_timezone_set("Africa/Nairobi");
                            echo "Generated: " . date("h:i:sa");
                            ?>
                        </div>
                    </div>

                <?php
                }
                ?>
            </div>


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