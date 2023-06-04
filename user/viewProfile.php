<?php
session_start();

if (!isset($_SESSION['user_id'])) {
  header('Location: ../login.php');
}

include('../db.php');

$userid = $_SESSION['user_id'];

$stmt = $db->prepare("SELECT * FROM user_profile WHERE user_id = ?");
$stmt->bind_param("i", $userid);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
  $row = $result->fetch_assoc();
  $id = $row['user_id'];
  $first_name = $row['first_name'];
  $last_name = $row['last_name'];
  $email = $row['email'];
  $pswd = $row['password'];
  $profile_photo = $row['profile_photo'];
  $middle_name = $row['middle_name'];
  $city_id = $row['city_id'];
  $nationality = $row['nationality'];
  $gender = $row['gender'];
  $birthdate = $row['birthdate'];
  $ticket_balance = $row['ticket_balance'];
  $role = $row['role'];
  $is_vaxxed = $row['is_vaxxed'];
  $vax_card = $row['vax_card'];
  $last_login = $row['last_login'];
}

$stmt = $db->prepare("SELECT * FROM city WHERE city_id = ?");
$stmt->bind_param("s", $city_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
  $row = $result->fetch_assoc();
  $city_name = $row['city_name'];
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
<title>View Profile</title>
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
            <a href="#">Profile</a>
          </li>
          <li class="breadcrumb-item active">View Profile</li>
        </ol>
        <hr>
        <a href="editProfile.php"><button class="btn btn-success" style="float:right; margin-right: 1%;">Edit Profile</button></a>
        <br><br>
        <div class="profile-photo">
          <?php if (!empty($profile_photo)) { ?>
            <img src="../assets/img/photos/<?php echo $profile_photo; ?>" alt="Profile Photo" class="photo-preview">
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
        <style>
          .form-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 10vh;
          }
        </style>
        <?php
        // Function to generate a random referral code
        function generateReferralCode()
        {
          // Generate a unique referral code
          $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
          $referralCode = '';
          $codeLength = 8;
          for ($i = 0; $i < $codeLength; $i++) {
            $referralCode .= $characters[rand(0, strlen($characters) - 1)];
          }
          return $referralCode;
        }
        ?>

        <!-- HTML form -->
        <div class="form-container">
          <form method="POST">
            <div class="form-group">
              <label for="referral_code">Referral Code:</label>
              <div class="input-group">
                <input type="text" class="form-control" value="<?php echo generateReferralCode(); ?>" id="referral_code" name="referral_code" readonly>
                <div class="input-group-append">
                  <button type="button" class="btn btn-primary" onclick="copyReferralCode()">Copy</button>
                </div>
              </div>
            </div>
          </form>
          <?php
          if (isset($_POST['referral_code'])) {
            // Retrieve the copied referral code
            $referralCode = $_POST['referral_code'];

            // Insert the referral code into the database
            $referred_id = 0;
            $stmt = $db->prepare("INSERT INTO code (referral_code, referrer_id) VALUES (?, ?)");
            $stmt->bind_param("si", $referralCode, $userid);
            $stmt->execute();

            // Display success message
            echo '<div style="text-align: center;"><h5 style="color: green; font-size:16px;">Referral Code Copied and Saved</h5></div>';
          }
          ?>
        </div>

        <script>
          function copyReferralCode() {
            var referralCodeField = document.getElementById("referral_code");
            referralCodeField.select();
            document.execCommand("copy");

            // Send the copied code to the server
            var code = referralCodeField.value;
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function() {
              if (xhr.readyState === 4 && xhr.status === 200) {
                alert("Referral code copied and saved to database");
              }
            };
            xhr.send("referral_code=" + encodeURIComponent(code));
          }
        </script>
        <br>


        <div class="card">
          <div class="card-header">
            <b>View Profile</b>
          </div>

          <div class="card-body">
            <!--Add User Form-->
            <form method="POST">
              <style>
                .disabled-input {
                  color: black;
                }
              </style>
              <div class="form-group">
                <label for="first_name">First Name</label>
                <input type="text" class="form-control disabled-input" value="<?php echo $first_name ?>" name="first_name" disabled><br>
              </div>

              <div class="form-group">
                <label for="middle_name">Middle Name</label>
                <input type="text" class="form-control disabled-input" value="<?php echo $middle_name ?>" name="middle_name" disabled><br>
              </div>

              <div class="form-group">
                <label for="last_name">Last Name</label>
                <input type="text" class="form-control disabled-input" value="<?php echo $last_name ?>" name="last_name" disabled><br>
              </div>

              <div class="form-group">
                <label for="city_name">City</label>
                <input type="text" class="form-control disabled-input" value="<?php echo $city_name ?>" name="city_name" disabled><br>
              </div>

              <div class="form-group">
                <label for="nationality">Nationality</label>
                <input type="text" class="form-control disabled-input" value="<?php echo $nationality ?>" name="nationality" disabled><br>
              </div>

              <div class="form-group">
                <label for="gender">Gender</label>
                <input type="text" class="form-control disabled-input" value="<?php echo $gender ?>" name="gender" disabled><br>
              </div>

              <div class="form-group">
                <label for="birthdate">Birthdate</label>
                <input type="date" class="form-control disabled-input" value="<?php echo $birthdate ?>" name="birthdate" disabled><br>
              </div>

              <div class="form-group">
                <label for="email">Registered Email</label>
                <input type="text" class="form-control disabled-input" value="<?php echo $email ?>" name="email" disabled><br>
              </div>

              <div class=" form-group">
                <label for="id">Provided Valid ID</label>
                <input type="text" class="form-control disabled-input" value="<?php echo $idtype ?>" name="id" disabled><br>
              </div>

              <div class="form-group">
                <label for="idnum">Valid ID Number</label>
                <input type="text" class="form-control disabled-input" value="<?php echo $validid ?>" name="idnum" disabled><br>
              </div>
              <div class="form-group text-center">
                <label for="vax_card">Vaccination Card:</label>
                <br>
                <div class="vax-card">
                  <?php if (!empty($vax_card)) { ?>
                    <img src="../assets/img/photos/<?php echo $vax_card; ?>" alt="Vaccination Card" class="card-preview">
                  <?php } else { ?>
                    <img src="../assets/img/default-vax-card.jpg" alt="Default Vaccination Card" class="card-preview">
                  <?php } ?>
                </div>
              </div>
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