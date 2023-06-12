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

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Overview</li>
        </ol>

        <?php
        if ($_SESSION['role'] == "City Admin") {
        ?>
          <!-- Icon Cards-->

          <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa fa-id-card"></i>
                  </div>
                  <?php
                  // Code for counting the number of approved car registrations by user ID
                  $result = $db->query("SELECT COUNT(*) FROM driver_identification WHERE driver_stat = 'Renewal'");
                  $approvedRegistrations = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $approvedRegistrations; ?>
                    </span> Driver's License Renewal</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="renewal.php?list=Driver">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa fa-id-card"></i>
                  </div>
                  <?php
                  // Code for counting the number of approved car registrations by user ID
                  $result = $db->query("SELECT COUNT(*) FROM car WHERE car_status = 'Renewal'");
                  $approvedRegistrations = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $approvedRegistrations; ?>
                    </span> License Plate Renewal</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="renewal.php?list=Plate">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa fa-bus"></i>
                  </div>
                  <?php
                  // Code for counting the number of pending car registrations by user ID
                  $result = $db->query("SELECT COUNT(*) FROM car WHERE car_status = 'Pending'");
                  $pendingRegistrations = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $pendingRegistrations; ?>
                    </span> Pending Car Registrations</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="carReg.php?status=Pending">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa fa-bus"></i>
                  </div>
                  <?php
                  // Code for counting the number of pending car registrations by user ID
                  $result = $db->query("SELECT COUNT(*) FROM cico WHERE trans_type = 'Cash-Out' AND trans_stat = 'Pending'");
                  $pendingRegistrations = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $pendingRegistrations; ?>
                    </span> Pending Cash-Out</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="cash-out-manage.php">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
          </div>
          <!--Bookings-->
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table"></i>
              Pending Car Registrations
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Owner Full Name</th>
                      <th>Car Photo</th>
                      <th>Vehicle Type</th>
                      <th>Brand, Model, & Color</th>
                      <th>OR No.</th>
                      <th>CR No.</th>
                      <th>Plate No.</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php
                    $city_id = $_SESSION['city_id'];

                    $ret = "SELECT car.*, user_profile.*, car_identification.*, city.city_name
                                        FROM car
                                        INNER JOIN user_profile ON car.user_id = user_profile.user_id
                                        INNER JOIN car_identification ON car.car_id = car_identification.car_id
                                        INNER JOIN city ON user_profile.city_id = city.city_id
                                        WHERE car.car_status = 'Pending' AND user_profile.city_id = '$city_id'";
                    $stmt = $db->prepare($ret);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $cnt = 1;

                    while ($row = $result->fetch_assoc()) {
                      $id = $row['user_id'];

                      echo "<tr>";
                      echo "<td>" . $cnt . "</td>";
                      echo "<td>" . $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'] . "</td>";
                      echo "<td><img src='../assets/img/car/" . $row['car_photo'] . "' alt='Profile Photo' width='50' height='50'></td>";
                      echo "<td>" . $row['type'] . "</td>";
                      echo "<td>" . $row['brand'] . " " . $row['model'] . " " . $row['color'] . "</td>";
                      echo "<td>" . $row['or_number'] . "</td>";
                      echo "<td>" . $row['cr_number'] . "</td>";
                      echo "<td>" . $row['plate_number'] . "</td>";
                    }
                    ?>

                    <?php
                    ?>

                    <?php
                    echo "</tr>";
                    $cnt++;
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


        } elseif ($_SESSION['role'] == "Driver") {
          $user_id = $_SESSION['user_id'];

        ?>
          <!-- Icon Cards-->
          <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-ticket-alt"></i>
                  </div>
                  <?php
                  $result = $db->query("SELECT ticket_balance FROM user_profile WHERE role = 'Driver' AND user_id = $user_id");
                  $passenger = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $passenger; ?></span> Wallet Tickets</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="cash-in.php">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-city"></i>
                  </div>
                  <?php
                  $result = $db->query("SELECT COUNT(*) AS active_route_count
                  FROM route
                  INNER JOIN car ON route.car_id = car.car_id
                  WHERE car.user_id = '$user_id'
                  AND route.route_status = 'Active' OR route.route_status = 'Fully Booked'
                  OR route.route_status = 'Start' OR route.route_status = 'Picked-Up'
                  OR route.route_status = 'Dropped-Off';
                  ");
                  $driver = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $driver; ?></span> Active Routes</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="driverRoute.php?status=Active">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa fa-car"></i>
                  </div>
                  <?php
                  // Code for counting the number of pending cars with status "pending" by user ID
                  $result = $db->query("SELECT COUNT(*) FROM car WHERE car_status = 'Active' AND user_id = $user_id");
                  $vehicle = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $vehicle; ?></span> Active Cars</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="carReg.php?status=Active">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-id-card"></i>
                  </div>
                  <?php
                  $result = $db->query("SELECT COUNT(*) FROM car WHERE car_status = 'Expired' AND user_id = $user_id");
                  $driver = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $driver; ?></span> Expired Car Plates</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="driverCars.php?list=Expired">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

          </div>
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table"></i>
              Active Routes
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Pickup Location</th>
                      <th>Drop-Off Location</th>
                      <th>Departure</th>
                      <th>Est Arrival Time</th>
                      <th>Route Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    $user_id = $_SESSION['user_id'];
                    $ret = "SELECT r.*, c.* 
FROM route AS r 
INNER JOIN car AS c ON r.car_id = c.car_id 
WHERE (c.user_id = ? AND r.route_status IN ('Active', 'Fully Booked', 'Start', 'Picked-up','Dropped-off'))
ORDER BY r.route_id";
                    $stmt = $db->prepare($ret);
                    $stmt->bind_param("s", $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $cnt = 1;
                    while ($row = $result->fetch_assoc()) {
                      $car_id = $row['car_id'];
                      $route_id = $row['route_id'];

                      // Retrieve the total number of seats for the route
                      $countSeatsSql = "SELECT COUNT(*) AS total_seats FROM seat WHERE route_id = ?";
                      $stmt = $db->prepare($countSeatsSql);
                      $stmt->bind_param("i", $route_id);
                      $stmt->execute();
                      $countResult = $stmt->get_result();
                      $countRow = $countResult->fetch_assoc();
                      $totalSeats = $countRow['total_seats'];

                      // Check if all booking statuses of the seats in the route are 'Dropped-off'
                      $checkBookingStatusSql = "SELECT COUNT(*) AS dropped_off_seats 
    FROM seat 
    INNER JOIN booking ON seat.seat_id = booking.seat_id 
    WHERE seat.route_id = ? AND booking.booking_status = 'Dropped-off'";

                      $stmt = $db->prepare($checkBookingStatusSql);
                      $stmt->bind_param("i", $route_id);
                      $stmt->execute();
                      $bookingStatusResult = $stmt->get_result();
                      $bookingStatusRow = $bookingStatusResult->fetch_assoc();
                      $droppedOffSeats = $bookingStatusRow['dropped_off_seats'];

                      // Check if all seats of the route are available
                      $checkSeatsSql = "SELECT COUNT(*) AS available_seats 
                      FROM seat 
                      WHERE route_id = ? AND seat_status = 'Available'";
                      $stmt = $db->prepare($checkSeatsSql);
                      $stmt->bind_param("i", $route_id);
                      $stmt->execute();
                      $seatResult = $stmt->get_result();
                      $seatRow = $seatResult->fetch_assoc();
                      $availableSeats = $seatRow['available_seats'];

                      echo "<tr>";
                      echo "<td>" . $cnt . "</td>";
                      echo "<td>" . substr($row['pickup_loc'], 0, 15) . "...</td>";
                      echo "<td>" . substr($row['dropoff_loc'], 0, 15) . "...</td>";
                      echo "<td>" . date('F j, Y h:i A', strtotime($row['departure'])) . "</td>";
                      echo "<td>" . date('h:i A', strtotime($row['est_arrival_time'])) . "</td>";
                      echo "<td>" . $row['route_status'] . "</td>";

                      echo "<td>";
                      echo "<a href='viewDriverRoute.php?user_id=" . $row['user_id'] . "&list=Active&route_id=" . $route_id . "'>
            <button>&nbsp;&nbsp;<i class='fa fa-eye'></i>&nbsp;View&nbsp;&nbsp;</button>
        </a>";
                      if ($droppedOffSeats > 0) {
                        echo "<a onclick='return confirm(\"Are you sure you want to mark the route as done?\")' href='doneRoute.php?list=driverRoute.php?status=Active&user_id=" . $row['user_id'] . "&car_id=" . $car_id . "&route_id=" . $route_id . "'>
                <button>&nbsp;&nbsp;<i class='fa fa-check' style='color: green'></i>&nbsp;Done&nbsp;&nbsp;</button>
            </a>";
                      } elseif ($availableSeats < $totalSeats && $row['route_status'] === 'Active') {
                        echo "<button onclick='markRouteAsDone(" . $route_id . ");'>&nbsp;&nbsp;<i class='fa fa-check' style='color:red;'></i>&nbsp;Start&nbsp;&nbsp;</button>";
                      } else {
                        echo "<a onclick='return confirm(\"Are you sure you want to cancel the route?\")' href='cancelRoute.php?user_id=" . $row['user_id'] . "&car_id=" . $car_id . "&route_id=" . $route_id . "'>
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

                <script>
                  function markRouteAsDone(route_id) {
                    if (confirm("Are you sure you want to mark this route as 'Start'?")) {
                      // Send an AJAX request to update the route status
                      var xhr = new XMLHttpRequest();
                      xhr.open("GET", "updateRouteStatus.php?route_id=" + route_id + "&status=Start", true);
                      xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                          // Reload the page to reflect the updated status
                          location.reload();
                        }
                      };
                      xhr.send();
                    }
                  }
                </script>


                <script>
                  function markRouteAsDone(route_id) {
                    if (confirm("Are you sure you want to mark this route as 'Start'?")) {
                      // Send an AJAX request to update the route status
                      var xhr = new XMLHttpRequest();
                      xhr.open("GET", "updateRouteStatus.php?route_id=" + route_id + "&status=Start", true);
                      xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                          // Reload the page to reflect the updated status
                          location.reload();
                        }
                      };
                      xhr.send();
                    }
                  }
                </script>
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
        } elseif ($_SESSION['role'] == "Passenger") {
          $user_id = $_SESSION['user_id'];

        ?>
          <!-- Icon Cards-->
          <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa-ticket-alt"></i>
                  </div>
                  <?php
                  $result = $db->query("SELECT ticket_balance FROM user_profile WHERE role = 'Passenger' AND user_id = $user_id");
                  $passenger = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $passenger; ?></span> Wallet Tickets</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="cash-in.php">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-city"></i>
                  </div>
                  <?php
                  $result = $db->query("SELECT COUNT(*) FROM booking WHERE user_id = $user_id AND booking_status = 'Approved'");
                  $driver = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $driver; ?></span> Approved Bookings</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="driverRoute.php?status=Active">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa fa-car"></i>
                  </div>
                  <?php
                  // Code for counting the number of pending cars with status "pending" by user ID
                  $result = $db->query("SELECT COUNT(*) FROM booking WHERE user_id = $user_id AND booking_status = 'Done'");
                  $vehicle = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $vehicle; ?></span> Previous Bookings</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="carReg.php?status=Active">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-id-card"></i>
                  </div>
                  <?php
                  $result = $db->query("SELECT COUNT(*) FROM booking WHERE user_id = $user_id AND booking_status = 'Cancelled'");
                  $driver = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $driver; ?></span> Cancelled Bookings</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="driverCars.php?list=Expired">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

          </div>
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table"></i>
              Booked Routes
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
                                                OR b.booking_status = 'Dropped-off')";
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
              <div class="card-footer small text-muted">
                <?php
                date_default_timezone_set("Africa/Nairobi");
                echo "Generated : " . date("h:i:sa");
                ?>
              </div>
            </div>
          </div>
        <?php
        } elseif ($_SESSION['role'] == "Main Admin") {
        ?>
          <!-- Icon Cards-->
          <div class="row">
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-city"></i>
                  </div>
                  <?php
                  // Code for counting the number of registered cars with status "approved" by user ID
                  $result = $db->query("SELECT COUNT(*) FROM user_profile WHERE role = 'City Admin'");
                  $driver = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $driver; ?></span> City Admins</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="cityAdmins.php">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-user-friends"></i>
                  </div>
                  <?php
                  $result = $db->query("SELECT COUNT(*) FROM user_profile WHERE role = 'Passenger'");
                  $passenger = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $passenger; ?></span> TaraSabay Passengers</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="cityUsers.php?list=Full">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-id-card"></i>
                  </div>
                  <?php
                  $result = $db->query("SELECT COUNT(*) FROM user_profile WHERE role = 'Driver'");
                  $driver = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $driver; ?></span> TaraSabay Drivers</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="cityUsers.php?list=Full">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6 mb-3">
              <div class="card text-white" style="background-color: #EAAA00;">
                <div class="card-body">
                  <div class="card-body-icon">
                    <i class="fas fa-fw fa fa-car"></i>
                  </div>
                  <?php
                  // Code for counting the number of pending cars with status "pending" by user ID
                  $result = $db->query("SELECT COUNT(*) FROM car WHERE car_status = 'Active'");
                  $vehicle = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $vehicle; ?></span> Active Cars</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="carReg.php?status=Active">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>
          </div>
          <div class="card mb-3">
            <div class="card-header">
              <i class="fas fa-table"></i>
              City Admins
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Full Name</th>
                      <th>Email Address</th>
                      <th>Designated City</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php
                    $ret = "SELECT * FROM user_profile
                                         WHERE role = 'City Admin' OR role = 'Previous City Admin'";
                    $stmt = $db->prepare($ret);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $cnt = 1;

                    while ($row = $result->fetch_assoc()) {
                      $cityAdminID = $row['user_id'];

                      echo "<tr>";
                      echo "<td>" . $cnt . "</td>";
                      echo "<td>" . $row['first_name'] . " " . $row['middle_name'] . " " . $row['last_name'] . "</td>";
                      echo "<td>" . $row['email'] . "</td>";

                      $city_query = "SELECT city_name FROM city WHERE city_id = ?";
                      $city_statement = mysqli_prepare($db, $city_query);
                      mysqli_stmt_bind_param($city_statement, "i", $row['city_id']);
                      mysqli_stmt_execute($city_statement);
                      mysqli_stmt_bind_result($city_statement, $city_name);
                      mysqli_stmt_fetch($city_statement);
                      mysqli_stmt_close($city_statement); // Close the prepared statement

                      echo "<td>" . $city_name . "</td>";
                      echo "<td>" . $row['role'] . "</td>";

                      if ($row['role'] == "City Admin") {
                    ?>
                        <td>
                          <a href="viewAdminProfile.php?user_id=<?php echo $cityAdminID; ?>">
                            <button>&nbsp;&nbsp;<i class="fa fa-eye"></i>&nbsp;View&nbsp;&nbsp;</button>
                          </a>
                          <a href="editCityAdmin.php?user_id=<?php echo $cityAdminID; ?>">
                            <button>&nbsp;&nbsp;<i class="fa fa-pencil"></i>&nbsp;Edit&nbsp;&nbsp;</button>
                          </a>
                          <a href="removeCityAdmin.php?user_id=<?php echo $cityAdminID; ?>">
                            <button>&nbsp;&nbsp;<i class="fa fa-ban"></i>&nbsp;Remove&nbsp;&nbsp;</button>
                          </a>
                        </td>
                      <?php
                      } else {
                      ?>
                        <td>
                          <a href="viewAdminProfile.php?user_id=<?php echo $cityAdminID; ?>">
                            <button>&nbsp;&nbsp;<i class="fa fa-eye"></i>&nbsp;View&nbsp&nbsp;;</button>
                          </a>
                          <a href="editCityAdmin.php?user_id=<?php echo $cityAdminID; ?>">
                            <button>&nbsp;&nbsp;<i class="fa fa-pencil"></i>&nbsp;Edit&nbsp;&nbsp;</button>
                          </a>
                          <a href="addCityAdmin.php?user_id=<?php echo $cityAdminID; ?>">
                            <button>&nbsp;&nbsp;<i class="fa fa-check"></i>&nbsp;Add&nbsp;&nbsp;</button>
                          </a>
                        </td>
                    <?php
                      }
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
        <br>
        <?php
        if ($_SESSION['role'] == 'Passenger') {
        ?>
          <button type="submit" name="submit" class="btn btn-success btn-lg d-block mx-auto">
            <a style="text-decoration: none; color: white;" href="verifyCar.php">Verify A Car / Driver</a>
          </button>
          <br>

        <?php
        }
        ?>
        <!-- Sticky Footer -->
        <?php
        include("vendor/inc/footer.php");

        if (isset($_SESSION['emergency_phone'])) {
          $phone = $_SESSION['emergency_phone'];
        ?>
          <button type="submit" name="submit" class="btn btn-danger btn-lg d-block mx-auto">
            <a style="text-decoration: none; color: white;" href="tel:<?php echo $phone ?>">Call Emergency</a>
          </button>
        <?php

        }

        ?>
        <br>
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