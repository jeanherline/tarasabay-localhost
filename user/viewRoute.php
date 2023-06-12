<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
}

include('../db.php');

$stmt = $db->prepare("SELECT * FROM city WHERE city_id = ?");
$stmt->bind_param("s", $city_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $city_name = $row['city_name'];
}


$stmt = $db->prepare("SELECT * FROM user_identification WHERE user_id = ?");
$stmt->bind_param("s", $user_id);
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
<title>View Route</title>
<link href="vendor/css/user.css" rel="stylesheet">
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
                        <?php
                        if (isset($_SESSION['role']) == "Driver") {
                        ?>
                            <?php
                            $list = '';

                            if (isset($_GET['list'])) {
                                $listOption = $_GET['list'];

                                // Check the value of the list option and set the corresponding value for $list
                                if (
                                    $listOption === 'Active' || $listOption === 'Previous' || $listOption === 'Cancelled' ||
                                    $listOption === 'PendingBooking' || $listOption === 'ApprovedBooking' || $listOption === 'PreviousBooking' || $listOption === 'CancelledBooking'
                                    || $listOption === 'All' || $listOption === 'From' || $listOption === 'To' || $listOption === 'Booked'
                                ) {
                                    $list = $listOption;
                                }
                            }
                            ?>
                            <?php
                            if ($list == 'Active') {
                            ?>
                                <a href="driverRoute.php?status=Active">Active Route</a>

                            <?php
                            } else if ($list == 'Previous') {
                            ?>
                                <a href="driverRoute.php?status=Previous">Previous Route</a>
                            <?php
                            } else if ($list == 'Cancelled') {
                            ?>
                                <a href="driverRoute.php?status=Cancelled">Cancelled Route</a>
                            <?php
                            } else if ($list == 'PendingBooking') {
                            ?>
                                <a href="driverBooking.php?status=Pending">Pending Booking</a>
                            <?php
                            } else if ($list == 'ApprovedBooking') {
                            ?>
                                <a href="driverBooking.php?status=Approved">Approved Booking</a>
                            <?php
                            } else if ($list == 'PreviousBooking') {
                            ?>
                                <a href="driverBooking.php?status=Previous">Previous Booking</a>
                            <?php
                            } else if ($list == 'CancelledBooking') {
                            ?>
                                <a href="driverBooking.php?status=Cancelled">Cancelled Booking</a>
                            <?php
                            }
                        }
                        if (isset($_SESSION['role']) == "Passenger") {
                            ?>
                            <?php
                            $list = '';

                            if (isset($_GET['list'])) {
                                $listOption = $_GET['list'];

                                // Check the value of the list option and set the corresponding value for $list
                                if ($listOption === 'All' || $listOption === 'From' || $listOption === 'To' || $listOption === 'Booked') {
                                    $list = $listOption;
                                }
                            }
                            ?>
                            <?php
                            if ($list == 'All') {
                            ?>
                                <a href="passengerRoute.php?list=All">All Routes </a>

                            <?php
                            } else if ($list == 'From') {
                            ?>
                                <a href="passengerRoute.php?list=From">From My City </a>
                            <?php
                            } else if ($list == 'To') {
                            ?>
                                <a href="passengerRoute.php?list=To">To My City</a>
                            <?php
                            } else if ($list == 'Booked') {
                            ?>
                                <a href="passengerBooking.php?list=Booked">View <?php echo $list ?> Route</a>
                            <?php
                            }
                            ?>
                        <?php
                        }
                        ?>
                    </li>


                    <hr>

                    <?php

                    ?>
                    <li class="breadcrumb-item active">&nbsp;/ View <?php echo $list ?></li>
                </ol>
                <hr>
                <?php
                if (isset($_GET['car_id']) && isset($_GET['route_id'])) {
                    $car_id = $_GET['car_id'];
                    $route_id = $_GET['route_id'];

                    $query = "SELECT user_profile.*, driver_identification.*, car.*, route.*, seat.*
                              FROM user_profile 
                              INNER JOIN driver_identification ON user_profile.user_id = driver_identification.user_id
                              INNER JOIN car ON user_profile.user_id = car.user_id
                              INNER JOIN route ON car.car_id = route.car_id
                              INNER JOIN seat ON route.route_id = seat.route_id
                              WHERE car.car_id = '$car_id' AND route.route_id = '$route_id'";

                    $stmt = $db->prepare($query);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $row = $result->fetch_assoc();

                    // Fetching data from user_profile table
                    $user_id = $row['user_id'];
                    $profile_photo = $row['profile_photo'];
                    $first_name = $row['first_name'];
                    $middle_name = $row['middle_name'];
                    $last_name = $row['last_name'];
                    $city_id = $row['city_id'];
                    $nationality = $row['nationality'];
                    $gender = $row['gender'];
                    $birthdate = $row['birthdate'];
                    $email = $row['email'];
                    $password = $row['password'];
                    $ticket_balance = $row['ticket_balance'];
                    $role = $row['role'];
                    $is_vaxxed = $row['is_vaxxed'];
                    $vax_card = $row['vax_card'];
                    $is_agree = $row['is_agree'];
                    $created_at_user_profile = $row['created_at'];
                    $updated_at_user_profile = $row['updated_at'];

                    // Fetching data from driver_identification table
                    $driver_id = $row['driver_id'];
                    $disability = $row['disability'];
                    $pwd_docx = $row['pwd_docx'];
                    $license_front = $row['license_front'];
                    $license_back = $row['license_back'];
                    $license_expiration = $row['license_expiration'];
                    $is_above_60 = $row['is_above_60'];
                    $nbi_police_cbi = $row['nbi_police_cbi'];
                    $cbi_date_issued = $row['cbi_date_issued'];
                    $years_experience = $row['years_experience'];
                    $consents = $row['consents'];
                    $declarations = $row['declarations'];
                    $driver_stat = $row['driver_stat'];
                    $created_at_driver_identification = $row['created_at'];
                    $updated_at_driver_identification = $row['updated_at'];

                    // Fetching data from car table
                    $car_id = $row['car_id'];
                    $car_photo = $row['car_photo'];
                    $brand = $row['brand'];
                    $model = $row['model'];
                    $color = $row['color'];
                    $type = $row['type'];
                    $seat_count = $row['seat_count'];
                    $car_status = $row['car_status'];
                    $qr_code = $row['qr_code'];
                    $created_at_car = $row['created_at'];
                    $updated_at_car = $row['updated_at'];

                    // Fetching data from route table
                    $route_id = $row['route_id'];
                    $pickup_loc = $row['pickup_loc'];
                    $dropoff_loc = $row['dropoff_loc'];
                    $departure = $row['departure'];
                    $est_arrival_time = $row['est_arrival_time'];
                    $route_status = $row['route_status'];
                    $cancellation_reason = $row['cancellation_reason'];
                    $created_at_route = $row['created_at'];
                    $updated_at_route = $row['updated_at'];

                    // Fetching data from seat table
                    $seat_id = $row['seat_id'];
                    $seat_type = $row['seat_type'];
                    $fare = $row['fare'];
                    $seat_status = $row['seat_status'];
                    $created_at_seat = $row['created_at'];
                    $updated_at_seat = $row['updated_at'];
                }
                ?>
                <style>
                    .form-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 10vh;
                    }
                </style>

                <div class="card">
                    <div class="card-header">
                        <b>View Route</b>
                    </div>

                    <div class="card-body">
                        <div class="profile-photo">
                            <?php if (!empty($profile_photo)) { ?>
                                <img src="../assets/img/profile-photo/<?php echo $profile_photo; ?>" alt="Profile Photo" class="photo-preview">
                            <?php } else { ?>
                                <img src="../assets/img/default-profile-photo.jpg" alt="Default Profile Photo" class="photo-preview">
                            <?php } ?>
                        </div>
                        <br>
                        <p class="role" style="font-size: 22px; font-weight: bold;
                                                text-align: center; color: #000;">
                            <?php echo $first_name . " " . $last_name; ?> ✔️
                        </p>
                        <p class="role" style="font-size: 18px; font-weight: bold;
                                                text-align: center; color: green;">
                            <?php echo $role; ?>
                        </p>
                        <br>
                        <div class="card mb-3">
                            <div class="card-header">
                                <i class="fas fa-table"></i>
                                Seats
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
                                                <th>Seat Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT DISTINCT COALESCE(up.first_name, 'N/A') AS first_name, up.last_name, s.seat_type, s.fare, s.seat_status, up.user_id
                                                    FROM seat s
                                                    LEFT JOIN booking b ON s.seat_id = b.seat_id
                                                    LEFT JOIN user_profile up ON b.user_id = up.user_id
                                                    INNER JOIN route r ON s.route_id = r.route_id
                                                    WHERE r.route_id = ?";
                                            $stmt = $db->prepare($ret);
                                            $stmt->bind_param("i", $route_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            $cnt = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $cnt . "</td>";
                                                if ($row['seat_status'] === 'Available') {
                                                    echo "<td>N/A</td>";
                                                } elseif ($row['first_name'] && $row['last_name']) {
                                                    echo '<td><a href="viewPassenger.php?user_id=' . $row['user_id'] . '&list=' . $list . '">' . $row['first_name'] . ' ' . $row['last_name'] . '</a></td>';
                                                } else {
                                                    echo "<td>N/A</td>";
                                                }
                                                echo "<td>" . $row['seat_type'] . "</td>";
                                                echo "<td>" . $row['fare'] . "</td>";
                                                echo "<td>" . $row['seat_status'] . "</td>";
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
                        <br>

                        <form method="POST">
                            <style>
                                .disabled-input {
                                    color: black;
                                }
                            </style>
                            <em>Route Information</em><br><br>

                            <div class="form-group">
                                <label for="pickup_loc">Pickup Location</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $pickup_loc ?>" name="pickup_loc" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="dropoff_loc">Drop-off Location</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $dropoff_loc ?>" name="dropoff_loc" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="departure">Departure</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $departure ?>" name="departure" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="est_arrival_time">Est Arrival Time</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $est_arrival_time ?>" name="est_arrival_time" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="route_status">Route Status</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $route_status ?>" name="route_status" disabled><br>
                            </div>
                            <br>
                            <em>Car Information</em><br><br>

                            <div class="form-group text-center">
                                <label for="pwd_docx">Car Photo:</label>
                                <br>
                                <div class="license" style="width: 700px; margin: 0 auto;">
                                    <img src="../assets/img/car/<?php echo $car_photo; ?>" alt="car_photo" class="card-preview">
                                </div>
                            </div>

                            <div class="form-group text-center">
                                <label for="pwd_docx">QR Code:</label>
                                <br>
                                <div class="license" style="width: 700px; margin: 0 auto;">
                                    <img src="../assets/img/qr_codes/<?php echo $qr_code; ?>" alt="qr_code" class="card-preview">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $brand ?>" name="brand" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="model">Model</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $model ?>" name="model" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="color">Color</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $color ?>" name="color" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="type">Type</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $type ?>" name="type" disabled><br>
                            </div>

                            <div class="form-group">
                                <label for="seat_count">Seat Count</label>
                                <input type="text" class="form-control disabled-input" value="<?php echo $seat_count ?>" name="seat_count" disabled><br>
                            </div>
                        </form>
                        <br>
                        <em>Reviews</em><br><br>
                        <div class="card mb-3">
                            <div class="card-header">
                                <i class="fas fa-table"></i>
                                Driver Booking Reviews
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Comment</th>
                                                <th>Date and Time</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $ret = "SELECT DISTINCT r.*, rev.comment, p.ticket_amount, p.payment_to, p.payment_status, up.first_name, up.last_name
        FROM route r
        INNER JOIN seat s ON r.route_id = s.route_id
        INNER JOIN booking b ON s.seat_id = b.seat_id
        INNER JOIN review rev ON b.booking_id = rev.booking_id
        INNER JOIN payment p ON b.booking_id = p.booking_id
        INNER JOIN user_profile up ON b.user_id = up.user_id
        WHERE r.car_id = ?";
                                            $stmt = $db->prepare($ret);
                                            $stmt->bind_param("i", $car_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            $cnt = 1;
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                echo "<td>" . $cnt . "</td>";
                                                echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                                                echo "<td>" . $row['comment'] . "</td>";
                                                echo "<td>" . $row['created_at'] . "</td>";
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
                        <br>
                    </div>
                </div>
                <?php

                ?>

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