<?php
require '../PHPMailer/src/PHPMailer.php';
require '../PHPMailer/src/SMTP.php';
require '../PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
}

include('../db.php');

$userid = $_SESSION['user_id'];

?>

<!DOCTYPE html>
<html lang="en">
<title>Add Route</title>

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
                        <a href="#">Register A Route</a>
                    </li>
                    <li class="breadcrumb-item active">Add Route</li>
                </ol>
                <hr>
                <div class="card">
                    <div class="card-header">
                        <b>Earn with TaraSabay: Route Registration</b><br>
                        Turn your car into a source of income by registering your route with TaraSabay.
                        <br>Connect with passengers, set your schedule, and maximize your earning potential.
                        <br>
                        <em><br>Join our community of drivers and start earning today!</em>
                        <!-- Icon Cards-->
                        <br><br>
                        <div class="row">
                            <div class="col-xl-3 col-sm-6 mb-3">
                                <div class="card text-white" style="background-color: #EAAA00;">
                                    <div class="card-body">
                                        <div class="card-body-icon">
                                            <i class="fas fa-route"></i>
                                        </div>
                                        <?php
                                        // Code for counting the ticket balance by user ID
                                        $result = $db->query("SELECT COUNT(*) FROM car INNER JOIN route ON car.user_id = route.car_id WHERE car.user_id = '$userid' AND route.route_status = 'Active'");
                                        $route = $result->fetch_row()[0];
                                        ?>
                                        <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><i class="fas fa-route"></i>&nbsp;&nbsp;<?php echo $route; ?></span> Active Routes</div>
                                    </div>
                                    <div class="card-body">
                                        <?php
                                        // Code for counting the ticket balance by user ID
                                        $result = $db->query("SELECT COUNT(*) FROM car INNER JOIN route ON car.user_id = route.car_id WHERE car.user_id = '$userid' AND route.route_status = 'Previous'");
                                        $route = $result->fetch_row()[0];
                                        ?>
                                        <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><i class="fas fa-route"></i>&nbsp;&nbsp;<?php echo $route; ?></span> Previous Routes</div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    <?php
                    $sql = "SELECT * FROM driver_identification WHERE user_id = '$userid' AND driver_stat = 'Expired'";
                    $result = mysqli_query($db, $sql);
                    $numRows = mysqli_num_rows($result);

                    if ($numRows > 0) {
                        echo '<div style="text-align: center; font-family: \'Poppins\', sans-serif; background-color: #FFFFFF; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;">
                        <img src="../assets/img/cancel.png" alt="Expired License" style="margin-bottom: 20px; width: 100px">
                        <h5 style="color: #FF0000; font-size: 24px; margin-bottom: 20px;">Expired Driver\'s License</h5>
                        <p style="color: #333333; font-size: 16px; margin-bottom: 20px;">Sorry, you cannot add a route with an expired driver\'s license.</p>
                        <p style="color: #333333; font-size: 16px;">Please renew your driver\'s license to proceed or contact our support team at support@tarasabay.com for further assistance.</p>
                    </div>';
                    } else {
                    ?>
                        <div class="card-body">


                            <form method="POST" autocomplete="on" enctype="multipart/form-data">
                                <div class="form-group">
                                    <label for="car_id">Choose Which Car to Use<span class="text-danger"> *</span></label>
                                    <select class="form-control" name="car_id" id="car_id" required>
                                        <option value="" selected disabled>Select Vehicle</option>
                                        <?php
                                        $sql = "SELECT * FROM car WHERE user_id = '$userid' AND car_status = 'Active'";
                                        $result = mysqli_query($db, $sql);
                                        $numRows = mysqli_num_rows($result);

                                        if ($numRows > 0) {
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $car_id  = $row['car_id'];
                                                $type = $row['type'];
                                                $brand = $row['brand'];
                                                $model = $row['model'];
                                                $seat_count = $row['seat_count'];
                                        ?>
                                                <option value="<?php echo $car_id; ?>" data-type="<?php echo $type; ?>" data-count="<?php echo $seat_count; ?>"><?php echo $type . " " . $brand . " " . $model; ?></option>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </select>
                                    <small class="form-text text-muted">
                                        <br><em>Guidelines for Selecting Car to Use:</em>
                                        <br>1. Choose the car you intend to use for the route from the dropdown list.
                                        <br>2. The available options will include active cars associated with your user account.
                                        <br>3. Select the car that best suits your needs for the specific route.
                                        <br>4. Ensure the car is in good condition and meets the requirements for the journey.
                                    </small>
                                    <br>
                                </div>

                                <div class="form-group">
                                    <label for="pickup_loc">Pick-Up Location<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Pick-Up Location" name="pickup_loc" required>
                                    <br>
                                </div>

                                <div class="form-group">
                                    <label for="dropoff_loc">Drop-off Location<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control" placeholder="Enter Drop-off Location" name="dropoff_loc" required>
                                    <br>
                                </div>

                                <div class="form-group">
                                    <label for="departure">Departure Date and Time<span class="text-danger"> *</span></label>
                                    <input type="datetime-local" class="form-control" placeholder="Enter Departure Date and Time" name="departure" required><br>
                                </div>

                                <div class="form-group">
                                    <label for="est_arrival_time">Estimated Arrival Time<span class="text-danger"> *</span></label>
                                    <input type="time" class="form-control" placeholder="Enter Estimated Arrival Time" name="est_arrival_time" required><br>
                                </div>

                                <script>
                                    document.getElementById('car_id').addEventListener('change', function() {
                                        var selectedCarId = this.value;
                                        var carTypeSelect = document.getElementById('car_id');
                                        var selectedOption = carTypeSelect.options[carTypeSelect.selectedIndex];
                                        var carType = selectedOption.dataset.type;
                                        var carSeatCount = selectedOption.dataset.count;
                                        var seatFareContainer = document.getElementById('seatFareContainer');
                                        var title = document.getElementById('title');

                                        title.style.display = 'block';

                                        seatFareContainer.innerHTML = '';

                                        if (carType === 'Coupe') {
                                            if (carSeatCount === '1') {
                                                seatFareContainer.innerHTML = `
                <div class="form-group">
                    <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                    <br>
                </div>`;
                                            } else if (carSeatCount === 3) {
                                                seatFareContainer.innerHTML = `
            <div class="form-group">
                <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                <br>
            </div>
            <div class="form-group">
                <label for="SRLWS">Second Row Left Window Seat<span class="text-danger"> *</span></label>
                <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRLWS" required>
                <br>
            </div>
            <div class="form-group">
                <label for="SRRWS">Second Row Right Window Seat<span class="text-danger"> *</span></label>
                <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRRWS" required>
                <br>
            </div>`;
                                            }
                                        } else if (carType === 'Crossover') {
                                            if (carSeatCount === '4') {
                                                seatFareContainer.innerHTML = `
                <div class="form-group">
                    <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRLWS">Second Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRMS">Second Row Middle Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRMS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRRWS">Second Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRRWS" required>
                    <br>
                </div>`;
                                            } else if (carSeatCount === '6') {
                                                seatFareContainer.innerHTML = `
                <div class="form-group">
                    <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRLWS">Second Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRMS">Second Row Middle Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRMS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRRWS">Second Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRRWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="TRLWS">Third Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="TRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="TRRWS">Third Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="TRRWS" required>
                    <br>
                </div>`;
                                            }
                                        } else if (carType === 'Regular Cab') {
                                            seatFareContainer.innerHTML = `
                <div class="form-group">
                    <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                    <br>
                </div>`;
                                        } else if (carType === 'Extended Cab') {
                                            if (carSeatCount === '3') {
                                                seatFareContainer.innerHTML = `
                <div class="form-group">
                    <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRLWS">Second Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRRWS">Second Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRRWS" required>
                    <br>
                </div>`;
                                            } else if (carSeatCount === '4') {
                                                seatFareContainer.innerHTML = `
                <div class="form-group">
                    <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRLWS">Second Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRMS">Second Row Middle Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRMS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRRWS">Second Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRRWS" required>
                    <br>
                </div>`;
                                            }
                                        } else if (carType === 'Crew Cab') {
                                            if (carSeatCount === '4') {
                                                seatFareContainer.innerHTML = `
                <div class="form-group">
                    <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRLWS">Second Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRMS">Second Row Middle Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRMS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRRWS">Second Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRRWS" required>
                    <br>
                </div>`;
                                            } else if (carSeatCount === '5') {
                                                seatFareContainer.innerHTML = `
                <div class="form-group">
                    <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="FPMS">Front Passenger Middle Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPMS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRLWS">Second Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRMS">Second Row Middle Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRMS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRRWS">Second Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRRWS" required>
                    <br>
                </div>`;
                                            }
                                        } else if (carType === 'SUV') {
                                            if (carSeatCount === '4') {
                                                seatFareContainer.innerHTML = `
                <div class="form-group">
                    <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRLWS">Second Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRMS">Second Row Middle Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRMS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRRWS">Second Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRRWS" required>
                    <br>
                </div>`;
                                            } else if (carSeatCount === '7') {
                                                seatFareContainer.innerHTML = `
                <div class="form-group">
                    <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRLWS">Second Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRMS">Second Row Middle Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRMS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRRWS">Second Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRRWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="TRLWS">Third Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="TRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="TRMS">Third Row Middle Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="TRMS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="TRRWS">Third Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="TRRWS" required>
                    <br>
                </div>`;
                                            }
                                        } else if (carType === 'MPV') {
                                            if (carSeatCount === '5') {
                                                seatFareContainer.innerHTML = `
                <div class="form-group">
                    <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRLWS">Second Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRRWS">Second Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRRWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="TRLWS">Third Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="TRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="TRRWS">Third Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="TRRWS" required>
                    <br>
                </div>`;
                                            } else if (carSeatCount === '7') {
                                                seatFareContainer.innerHTML = `
                <div class="form-group">
                    <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRLWS">Second Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRMS">Second Row Middle Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRMS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRRWS">Second Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRRWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="TRLWS">Third Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="TRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="TRRWS">Third Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="TRRWS" required>
                    <br>
                </div>`;
                                            }
                                        } else {
                                            seatFareContainer.innerHTML = `
                <div class="form-group">
                    <label for="FPS">Front Passenger Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="FPS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRLWS">Second Row Left Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRLWS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRMS">Second Row Middle Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRMS" required>
                    <br>
                </div>
                <div class="form-group">
                    <label for="SRRWS">Second Row Right Window Seat<span class="text-danger"> *</span></label>
                    <input type="number" class="form-control" placeholder="Enter Ticket Fare" name="SRRWS" required>
                    <br>
                </div>`;
                                        }
                                    });
                                </script>
                                <div id="title" style="display: none;">
                                    <em>Seat Fares</em><br><br>
                                </div>
                                <div id="seatFareContainer"></div>
                                <?php
                                if (isset($_POST['submit'])) {
                                    // Retrieve form data
                                    $car = $_POST["car_id"];
                                    $pickup_loc = $_POST["pickup_loc"];
                                    $dropoff_loc = $_POST["dropoff_loc"];
                                    $departure = $_POST["departure"];
                                    $est_arrival_time = $_POST["est_arrival_time"];

                                    // Define array for selected seat types
                                    $selected_seats = [];

                                    // Check which seat types are selected and add them to the array
                                    if (isset($_POST['FPS'])) {
                                        $selected_seats[] = [
                                            'seat_type' => 'Front Passenger Seat',
                                            'fare' => $_POST['FPS']
                                        ];
                                    }

                                    if (isset($_POST['FPMS'])) {
                                        $selected_seats[] = [
                                            'seat_type' => 'Front Passenger Middle Seat',
                                            'fare' => $_POST['FPMS']
                                        ];
                                    }

                                    if (isset($_POST['SRLWS'])) {
                                        $selected_seats[] = [
                                            'seat_type' => 'Second Row Left Window Seat',
                                            'fare' => $_POST['SRLWS']
                                        ];
                                    }

                                    if (isset($_POST['SRMS'])) {
                                        $selected_seats[] = [
                                            'seat_type' => 'Second Row Middle Seat',
                                            'fare' => $_POST['SRMS']
                                        ];
                                    }

                                    if (isset($_POST['SRRWS'])) {
                                        $selected_seats[] = [
                                            'seat_type' => 'Second Row Right Window Seat',
                                            'fare' => $_POST['SRRWS']
                                        ];
                                    }

                                    if (isset($_POST['TRLWS'])) {
                                        $selected_seats[] = [
                                            'seat_type' => 'Third Row Left Window Seat',
                                            'fare' => $_POST['TRLWS']
                                        ];
                                    }

                                    if (isset($_POST['TRMS'])) {
                                        $selected_seats[] = [
                                            'seat_type' => 'Third Row Middle Seat',
                                            'fare' => $_POST['TRMS']
                                        ];
                                    }

                                    if (isset($_POST['TRRWS'])) {
                                        $selected_seats[] = [
                                            'seat_type' => 'Third Row Right Window Seat',
                                            'fare' => $_POST['TRRWS']
                                        ];
                                    }

                                    $user_id = $_SESSION['user_id'];
                                    $ticket_balance_query = "SELECT ticket_balance FROM user_profile WHERE user_id = ?";
                                    $ticket_balance_statement = mysqli_prepare($db, $ticket_balance_query);
                                    mysqli_stmt_bind_param($ticket_balance_statement, "i", $user_id);
                                    mysqli_stmt_execute($ticket_balance_statement);
                                    mysqli_stmt_bind_result($ticket_balance_statement, $ticket_balance);
                                    mysqli_stmt_fetch($ticket_balance_statement);
                                    mysqli_stmt_close($ticket_balance_statement);

                                    // Check if the ticket balance is sufficient
                                    if ($ticket_balance >= 10) {
                                        // Insert data into the `route` table using prepared statements
                                        $route_query = "INSERT INTO route (car_id, pickup_loc, dropoff_loc, departure, est_arrival_time, route_status)
                        VALUES (?, ?, ?, ?, ?, 'Active')";
                                        $route_statement = mysqli_prepare($db, $route_query);
                                        mysqli_stmt_bind_param($route_statement, "issss", $car, $pickup_loc, $dropoff_loc, $departure, $est_arrival_time);
                                        mysqli_stmt_execute($route_statement);

                                        if (mysqli_stmt_affected_rows($route_statement) > 0) {
                                            $route_id = mysqli_insert_id($db); // Get the auto-generated route ID

                                            // Insert data into the `seat` table for each selected seat type using prepared statements
                                            $seat_query = "INSERT INTO seat (route_id, seat_type, fare, seat_status)
                            VALUES (?, ?, ?, 'Available')";
                                            $seat_statement = mysqli_prepare($db, $seat_query);

                                            foreach ($selected_seats as $seat) {
                                                $seat_type = $seat['seat_type'];
                                                $fare = $seat['fare'];

                                                mysqli_stmt_bind_param($seat_statement, "iss", $route_id, $seat_type, $fare);
                                                mysqli_stmt_execute($seat_statement);
                                            }

                                            // Update the ticket_balance in the user table
                                            $ticket_update_query = "UPDATE user_profile SET ticket_balance = ticket_balance - 10 WHERE user_id = ?";
                                            $ticket_update_statement = mysqli_prepare($db, $ticket_update_query);
                                            mysqli_stmt_bind_param($ticket_update_statement, "i", $user_id);
                                            mysqli_stmt_execute($ticket_update_statement);

                                            // Success message for route registration
                                            echo '<div style="text-align: center; font-family: \'Poppins\', sans-serif; background-color: #FFFFFF; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;">
                                                    <img src="../assets/img/checked.png" alt="Route Registration" style="margin-bottom: 20px; width: 100px">
                                                    <h5 style="color: #4CAF50; font-size: 24px; margin-bottom: 20px;">Route registration successful!</h5>
                                                    <p style="color: #333333; font-size: 16px; margin-bottom: 20px;">Thank you for registering your route. Your route has been successfully registered.</p>
                                                    <p style="color: #333333; font-size: 16px; margin-bottom: 20px;">10 tickets have been deducted from your account as a registration fee.</p>
                                                    <p style="color: #333333; font-size: 16px; margin-bottom: 20px;">Please note that if you cancel the created route, the registration fee will not be refunded to your account.</p>
                                                    <p style="color: #333333; font-size: 16px;">If you have any further questions or need assistance, please contact our support team at support@tarasabay.com. We are here to help!</p>
                                                </div>';
                                        } else {
                                            // Error inserting route data
                                            echo "Error inserting route data: " . mysqli_error($db);
                                        }
                                        mysqli_stmt_close($route_statement); // Close the statement
                                    } else {
                                        // Insufficient ticket balance
                                        echo '<div style="text-align: center; font-family: \'Poppins\', sans-serif; background-color: #FFFFFF; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;">
                                                <img src="../assets/img/error.png" alt="Insufficient Tickets" style="margin-bottom: 20px; width: 100px">
                                                <h5 style="color: #FF0000; font-size: 24px; margin-bottom: 20px;">Insufficient tickets!</h5>
                                                <p style="color: #333333; font-size: 16px; margin-bottom: 20px;">Sorry, you don\'t have enough tickets in your account to register the route.</p>
                                                <p style="color: #333333; font-size: 16px;">Please cash-in additional tickets to proceed or contact our support team at support@tarasabay.com for further assistance.</p>
                                            </div>';
                                    }
                                }
                                ?>

                                <button type="submit" name="submit" class="btn btn-success">Register</button>
                            </form>

                            <!-- End Form-->
                        </div>
                        <hr>
                        
                </div>
            <?php
                    }
            ?>



            <!-- /.content-wrapper -->

            </div>

            <!-- Sticky Footer -->
            <?php include("vendor/inc/footer.php"); ?>

        </div> <!-- /#wrapper -->

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
        <!--INject Sweet alert js-->
        <script src="vendor/js/swal.js"></script>

</body>

</html>