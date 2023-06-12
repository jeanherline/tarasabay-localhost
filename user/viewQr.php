<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
}

include('../db.php');

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
                <?php
                if (isset($_GET['car_id'])) {
                    $car_id = $_GET['car_id'];

                    include('../db.php');

                    // Fetch user_profile and car associated with the car_id
                    $query = "SELECT up.*, c.*, ct.city_name 
                FROM user_profile up
                INNER JOIN car c ON up.user_id = c.user_id
                INNER JOIN city ct ON up.city_id = ct.city_id
                WHERE c.car_id = ?";
                    $stmt = $db->prepare($query);
                    $stmt->bind_param("i", $car_id);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows == 1) {
                        $row = $result->fetch_assoc();

                        // Access the user_profile, car, and city data
                        $userProfile = $row;
                        $car = $row;
                        $city = $row;

                        // Extract the fields from the user_profile table
                        $user_id = $userProfile['user_id'];
                        $profile_photo = $userProfile['profile_photo'];
                        $first_name = $userProfile['first_name'];
                        $middle_name = $userProfile['middle_name'];
                        $last_name = $userProfile['last_name'];
                        $city_id = $userProfile['city_id'];
                        $nationality = $userProfile['nationality'];
                        $gender = $userProfile['gender'];
                        $birthdate = $userProfile['birthdate'];
                        $email = $userProfile['email'];
                        $password = $userProfile['password'];
                        $ticket_balance = $userProfile['ticket_balance'];
                        $role = $userProfile['role'];
                        $is_vaxxed = $userProfile['is_vaxxed'];
                        $vax_card = $userProfile['vax_card'];
                        $is_agree = $userProfile['is_agree'];
                        $user_created_at = $userProfile['created_at'];
                        $user_updated_at = $userProfile['updated_at'];

                        // Extract the fields from the car table
                        $car_id = $car['car_id'];
                        $car_user_id = $car['user_id'];
                        $car_photo = $car['car_photo'];
                        $brand = $car['brand'];
                        $model = $car['model'];
                        $color = $car['color'];
                        $type = $car['type'];
                        $seat_count = $car['seat_count'];
                        $car_status = $car['car_status'];
                        $qr_code = $car['qr_code'];
                        $car_created_at = $car['created_at'];
                        $car_updated_at = $car['updated_at'];

                        // Extract the field from the city table
                        $city_name = $city['city_name'];

                        // You can now use the fetched data as needed
                        // Example: echo $first_name, $brand, $city_name, etc.
                    } else {
                        echo "No data found for car_id: $car_id";
                    }
                }
                ?>

                <!-- Breadcrumbs-->
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="verifyCar.php">Verify A Car or Driver</a>
                    </li>
                    <li class="breadcrumb-item active">View Profile</li>
                </ol>
                <hr>
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
                <style>
                    .form-container {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        height: 10vh;
                    }
                </style>

                <!-- HTML form -->

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

                            <?php
                            $stmt = $db->prepare("SELECT * FROM driver_identification WHERE user_id = ?");
                            $stmt->bind_param("s", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows == 1) {
                                $row = $result->fetch_assoc();
                                $disability = $row['disability'];
                                $pwd_docx = $row['pwd_docx'];
                                $license_front = $row['license_front'];
                                $license_back = $row['license_back'];
                                $license_expiration = $row['license_expiration'];
                                $nbi_police_cbi = $row['nbi_police_cbi'];
                                $cbi_date_issued = $row['cbi_date_issued'];
                                $years_experience = $row['years_experience'];
                            }

                            if (!empty($disability)) {
                            ?>
                                <div class="form-group">
                                    <label for="disability">Disability</label>
                                    <input type="text" class="form-control disabled-input" value="<?php echo $disability ?>" name="disability" disabled><br>
                                </div>
                                <div class="form-group">
                                    <label for="years_experience">Years of Experience</label>
                                    <input type="text" class="form-control disabled-input" value="<?php echo $years_experience ?>" name="years_experience" disabled><br>
                                </div>
                            <?php
                            }

                            ?>
                            <div class="form-group text-center">
                                <label for="pwd_docx">Car Photo:</label>
                                <div class="vax-card" style="width: 500px; margin: 0 auto;">
                                    <img src="../assets/img/car/<?php echo $car_photo; ?>" alt="car_photo" class="card-preview">
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
                            <br>
                            <?php
                            $stmt = $db->prepare("SELECT * FROM emergency WHERE user_id = ?");
                            $stmt->bind_param("s", $user_id);
                            $stmt->execute();
                            $result = $stmt->get_result();

                            if ($result->num_rows == 1) {
                                $row = $result->fetch_assoc();
                                $name = $row['name'];
                                $relationship = $row['relationship'];
                                $phone = $row['phone'];
                                $address = $row['address'];

                            ?>
                                <em>Emergency Contact</em>
                                <br> <br>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control disabled-input" value="<?php echo $name ?>" name="name" disabled><br>
                                </div>

                                <div class="form-group">
                                    <label for="relationship">Relationship</label>
                                    <input type="text" class="form-control disabled-input" value="<?php echo $relationship ?>" name="relationship" disabled><br>
                                </div>

                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control disabled-input" value="<?php echo $phone ?>" name="phone" disabled><br>
                                </div>

                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" class="form-control disabled-input" value="<?php echo $address ?>" name="address" disabled><br>
                                </div>
                            <?php
                            }
                            ?>
                            <br>
                            
                            <?php
                            if ($_SESSION['role'] == 'Driver') {
                                $stmt = $db->prepare("SELECT * FROM driver_identification WHERE user_id = ?");
                                $stmt->bind_param("s", $user_id);
                                $stmt->execute();
                                $result = $stmt->get_result();

                                if ($result->num_rows == 1) {
                                    $row = $result->fetch_assoc(); // Add this line to fetch the row

                                    $disability = $row['disability'];
                                    $pwd_docx = $row['pwd_docx'];
                                    $license_front = $row['license_front'];
                                    $license_back = $row['license_back'];
                                    $license_expiration = $row['license_expiration'];
                                    $nbi_police_cbi = $row['nbi_police_cbi'];
                                    $cbi_date_issued = $row['cbi_date_issued'];
                                    $years_experience = $row['years_experience'];
                            ?>
                                    <em>Driver Information</em>
                                    <br> <br>
                                    <div class="form-group">
                                        <label for="disability">Disability</label>
                                        <input type="text" class="form-control disabled-input" value="<?php echo $disability ?>" name="disability" disabled><br>
                                    </div>

                                    <div class="form-group text-center">
                                        <label for="pwd_docx">PWD ID / Certificate:</label>
                                        <br>
                                        <div class="vax-card" style="width:200px;">
                                            <?php if (!empty($pwd_docx)) { ?>
                                                <img src="../assets/img/pwd/<?php echo $pwd_docx; ?>" alt="PWD" class="card-preview">
                                            <?php } else { ?>
                                            <?php } ?>
                                        </div>
                                    </div>

                                    <div class="form-group text-center">
                                        <label for="license_front">License Front:</label>
                                        <br>
                                        <div class="vax-card" style="width: 500px; margin: 0 auto;">
                                            <img src="../assets/img/license/<?php echo $license_front; ?>" alt="license_front" class="card-preview">
                                        </div>
                                    </div>

                                    <div class="form-group text-center">
                                        <label for="license_back">License Back:</label>
                                        <br>
                                        <div class="vax-card" style="width: 500px; margin: 0 auto;">
                                            <img src="../assets/img/license/<?php echo $license_back; ?>" alt="license_back" class="card-preview">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="license_expiration">License Expiration</label>
                                        <input type="date" class="form-control disabled-input" value="<?php echo $license_expiration ?>" name="license_expiration" disabled><br>
                                    </div>

                                    <div class="form-group text-center">
                                        <label for="nbi_police_cbi">NBI / Police / CBI:</label>
                                        <br>
                                        <div class="vax-card" style="width:200px;">
                                            <img src="../assets/img/docx/<?php echo $nbi_police_cbi; ?>" alt="nbi_police_cbi" class="card-preview">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="cbi_date_issued"><em><b>IF:</b></em> CBI Date of Issuance</label>
                                        <input type="date" class="form-control disabled-input" value="<?php echo $cbi_date_issued ?>" name="cbi_date_issued" disabled><br>
                                    </div>

                                    <div class="form-group">
                                        <label for="years_experience">Years of Experience</label>
                                        <input type="text" class="form-control disabled-input" value="<?php echo $years_experience ?>" name="years_experience" disabled><br>
                                    </div>
                            <?php
                                }
                            }
                            ?>

                        </form>
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