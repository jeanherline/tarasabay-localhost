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
<title>Cash-in</title>

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
                        <?php
                        $list = '';

                        if (isset($_GET['list'])) {
                            $listOption = $_GET['list'];

                            // Check the value of the list option and set the corresponding value for $list
                            if ($listOption === 'All' || $listOption === 'From' || $listOption === 'To') {
                                $list = $listOption;
                            }
                        }
                        ?>
                        <?php
                        if ($list == 'All') {
                        ?>
                            <a href="passengerRoute.php?list=All">View All Routes</a>

                        <?php
                        } else if ($list == 'From') {
                        ?>
                            <a href="passengerRoute.php?list=From">From My City</a>
                        <?php
                        } else if ($list == 'To') {
                        ?>
                            <a href="passengerRoute.php?list=To">To My City</a>
                        <?php
                        }
                        ?>
                    </li>
                    <li class="breadcrumb-item active">Book A Ride</li>

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
                                $result = $db->query("SELECT ticket_balance FROM user_profile WHERE user_id = '$userid'");
                                $ticketBalance = $result->fetch_row()[0];
                                ?>
                                <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><i class="fa fa-ticket"></i>&nbsp;&nbsp;<?php echo $ticketBalance; ?></span> Ticket Balance</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <b>Booking: </b><em>Reserve Your Spot!</em>
                    </div>
                    <div class="card-body">
                        <!-- Add User Form -->
                        <?php
                        if (isset($_GET['route_id']) && isset($_GET['car_id'])) {
                            $route_id = $_GET['route_id'];
                            $car_id = $_GET['car_id'];

                            $ret = "SELECT * FROM route WHERE route_status = 'Active' AND route_id = $route_id AND car_id = $car_id";
                            $stmt = $db->prepare($ret);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $cnt = 1;
                            while ($row = $result->fetch_assoc()) {
                                $pickup_loc = $row['pickup_loc'];
                                $dropoff_loc = $row['dropoff_loc'];
                                $departure = date('F j, Y h:i A', strtotime($row['departure']));
                                $est_arrival_time =  date('h:i A', strtotime($row['est_arrival_time']));
                            }
                        }
                        ?>
                        <form method="POST">
                            <label for="loc" class="form-label">Pick-up Location -> Drop-off Location</label>
                            <input type="text" class="form-control" name="loc" value="<?php echo $pickup_loc . " to " . $dropoff_loc ?>" id="loc" disabled>
                            <br>

                            <label for="departure" class="form-label">Departure</label>
                            <input type="text" class="form-control" name="departure" value="<?php echo $departure ?>" id="departure" disabled>
                            <br>

                            <label for="est_arrival_time" class="form-label">Est. Time of Arrival</label>
                            <input type="text" class="form-control" name="est_arrival_time" value="<?php echo $est_arrival_time ?>" id="est_arrival_time" disabled>
                            <br>
                            <em>Pick Your Seat</em><br><br>
                            <?php
                            $sql = "SELECT * FROM seat WHERE route_id = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param("s", $route_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $numRows = $result->num_rows;

                            if ($numRows > 0) {
                            ?>
                                <select class="form-control" name="seat_id" id="seat_id" aria-label="Default select example">
                                    <?php
                                    while ($row = $result->fetch_assoc()) {
                                        $seat_id = $row['seat_id'];
                                        $seat_type = $row['seat_type'];
                                        $fare = $row['fare'];
                                        $seat_status = $row['seat_status'];
                                    ?>
                                        <option value="<?php echo $seat_id; ?>" <?php echo isset($_POST['seat_id']) && $_POST['seat_id'] == $seat_id ? 'selected' : ''; ?>><?php echo $seat_type; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            <?php
                            }
                            ?>
                            <br>
                            <?php
                            $sql = "SELECT * FROM seat WHERE route_id = ?";
                            $stmt = $db->prepare($sql);
                            $stmt->bind_param("s", $route_id);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $numRows = $result->num_rows;

                            if ($numRows > 0) {
                                $row = $result->fetch_assoc();
                                $fare = $row['fare'];
                            }
                            ?>

                            <label for="fare" class="form-label">Ticket Fare</label>
                            <input type="text" name="fare" id="fare" class="form-control" value="<?php echo $fare ?>" readonly>

                            <script>
                                $(document).ready(function() {
                                    $('#seat_id').on('change', function() {
                                        var selectedSeat = $(this).val();
                                        var selectedFare = '';

                                        <?php
                                        $result->data_seek(0); // Reset the result set pointer
                                        while ($row = $result->fetch_assoc()) {
                                            $seat_id = $row['seat_id'];
                                            $fare = $row['fare'];
                                        ?>
                                            if (selectedSeat == '<?php echo $seat_id; ?>') {
                                                selectedFare = '<?php echo $fare; ?>';
                                            }
                                        <?php
                                        }
                                        ?>

                                        $('#fare').val(selectedFare);
                                    });
                                });
                            </script>

                            <br><br>
                            <?php
                            if (isset($_POST['submit'])) {
                                $seat_id = $_POST['seat_id'];
                                $fare = $_POST['fare'];
                                $status = 'Taken';

                                // Check if the user has already booked a seat for the route
                                $user_id = $_SESSION['user_id'];
                                $checkBookingSql = "SELECT * FROM booking WHERE user_id = ? AND seat_id IN (SELECT seat_id FROM seat WHERE route_id = (SELECT route_id FROM seat WHERE seat_id = ?)) AND (booking_status = 'Booked' OR booking_status = 'Pending')";

                                $stmt = $db->prepare($checkBookingSql);
                                $stmt->bind_param("ii", $user_id, $seat_id);
                                $stmt->execute();
                                $existingBooking = $stmt->get_result()->fetch_assoc();

                                // Check if the user's ticket balance is sufficient
                                $getUserBalanceSql = "SELECT ticket_balance FROM user_profile WHERE user_id = ?";
                                $stmt = $db->prepare($getUserBalanceSql);
                                $stmt->bind_param("i", $user_id);
                                $stmt->execute();
                                $userProfile = $stmt->get_result()->fetch_assoc();
                                $ticketBalance = $userProfile['ticket_balance'];

                                if ($existingBooking) {
                                    echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">You have already booked a seat for this route.</h5></div>';
                                } elseif ($ticketBalance < $fare) {
                                    echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">Insufficient ticket balance.</h5></div>';
                                } elseif ($ticketBalance < 250) {
                                    echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">You must have at least 250 tickets to book a route.</h5></div>';
                                } else {
                                    // Get the seat type of the selected seat
                                    $getSeatTypeSql = "SELECT seat_type FROM seat WHERE seat_id = ?";
                                    $stmt = $db->prepare($getSeatTypeSql);
                                    $stmt->bind_param("i", $seat_id);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $seat = $result->fetch_assoc();
                                    $seat_type = $seat['seat_type'];

                                    // Check if the seat is already taken
                                    $checkSeatStatusSql = "SELECT seat_status FROM seat WHERE route_id = ? AND seat_type = ?";
                                    $stmt = $db->prepare($checkSeatStatusSql);
                                    $stmt->bind_param("is", $route_id, $seat_type);
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    $seats = $result->fetch_all(MYSQLI_ASSOC);

                                    $seatTaken = false;
                                    foreach ($seats as $seat) {
                                        if ($seat['seat_status'] == 'Taken') {
                                            $seatTaken = true;
                                            break;
                                        }
                                    }

                                    if ($seatTaken) {
                                        echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">The seat is already taken.</h5></div>';
                                    } else {
                                        // Update the seat status to 'Taken'
                                        $updateSeatSql = "UPDATE seat SET seat_status = ? WHERE seat_id = ?";
                                        $stmt = $db->prepare($updateSeatSql);
                                        $stmt->bind_param("si", $status, $seat_id);
                                        $stmt->execute();

                                        // Deduct the fare amount from the user's ticket balance
                                        $newBalance = $ticketBalance - $fare;
                                        $updateBalanceSql = "UPDATE user_profile SET ticket_balance = ? WHERE user_id = ?";
                                        $stmt = $db->prepare($updateBalanceSql);
                                        $stmt->bind_param("di", $newBalance, $user_id);
                                        $stmt->execute();

                                        // Add the booking to the booking table
                                        $booking_status = 'Pending';
                                        $insertBookingSql = "INSERT INTO booking (user_id, seat_id, booking_status) VALUES (?, ?, ?)";
                                        $stmt = $db->prepare($insertBookingSql);
                                        $stmt->bind_param("iis", $user_id, $seat_id, $booking_status);

                                        if ($stmt->execute()) {
                                            echo '<div style="text-align: center;"><h5 style="color: green; font-size:18px;">Route Reservation Successful!</h5></div>';
                                            echo '<div style="text-align: center;"><p style="font-size:14px;">Please note that the fare amount will only be transferred to the driver<br>after confirming that you have been dropped off at the destination.<br><br><em>Thank you for choosing our route reservation service!</em></p></div>';

                                            // Check if all seats for the route are taken
                                            $checkAllSeatsTakenSql = "SELECT COUNT(*) AS total_seats FROM seat WHERE route_id = (SELECT route_id FROM seat WHERE seat_id = ?) AND seat_status != 'Taken'";
                                            $stmt = $db->prepare($checkAllSeatsTakenSql);
                                            $stmt->bind_param("i", $seat_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            $row = $result->fetch_assoc();
                                            $totalSeats = $row['total_seats'];

                                            if ($totalSeats == 0) {
                                                $updateRouteSql = "UPDATE route SET route_status = 'Fully Booked' WHERE route_id = (SELECT route_id FROM seat WHERE seat_id = ?)";
                                                $stmt = $db->prepare($updateRouteSql);
                                                $stmt->bind_param("i", $seat_id);
                                                $stmt->execute();
                                            }
                                        } else {
                                            echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">Route Reservation Failed</h5></div>';
                                        }
                                    }
                                }
                            }
                            ?>
                            <?php
                            if (!isset($_POST['submit']) || isset($_POST['submit']) && ($existingBooking || $ticketBalance < $fare || $ticketBalance < 250 || $seat['seat_status'] == 'Taken')) {
                                echo '<button type="submit" name="submit" class="btn btn-success">Reserve</button>';
                            }
                            ?>
                        </form>

                        <!-- End Form-->
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