<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
}

include('../db.php');

$userid = $_SESSION['user_id'];


?>

<!DOCTYPE html>
<html lang="en">
<title>Cash-out</title>

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
                        <a href="#">Trasactions</a>
                    </li>
                    <li class="breadcrumb-item active">Cash-Out</li>
                </ol>
                <hr>
                <!-- Icon Cards-->
                <div class="row">
                    <div class="col-xl-3 col-sm-6 mb-3">
                        <div class="card text-white" style="background-color: #EAAA00;">
                            <div class="card-body">
                                <div class="card-body-icon">
                                    <i class="fas fa-fw fa-ticket-alt"></i>
                                </div>
                                <?php
                                // Code for counting the ticket balance by user ID
                                $result = $db->query("SELECT acc_balance FROM user_profile WHERE user_id = '$userid'");
                                $ticketBalance = $result->fetch_row()[0];
                                ?>
                                <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $ticketBalance; ?></span> Ticket Balance</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <b>GCash: </b><em>Cash-Out</em>
                    </div>
                    <div class="card-body">
                        <!-- Add User Form -->
                        <form method="POST">
                            <label for="mobile_no" class="form-label"> GCash Mobile Number <span class="text-danger">*</span></label>
                            <input type="text" name="mobile_no" id="mobile_no" minlength="11" maxlength="11" class="form-control" required>
                            <br>
                            <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                            <input type="number" name="amount" id="amount" class="form-control" required>
                            <br>
                            <div id="emailHelp" class="form-text">
                                <b>Note:</b> 1 Ticket = 1 Peso<br><br>
                                Only ₱20.00 fee for every ₱1000.00 Cash Out transaction.<br><br>
                                <em>Example: 2100 tickets = 2100 pesos with Processing fee of 60 tickets.</em>
                            </div>
                            <br><br>
                            <?php
                            if (isset($_POST['submit'])) {
                                $mobileNo = $_POST['mobile_no'];
                                $amount = $_POST['amount'];
                                $processingFee = ceil($amount / 1000) * 20; // Calculate processing fee based on amount

                                // Perform necessary validation and sanitization of the input data
                                // ...

                                // Cash out process for driver
                                $transactionType = 'cash-out';
                                $ticketAmount = $amount + $processingFee;

                                // Check if the driver has enough tickets for cash out
                                if ($ticketBalance >= $ticketAmount) {
                                    // Insert the transaction record into the cico table
                                    $stmt = $db->prepare("INSERT INTO cico (wallet_id, user_id, transaction_type, amount, processing_fee, reference_number, status, created_at, updated_at) VALUES (NULL, ?, ?, ?, ?, '', 'Pending', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
                                    $stmt->bind_param("isdd", $userid, $transactionType, $amount, $processingFee);
                                    $result = $stmt->execute();

                                    if ($result) {
                                        $walletId = $stmt->insert_id;

                                        // Update the user's ticket balance in the user_profile table
                                        $stmt = $db->prepare("UPDATE user_profile SET acc_balance = acc_balance - ? WHERE user_id = ?");
                                        $stmt->bind_param("ii", $ticketAmount, $userid);
                                        $result = $stmt->execute();

                                        if ($result) {
                                            // Insert the transaction record into the transaction table
                                            $stmt = $db->prepare("INSERT INTO transaction (transaction_id, wallet_id, gcash_mobile_number, ticket_amount, peso_amount, transaction_status, created_at, updated_at) VALUES (NULL, ?, ?, ?, ?, 'success', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
                                            $stmt->bind_param("isis", $walletId, $mobileNo, $ticketAmount, $amount);
                                            $result = $stmt->execute();

                                            if ($result) {
                                                // Cash out process is successful
                                                echo '<div style="text-align: center;"><h5 style="color: green; font-size:16px;">Cash Out Successful</h5></div>';
                                            } else {
                                                // Failed to insert into transaction table
                                                echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">Cash Out Failed</h5></div>';
                                            }
                                        } else {
                                            // Failed to update ticket balance in user_profile table
                                            echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">Cash Out Failed</h5></div>';
                                        }
                                    } else {
                                        // Failed to insert into cico table
                                        echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">Cash Out Failed</h5></div>';
                                    }
                                } else {
                                    // Insufficient ticket balance for cash out
                                    echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">Insufficient Ticket Balance for Cash Out</h5></div>';
                                }
                            }
                            ?>
                            <button type="submit" name="submit" class="btn btn-success">Cash Out</button>
                        </form>
                        <!-- End Form-->
                    </div>
                </div>

                <hr>
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