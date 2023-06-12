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
                        <a href="#">Manage Wallets</a>
                    </li>
                    <li class="breadcrumb-item active">Cash-Outs</li>
                </ol>
                <hr>
                <div class="card">
                    <div class="card-header">
                        <b>Add Reference Number</b>
                    </div>
                    <div class="card-body">
                        <!-- Add User Form -->
                        <form method="POST">
                            <div class="form-group">
                                <label for="reference_number">Reference Number</label>
                                <input type="text" class="form-control" name="reference_number" placeholder="Enter Reference Number" required><br>
                            </div>
                            <?php
                            if (isset($_POST['submit'])) {
                                if (isset($_GET['cico_id']) && isset($_GET['ticket_amount']) && isset($_GET['processing_fee'])) {
                                    $cico_id = $_GET['cico_id'];
                                    $ticket_amount = $_GET['ticket_amount'];
                                    $processing_fee = $_GET['processing_fee'];

                                    // Retrieve the user_id associated with the cico_id
                                    $stmt = $db->prepare("SELECT user_id FROM cico WHERE cico_id = ?");
                                    $stmt->bind_param("i", $cico_id);
                                    $stmt->execute();
                                    $stmt->bind_result($user_id);
                                    $stmt->fetch();
                                    $stmt->close();

                                    // Retrieve the available ticket balance for the user
                                    $stmt = $db->prepare("SELECT ticket_balance FROM user_profile WHERE user_id = ?");
                                    $stmt->bind_param("i", $user_id);
                                    $stmt->execute();
                                    $stmt->bind_result($availableBalance);
                                    $stmt->fetch();
                                    $stmt->close();

                                    $status = "Successful";
                                    $referenceNumber = $_POST['reference_number'];
                                    $stmt = $db->prepare("UPDATE cico SET trans_stat = ?, reference_number = ? WHERE cico_id = ?");
                                    $stmt->bind_param("ssi", $status, $referenceNumber, $cico_id);
                                    $result = $stmt->execute();
                                    $stmt->close();

                                    if ($result) {
                                        $newBalance = $availableBalance - ($ticket_amount + $processing_fee);

                                        $stmt = $db->prepare("UPDATE user_profile SET ticket_balance = ? WHERE user_id = ?");
                                        $stmt->bind_param("di", $newBalance, $user_id);
                                        $stmt->execute();
                                        $stmt->close();
                            ?>
                                        <script>
                                            window.location.href = '../user/cash-out-manage.php';
                                        </script>
                            <?php
                                    } else {
                                        echo '<div style="text-align: center;"><h5 style="color: red; font-size: 18px;">Failed</h5></div>';
                                    }
                                }
                            }
                            ?>



                            <button type="submit" name="submit" class="btn btn-success">Add & Approve</button>
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