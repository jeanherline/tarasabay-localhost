<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
}

include('../db.php');

$userid = $_SESSION['user_id'];

?>
<!DOCTYPE html>
<html lang="en">
<title>Add Reference Number</title>

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
                        <a href="passengerBooking.php?list=Booked">Booked Routes </a>
                    </li>
                    <li class="breadcrumb-item active">Cancel Booking</li>
                </ol>
                <hr>
                <div class="card">
                    <div class="card-header">
                        <b>Cancel Route Booking</b>
                    </div>
                    <div class="card-body">
                        <!-- Add User Form -->
                        <form method="POST">
                            <div class="form-group">
                                <label for="cancellation_reason">Cancellation Reason</label>
                                <input type="text" class="form-control" name="cancellation_reason" placeholder="Enter Cancellation Reason" required><br>
                            </div>
                            <?php
                            include('../db.php');

                            if (isset($_POST['submit'])) {
                                if (isset($_GET['user_id']) && isset($_GET['list']) && isset($_GET['car_id']) && isset($_GET['route_id']) && isset($_GET['seat_id'])) {
                                    $user_id = $_GET['user_id'];
                                    $car_id = $_GET['car_id'];
                                    $route_id = $_GET['route_id'];
                                    $seat_id = $_GET['seat_id'];
                                    $list = $_GET['list'];
                                    $cancellation_reason = $_POST['cancellation_reason'];
                                    $status = "Cancelled";

                                    // Get the fare amount of the booking
                                    $fare_query = "SELECT fare FROM seat WHERE seat_id = ?";
                                    $fare_stmt = $db->prepare($fare_query);
                                    $fare_stmt->bind_param("i", $seat_id);
                                    $fare_stmt->execute();
                                    $fare_result = $fare_stmt->get_result();
                                    if ($fare_row = $fare_result->fetch_assoc()) {
                                        $fare = $fare_row['fare'];
                                    } else {
                                        exit("Fare not found");
                                    }

                                    $updateSeatSql = "UPDATE seat SET seat_status = 'Available' WHERE seat_id = ?";
                                    $stmt = $db->prepare($updateSeatSql);
                                    $stmt->bind_param("i", $seat_id);
                                    $stmt->execute();

                                    $updateDriverSql = "UPDATE user_profile SET ticket_balance = ticket_balance + ? WHERE user_id = ?";
                                    $stmt = $db->prepare($updateDriverSql);
                                    $stmt->bind_param("ii", $fare, $user_id);
                                    $stmt->execute();

                                    $updateBookingSql = "UPDATE booking SET booking_status = 'Cancelled', cancellation_reason = ? WHERE user_id = ? AND seat_id = ?";
                                    $stmt = $db->prepare($updateBookingSql);
                                    $stmt->bind_param("sii", $cancellation_reason, $user_id, $seat_id);
                                    $stmt->execute();

                                    // Check if the route is fully booked
                                    $routeStatusQuery = "SELECT route_status FROM route WHERE route_id = ?";
                                    $routeStatusStmt = $db->prepare($routeStatusQuery);
                                    $routeStatusStmt->bind_param("i", $route_id);
                                    $routeStatusStmt->execute();
                                    $routeStatusResult = $routeStatusStmt->get_result();
                                    if ($routeStatusRow = $routeStatusResult->fetch_assoc()) {
                                        $route_status = $routeStatusRow['route_status'];

                                        if ($route_status === "Fully Booked") {
                                            // Mark route as "Active"
                                            $updateRouteStatusSql = "UPDATE route SET route_status = 'Active' WHERE route_id = ?";
                                            $routeStmt = $db->prepare($updateRouteStatusSql);
                                            $routeStmt->bind_param("i", $route_id);
                                            $routeStmt->execute();
                                        }
                                    }

                                    echo '<script>
                                            window.location.href = "passengerBooking.php?list=Booked";
                                        </script>';
                                    exit();
                                }
                            }
                            ?>
                            <button type="submit" name="submit" class="btn btn-success">Submit</button>
                        </form>



                        <!-- End Form -->
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