<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
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

// Retrieve the original user profile data from the database
$stmt = $db->prepare("SELECT first_name, middle_name, last_name, city_id, nationality, gender, birthdate, profile_photo, vax_card FROM user_profile WHERE user_id = ?");
$stmt->bind_param("i", $userId); // Replace $userId with the actual user ID
$stmt->execute();
$stmt->bind_result($originalFirstName, $originalMiddleName, $originalLastName, $originalCityId, $originalNationality, $originalGender, $originalBirthdate, $originalProfilePhoto, $originalVaxCard);
$stmt->fetch();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<title>Edit Profile</title>
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
                        <a href="viewProfile.php">View Profile</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Profile</li>
                </ol>
                <hr>
                <div class="card">
                    <div class="card-header">
                        <b>Edit Profile</b>
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
                            <?php echo $first_name . " " . $last_name; ?> ✔️</p>
                        <p class="role" style="font-size: 18px; font-weight: bold;
                                                text-align: center; color: green;">
                            <?php echo $role; ?></p>
                        <br>
                        <form method="POST" autocomplete="on" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="profile_photo">Upload Profile Photo</label>
                                <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept=".jpg,.jpeg,.png" value="<?php echo isset($_POST['profile_photo']) ? $_POST['profile_photo'] : $profile_photo; ?>">
                                <br>
                            </div>
                            <div class="form-group">
                                <label for="first_name">First Name</label>
                                <input type="text" class="form-control" value="<?php echo isset($_POST['first_name']) ? $_POST['first_name'] : $first_name; ?>" name="first_name"><br>
                            </div>

                            <div class="form-group">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" class="form-control" value="<?php echo isset($_POST['middle_name']) ? $_POST['middle_name'] : $middle_name; ?>" name="middle_name"><br>
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" value="<?php echo isset($_POST['last_name']) ? $_POST['last_name'] : $last_name; ?>" name="last_name"><br>
                            </div>

                            <div class="form-group">
                                <label for="city_id">City</label>
                                <select class="form-control" name="city_id">
                                    <?php if (!empty($city_id)) { ?>
                                        <option value="<?php echo isset($_POST['city_id']) ? $_POST['city_id'] : $city_id; ?>" selected><?php echo isset($_POST['city_name']) ? $_POST['city_name'] : $city_name; ?></option>
                                    <?php } else { ?>
                                        <option value="" selected disabled>Select City</option>
                                    <?php } ?>
                                    <?php
                                    $stmt = $db->prepare("SELECT city_id, city_name FROM city");
                                    $stmt->execute();
                                    $result = $stmt->get_result();
                                    while ($row = $result->fetch_assoc()) {
                                        $cityId = $row['city_id'];
                                        $cityName = $row['city_name'];
                                    ?>
                                        <option value="<?php echo $cityId; ?>" <?php if ($cityId == $city_id) echo 'selected'; ?>><?php echo $cityName; ?></option>
                                    <?php
                                    }
                                    ?>
                                </select>
                                <br>
                            </div>

                            <div class="form-group">
                                <label for="nationality">Nationality</label>
                                <select class="form-control" id="nationality" name="nationality">
                                    <?php if (!empty($nationality)) { ?>
                                        <option value="<?php echo isset($_POST['nationality']) ? $_POST['nationality'] : $nationality; ?>" selected><?php echo isset($_POST['nationality']) ? $_POST['nationality'] : $nationality; ?></option>
                                    <?php } else { ?>
                                        <option value="" selected disabled>Select Nationality</option>
                                    <?php } ?>
                                    <option value="Afghan">Afghan</option>
                                    <option value="Albanian">Albanian</option>
                                    <option value="Algerian">Algerian</option>
                                    <option value="American">American</option>
                                    <option value="Andorran">Andorran</option>
                                    <option value="Angolan">Angolan</option>
                                    <option value="Antiguans">Antiguans</option>
                                    <option value="Argentinean">Argentinean</option>
                                    <option value="Armenian">Armenian</option>
                                    <option value="Australian">Australian</option>
                                    <option value="Austrian">Austrian</option>
                                    <option value="Azerbaijani">Azerbaijani</option>
                                    <option value="Bahamian">Bahamian</option>
                                    <option value="Bahraini">Bahraini</option>
                                    <option value="Bangladeshi">Bangladeshi</option>
                                    <option value="Barbadian">Barbadian</option>
                                    <option value="Barbudans">Barbudans</option>
                                    <option value="Batswana">Batswana</option>
                                    <option value="Belarusian">Belarusian</option>
                                    <option value="Belgian">Belgian</option>
                                    <option value="Belizean">Belizean</option>
                                    <option value="Beninese">Beninese</option>
                                    <option value="Bhutanese">Bhutanese</option>
                                    <option value="Bolivian">Bolivian</option>
                                    <option value="Bosnian">Bosnian</option>
                                    <option value="Brazilian">Brazilian</option>
                                    <option value="British">British</option>
                                    <option value="Bruneian">Bruneian</option>
                                    <option value="Bulgarian">Bulgarian</option>
                                    <option value="Burkinabe">Burkinabe</option>
                                    <option value="Burmese">Burmese</option>
                                    <option value="Burundian">Burundian</option>
                                    <option value="Cambodian">Cambodian</option>
                                    <option value="Cameroonian">Cameroonian</option>
                                    <option value="Canadian">Canadian</option>
                                    <option value="Cape Verdean">Cape Verdean</option>
                                    <option value="Central African">Central African</option>
                                    <option value="Chadian">Chadian</option>
                                    <option value="Chilean">Chilean</option>
                                    <option value="Chinese">Chinese</option>
                                    <option value="Colombian">Colombian</option>
                                    <option value="Comoran">Comoran</option>
                                    <option value="Congolese">Congolese</option>
                                    <option value="Costa Rican">Costa Rican</option>
                                    <option value="Croatian">Croatian</option>
                                    <option value="Cuban">Cuban</option>
                                    <option value="Cypriot">Cypriot</option>
                                    <option value="Czech">Czech</option>
                                    <option value="Danish">Danish</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominican">Dominican</option>
                                    <option value="Dutch">Dutch</option>
                                    <option value="East Timorese">East Timorese</option>
                                    <option value="Ecuadorean">Ecuadorean</option>
                                    <option value="Egyptian">Egyptian</option>
                                    <option value="Emirian">Emirian</option>
                                    <option value="Equatorial Guinean">Equatorial Guinean</option>
                                    <option value="Eritrean">Eritrean</option>
                                    <option value="Estonian">Estonian</option>
                                    <option value="Ethiopian">Ethiopian</option>
                                    <option value="Fijian">Fijian</option>
                                    <option value="Filipino">Filipino</option>
                                    <option value="Finnish">Finnish</option>
                                    <option value="French">French</option>
                                    <option value="Gabonese">Gabonese</option>
                                    <option value="Gambian">Gambian</option>
                                    <option value="Georgian">Georgian</option>
                                    <option value="German">German</option>
                                    <option value="Ghanaian">Ghanaian</option>
                                    <option value="Greek">Greek</option>
                                    <option value="Grenadian">Grenadian</option>
                                    <option value="Guatemalan">Guatemalan</option>
                                    <option value="Guinea-Bissauan">Guinea-Bissauan</option>
                                    <option value="Guinean">Guinean</option>
                                    <option value="Guyanese">Guyanese</option>
                                    <option value="Haitian">Haitian</option>
                                    <option value="Herzegovinian">Herzegovinian</option>
                                    <option value="Honduran">Honduran</option>
                                    <option value="Hungarian">Hungarian</option>
                                    <option value="I-Kiribati">I-Kiribati</option>
                                    <option value="Icelander">Icelander</option>
                                    <option value="Indian">Indian</option>
                                    <option value="Indonesian">Indonesian</option>
                                    <option value="Iranian">Iranian</option>
                                    <option value="Iraqi">Iraqi</option>
                                    <option value="Irish">Irish</option>
                                    <option value="Israeli">Israeli</option>
                                    <option value="Italian">Italian</option>
                                    <option value="Ivorian">Ivorian</option>
                                    <option value="Jamaican">Jamaican</option>
                                    <option value="Japanese">Japanese</option>
                                    <option value="Jordanian">Jordanian</option>
                                    <option value="Kazakhstani">Kazakhstani</option>
                                    <option value="Kenyan">Kenyan</option>
                                    <option value="Kittian and Nevisian">Kittian and Nevisian</option>
                                    <option value="Kuwaiti">Kuwaiti</option>
                                    <option value="Kyrgyz">Kyrgyz</option>
                                    <option value="Laotian">Laotian</option>
                                    <option value="Latvian">Latvian</option>
                                    <option value="Lebanese">Lebanese</option>
                                    <option value="Liberian">Liberian</option>
                                    <option value="Libyan">Libyan</option>
                                    <option value="Liechtensteiner">Liechtensteiner</option>
                                    <option value="Lithuanian">Lithuanian</option>
                                    <option value="Luxembourger">Luxembourger</option>
                                    <option value="Macedonian">Macedonian</option>
                                    <option value="Malagasy">Malagasy</option>
                                    <option value="Malawian">Malawian</option>
                                    <option value="Malaysian">Malaysian</option>
                                    <option value="Maldivan">Maldivan</option>
                                    <option value="Malian">Malian</option>
                                    <option value="Maltese">Maltese</option>
                                    <option value="Marshallese">Marshallese</option>
                                    <option value="Mauritanian">Mauritanian</option>
                                    <option value="Mauritian">Mauritian</option>
                                    <option value="Mexican">Mexican</option>
                                    <option value="Micronesian">Micronesian</option>
                                    <option value="Moldovan">Moldovan</option>
                                    <option value="Monacan">Monacan</option>
                                    <option value="Mongolian">Mongolian</option>
                                    <option value="Moroccan">Moroccan</option>
                                    <option value="Mosotho">Mosotho</option>
                                    <option value="Motswana">Motswana</option>
                                    <option value="Mozambican">Mozambican</option>
                                    <option value="Namibian">Namibian</option>
                                    <option value="Nauruan">Nauruan</option>
                                    <option value="Nepalese">Nepalese</option>
                                    <option value="New Zealander">New Zealander</option>
                                    <option value="Ni-Vanuatu">Ni-Vanuatu</option>
                                    <option value="Nicaraguan">Nicaraguan</option>
                                    <option value="Nigerian">Nigerian</option>
                                    <option value="Nigerien">Nigerien</option>
                                    <option value="North Korean">North Korean</option>
                                    <option value="Northern Irish">Northern Irish</option>
                                    <option value="Norwegian">Norwegian</option>
                                    <option value="Omani">Omani</option>
                                    <option value="Pakistani">Pakistani</option>
                                    <option value="Palauan">Palauan</option>
                                    <option value="Panamanian">Panamanian</option>
                                    <option value="Papua New Guinean">Papua New Guinean</option>
                                    <option value="Paraguayan">Paraguayan</option>
                                    <option value="Peruvian">Peruvian</option>
                                    <option value="Polish">Polish</option>
                                    <option value="Portuguese">Portuguese</option>
                                    <option value="Qatari">Qatari</option>
                                    <option value="Romanian">Romanian</option>
                                    <option value="Russian">Russian</option>
                                    <option value="Rwandan">Rwandan</option>
                                    <option value="Saint Lucian">Saint Lucian</option>
                                    <option value="Salvadoran">Salvadoran</option>
                                    <option value="Samoan">Samoan</option>
                                    <option value="San Marinese">San Marinese</option>
                                    <option value="Sao Tomean">Sao Tomean</option>
                                    <option value="Saudi">Saudi</option>
                                    <option value="Scottish">Scottish</option>
                                    <option value="Senegalese">Senegalese</option>
                                    <option value="Serbian">Serbian</option>
                                    <option value="Seychellois">Seychellois</option>
                                    <option value="Sierra Leonean">Sierra Leonean</option>
                                    <option value="Singaporean">Singaporean</option>
                                    <option value="Slovakian">Slovakian</option>
                                    <option value="Slovenian">Slovenian</option>
                                    <option value="Solomon Islander">Solomon Islander</option>
                                    <option value="Somali">Somali</option>
                                    <option value="South African">South African</option>
                                    <option value="South Korean">South Korean</option>
                                    <option value="Spanish">Spanish</option>
                                    <option value="Sri Lankan">Sri Lankan</option>
                                    <option value="Sudanese">Sudanese</option>
                                    <option value="Surinamer">Surinamer</option>
                                    <option value="Swazi">Swazi</option>
                                    <option value="Swedish">Swedish</option>
                                    <option value="Swiss">Swiss</option>
                                    <option value="Syrian">Syrian</option>
                                    <option value="Taiwanese">Taiwanese</option>
                                    <option value="Tajik">Tajik</option>
                                    <option value="Tanzanian">Tanzanian</option>
                                    <option value="Thai">Thai</option>
                                    <option value="Togolese">Togolese</option>
                                    <option value="Tongan">Tongan</option>
                                    <option value="Trinidadian or Tobagonian">Trinidadian or Tobagonian</option>
                                    <option value="Tunisian">Tunisian</option>
                                    <option value="Turkish">Turkish</option>
                                    <option value="Tuvaluan">Tuvaluan</option>
                                    <option value="Ugandan">Ugandan</option>
                                    <option value="Ukrainian">Ukrainian</option>
                                    <option value="Uruguayan">Uruguayan</option>
                                    <option value="Uzbekistani">Uzbekistani</option>
                                    <option value="Venezuelan">Venezuelan</option>
                                    <option value="Vietnamese">Vietnamese</option>
                                    <option value="Welsh">Welsh</option>
                                    <option value="Yemenite">Yemenite</option>
                                    <option value="Zambian">Zambian</option>
                                    <option value="Zimbabwean">Zimbabwean</option>
                                </select>
                                <br>
                            </div>

                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select class="form-control" name="gender" id="gender">
                                    <?php if (!empty($gender)) { ?>
                                        <option value="<?php echo isset($_POST['gender']) ? $_POST['gender'] : $gender; ?>" selected><?php echo isset($_POST['gender']) ? $_POST['gender'] : $gender; ?></option>
                                    <?php } else { ?>
                                        <option value="" selected disabled>Select Gender</option>
                                    <?php } ?>
                                    <option value="Male" <?php if ($gender == "Male") echo 'selected'; ?>>Male</option>
                                    <option value="Female" <?php if ($gender == "Female") echo 'selected'; ?>>Female</option>
                                    <option value="Other" <?php if ($gender == "Other") echo 'selected'; ?>>Other</option>
                                </select>
                                <br>
                            </div>

                            <div class="form-group">
                                <label for="birthdate">Birthdate</label>
                                <input type="date" class="form-control" value="<?php echo isset($_POST['birthdate']) ? $_POST['birthdate'] : $birthdate; ?>" name="birthdate"><br>
                            </div>

                            <div class="vax-card">
                                <?php if (!empty($vax_card)) { ?>
                                    <img src="../assets/img/vax-card/<?php echo $vax_card; ?>" alt="Vaccination Card" class="card-preview">
                                <?php } else { ?>
                                    <img src="../assets/img/default-vax-card.jpg" alt="Default Vaccination Card" class="card-preview">
                                <?php } ?>
                            </div>

                            <div class="form-group">
                                <label for="vax_card">Upload Vaccination Card</label>
                                <input type="file" class="form-control" id="vax_card" name="vax_card" accept=".jpg,.jpeg,.png" value="<?php echo isset($_POST['vax_card']) ? $_POST['vax_card'] : (isset($vax_card) ? $vax_card : ''); ?>">
                                <br>
                            </div>
                            <?php
                            // Check if the form is submitted
                            if (isset($_POST['submit'])) {
                                // Retrieve the form data
                                $firstName = isset($_POST['first_name']) ? $_POST['first_name'] : '';
                                $middleName = isset($_POST['middle_name']) ? $_POST['middle_name'] : '';
                                $lastName = isset($_POST['last_name']) ? $_POST['last_name'] : '';
                                $cityId = isset($_POST['city_id']) ? $_POST['city_id'] : '';
                                $nationality = isset($_POST['nationality']) ? $_POST['nationality'] : '';
                                $gender = isset($_POST['gender']) ? $_POST['gender'] : '';
                                $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';

                                $is_vaxxed = 0;

                                if (!empty($_FILES['profile_photo']['name'])) {
                                    $profile_photo = $_FILES['profile_photo']['name'];
                                    $temp_name = $_FILES['profile_photo']['tmp_name'];
                                    $folder_path = "../assets/img/profile-photo/";
                                    move_uploaded_file($temp_name, $folder_path . $profile_photo);
                                }

                                if (!empty($_FILES['vax_card']['name'])) {
                                    $vax_card = $_FILES['vax_card']['name'];
                                    $temp_name = $_FILES['vax_card']['tmp_name'];
                                    $folder_path = "../assets/img/vax-card/";
                                    move_uploaded_file($temp_name, $folder_path . $vax_card);
                                    $is_vaxxed = 1;
                                }

                                $stmt = $db->prepare("UPDATE user_profile SET profile_photo=?, first_name=?, middle_name=?, last_name=?, city_id=?, nationality=?, gender=?, birthdate=?, is_vaxxed=?, vax_card=? WHERE user_id=?");
                                $stmt->bind_param("ssssisssssi", $profile_photo, $firstName, $middleName, $lastName, $cityId, $nationality, $gender, $birthdate, $is_vaxxed, $vax_card, $userid);

                                if ($stmt->execute()) {
                                    if ($stmt->affected_rows > 0) {
                                        echo '<div style="text-align: center;"><h5 style="color: green; font-size:16px;">Profile Updated</h5></div>';
                                    } else {
                                        echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">No Changes</h5></div>';
                                    }
                                } else {
                                    echo '<div style="text-align: center;"><h5 style="color: red; font-size:16px;">Update Unsuccessful</h5></div>';
                                    echo 'Error: ' . $stmt->error; // Print any error message
                                }
                                mysqli_close($db);
                            }
                            ?>

                            <button type="submit" style="float:right; margin-right: 1%;" name="submit" id="submit" class="btn btn-success">Save Changes</button>
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