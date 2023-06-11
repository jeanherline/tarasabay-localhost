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
<title>Cancel Route</title>

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
                        <a href="driverRoute.php?status=Active">Active Route</a>
                    </li>
                    <li class="breadcrumb-item active">Cancel</li>
                </ol>
                <hr>
                <div class="card">
                    <div class="card-header">
                        <b>Cancel Route</b>
                    </div>
                    <div class="card-body">
                        <form method="POST" onsubmit="return confirm('Are you sure you want to cancel the route?\nNote: The 10 tickets for creating the route will not be refunded.\n');">
                            <div class="form-group">
                                <label for="cancellation_reason">Cancellation Reason</label>
                                <input type="text" class="form-control" name="cancellation_reason" placeholder="Enter Reason" required><br>
                            </div>
                            <?php
                            if (isset($_POST['submit'])) {
                                if (isset($_GET['user_id']) && isset($_GET['car_id']) && isset($_GET['route_id'])) {
                                    $user_id = $_GET['user_id'];
                                    $car_id = $_GET['car_id'];
                                    $route_id = $_GET['route_id'];

                                    // Check if any seat associated with the route has been taken
                                    $seat_taken = false;
                                    $seat_query = "SELECT * FROM seat WHERE route_id = $route_id";
                                    $seat_result = $db->query($seat_query);
                                    if ($seat_result) {
                                        while ($seat_row = $seat_result->fetch_assoc()) {
                                            if ($seat_row['seat_status'] !== 'Available') {
                                                $seat_taken = true;
                                                break;
                                            }
                                        }
                                        $seat_result->free();
                                    }

                                    if ($seat_taken) {
                                        echo '<div style="text-align: center;"><h5 style="color: red; font-size: 18px;">Cancellation is not allowed as some seats have already been booked or the route is already cancelled.</h5></div>';
                                    } else {
                                        $status = "Cancelled";
                                        $cancellation_reason = $_POST['cancellation_reason'];

                                        // Update the route and seat status to Cancelled
                                        $route_update_sql = "UPDATE route SET route_status = '$status', cancellation_reason = '$cancellation_reason' WHERE route_id = $route_id";
                                        $route_result = $db->query($route_update_sql);

                                        $seat_update_sql = "UPDATE seat SET seat_status = '$status' WHERE route_id = $route_id";
                                        $seat_result = $db->query($seat_update_sql);

                                        if ($route_result && $seat_result) {
                                            echo '<div style="text-align: center;"><h5 style="color: green; font-size: 18px;">Route and associated seats have been cancelled.</h5>';
                                        } else {
                                            echo '<div style="text-align: center;"><h5 style="color: red; font-size: 18px;">Failed to cancel the route and associated seats.</h5></div>';
                                        }
                                    }
                                }
                            }
                            ?>
                        </form>


                        <!-- End Form -->
                    </div>

                </div>

                <hr>
                </div>


                <!-- Sticky Footer -->
                <?php include("vendor/inc/footer.php"); ?>

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