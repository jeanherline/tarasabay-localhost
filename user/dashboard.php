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
  $pswd = $row['pswd'];
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

        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Dashboard</a>
          </li>
          <li class="breadcrumb-item active">Overview</li>
        </ol>

        <?php
        if ($_SESSION['role'] == "admin") {
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
                  $result = $db->query("SELECT COUNT(*) FROM car WHERE status = 'approved'");
                  $approvedRegistrations = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $approvedRegistrations; ?></span> Approved Registrations</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="registeredCars.php">
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
                  $result = $db->query("SELECT COUNT(*) FROM car WHERE status = 'pending'");
                  $pendingRegistrations = $result->fetch_row()[0];
                  ?>
                  <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $pendingRegistrations; ?></span> Pending Registrations</div>
                </div>
                <a class="card-footer text-white clearfix small z-1" href="pendingCars.php">
                  <span class="float-left">View Details</span>
                  <span class="float-right">
                    <i class="fas fa-angle-right"></i>
                  </span>
                </a>
              </div>
            </div>

          <?php
        } else {
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
                    // Code for counting the number of registered cars with status "approved" by user ID
                    $result = $db->query("SELECT COUNT(*) FROM car INNER JOIN car_identification ON car.car_id = car_identification.car_id WHERE car.status = 'approved' AND car.owner_id = '$id'");
                    $driver = $result->fetch_row()[0];
                    ?>
                    <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $driver; ?></span> Registered Cars</div>
                  </div>
                  <a class="card-footer text-white clearfix small z-1" href="registeredCars.php">
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
                    // Code for counting the number of pending cars with status "pending" by user ID
                    $result = $db->query("SELECT COUNT(*) FROM car WHERE status = 'pending' AND owner_id = $id");
                    $vehicle = $result->fetch_row()[0];
                    ?>
                    <div class="mr-5"><span class="badge" style="background-color: #EAAA00;"><?php echo $vehicle; ?></span> Pending Cars</div>
                  </div>
                  <a class="card-footer text-white clearfix small z-1" href="pendingCars.php">
                    <span class="float-left">View Details</span>
                    <span class="float-right">
                      <i class="fas fa-angle-right"></i>
                    </span>
                  </a>
                </div>
              </div>
            <?php
          }
            ?>



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
                        <th>Brand</th>
                        <th>Model</th>
                        <th>Color</th>
                        <th>Seat Count</th>
                        <th>License Plate</th>
                        <th>CR Number</th>
                        <th>OR Number</th>
                        <th>Reg Exp Date</th>
                        <th>Status</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                      $ret = "SELECT c.car_id, c.owner_id, c.brand, c.model, ci.car_identity_num, ci.cr_number, ci.or_number, ci.reg_exp_date, c.status, c.color, c.seat_count
                        FROM car c
                        JOIN car_identification ci ON c.car_id = ci.car_id
                        JOIN user_profile up ON c.owner_id = up.user_id
                        AND c.status = 'pending'";
                      $stmt = $db->prepare($ret);
                      $stmt->execute();
                      $result = $stmt->get_result();
                      $cnt = 1;
                      while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['brand'] . "</td>";
                        echo "<td>" . $row['model'] . "</td>";
                        echo "<td>" . $row['color'] . "</td>";
                        echo "<td>" . $row['seat_count'] . "</td>";
                        echo "<td>" . $row['car_identity_num'] . "</td>";
                        echo "<td>" . $row['cr_number'] . "</td>";
                        echo "<td>" . $row['or_number'] . "</td>";
                        echo "<td>" . $row['reg_exp_date'] . "</td>";
                      ?>
                        <td><?php if ($row['status'] == "Pending") {
                              echo '<span class = "badge badge-warning">' . $row['status'] . '</span>';
                            } else {
                              echo '<span class = "badge badge-success">' . $row['status'] . '</span>';
                            } ?></td>
                      <?php
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