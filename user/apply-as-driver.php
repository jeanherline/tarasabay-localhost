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
<title>Apply As A Driver</title>

<link rel="icon" href="../assets/img/logo.png" type="images" />
<!-- Include Chart.js library -->

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
                        <a href="#">Apply As A Driver</a>
                    </li>
                    <li class="breadcrumb-item active">Onboarding Form</li>
                </ol>
                <hr>
                <div class="card">
                    <div class="card-header">
                        <b>Earn With TaraSabay</b><br>
                        <em>Complete your application and start driving with TaraSabay!</em>
                    </div>
                    <div class="card-body">
                        <form method="POST" autocomplete="on" enctype="multipart/form-data">
                            <div id="emailHelp" class="form-text">
                                <b>Submit -> Review -> Activation</b><br>
                                <?php
                                $user_id = $_SESSION['user_id'];
                                $query = "SELECT * FROM driver_identification WHERE user_id = $user_id AND driver_stat = 'Pending'";
                                $result = mysqli_query($db, $query);

                                if (mysqli_num_rows($result) > 0) {
                                    $car = mysqli_fetch_assoc($result);
                                ?><br>
                                    <em style="color: green;"><b>Status Update: </b>You already submitted your Driver Onboarding / Renewal Form</em>
                                    <br><br>
                                    Thank you for submitting your requirements to become a driver on TaraSabay. We have successfully received your information.
                                    To be officially qualified as a driver, we kindly request you to register a car on the TaraSabay App. This will allow you to offer rides and maximize your earning potential on our platform.</p>
                                    Please reload or log in again to the TaraSabay Web App and navigate to the car registration section to complete the process.
                                    Alternatively, you may use or click the button below to redirect to the car registration page: <br><br>
                                    <a href="addCar.php" class="btn btn-primary">Register a Car</a>
                                    <br><br>
                                    If you have submitted these documents for driver's license renewal, please ensure that you have gathered all the necessary documents listed below:

                                    <ul style="text-align: center;">
                                        Photocopy of the TIN (Tax Identification Number) ID <br>
                                        Photocopy of Owner's Government ID with 3 Original Specimen Signatures<br>
                                        Photocopy of either NBI Clearance, Police Clearance, or CIBI Clearance<br>
                                    </ul>

                                    Please ensure that these documents are ready for the renewal process. If you have any questions or encounter any difficulties, please reach out to our support team at support@tarasabay.com for assistance.
                                    <br><br>
                                    <p style="color: #333333; font-size: 16px;">If you encounter any difficulties or have any questions, our support team at support@tarasabay.com is ready to assist you.
                                        <br>
                                        <em>We look forward to having you as a qualified driver on TaraSabay!</em>
                                        <br>
                                    <?php
                                }
                                    ?>

                                    <?php
                                    $user_id = $_SESSION['user_id'];
                                    $query = "SELECT * FROM driver_identification WHERE user_id = $user_id AND driver_stat = 'Expired'";
                                    $result = mysqli_query($db, $query);

                                    if (mysqli_num_rows($result) > 0) {
                                        $car = mysqli_fetch_assoc($result);
                                    ?>
                                        <br>
                                        <em style="color: green;"><b>Status Update: </b>Please check your license expiration, as it appears to have expired.<br>
                                            To proceed with the renewal process, please follow these steps:</em>
                                        <br> <br>
                                        1. Fill out the renewal form: Begin by filling out the renewal form. You can find this form on the TaraSabay website or by contacting their customer support.
                                        <br><br>
                                        2. Gather the required documents: Ensure you have the following documents ready:
                                        - Photocopy of your Driver's License: Make a photocopy of your valid Driver's License.
                                        - Photocopy of the TIN (Tax Identification Number) ID: Provide a photocopy of your TIN ID.
                                        - Photocopy of Owner's Government ID with 3 Original Specimen Signatures: Prepare a photocopy of your government-issued ID that includes three original specimen signatures.
                                        - Photocopy of NBI Clearance or Police Clearance: Include a photocopy of either your NBI Clearance, Police Clearance or CIBI Clearance.
                                        - Photocopy of : Provide a photocopy of your CIBI clearance.
                                        <br><br>
                                        3. Submit your documents: Once you have filled out the renewal form and gathered all the necessary documents, submit them to the nearest TaraSabay office in <b><?php echo $_SESSION['city_name']; ?></b>. You may contact their customer support for the exact location and office hours.
                                        <br><br>
                                        By following these steps and submitting all the required documents, you can ensure the smooth continuation of your TaraSabay driver account.
                                        <br> <br>
                                        <em>Personal Information</em>
                                    <div class="form-group">
                                        <label for="profile_photo">Profile Photo<span class="text-danger"> *</span></label>
                                        <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept=".jpg,.jpeg,.png" value="" required>
                                    </div>
                                    <small class="form-text text-muted"> - Your profile photo helps people recognize you.<br>Please note that once you submit your profile photo it cannot be changed.
                                        <br>
                                        <br>1. Face the camera directly with your eyes and mouth clearly visible.
                                        <br>2. Make sure the photo is well lit, free of glare, and in focus.
                                        <br>3. No photos of a photo, filters, or alterations.</small>
                                    <br>
                                    <div class="form-group">
                                        <label for="pwd">Are you a PWD? <span class="text-danger">*</span></label>
                                        <select class="form-control" id="pwd" name="pwd" required>
                                            <option value="" selected disabled>Select Yes/No</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div id="pwdDetailsContainer" style="display: none;">
                                        <div class="form-group">
                                            <label for="disabilityType">Choose type of disability</label>
                                            <select class="form-control" id="disabilityType" name="disabilityType">
                                                <option value="" selected disabled>Select Type of disability</option>
                                                <option value="Deaf/Hearing Impairment">Deaf/Hearing Impairment</option>
                                                <option value="Blind/Low Vision">Blind/Low Vision</option>
                                                <option value="Speech/Language Disability">Speech/Language Disability</option>
                                                <option value="Physical/Muscular/Orthopedic Disability">Physical/Muscular/Orthopedic Disability</option>
                                                <option value="intellectual">Intellectual/Mental/Learning Disability</option>
                                                <option value="Others">Others</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a type of disability.</div>
                                        </div>


                                        <div id="otherDisabilityContainer" style="display: none;">
                                            <div class="form-group">
                                                <label for="otherDisability">Indicate Type of Disability</label>
                                                <input type="text" class="form-control" placeholder="Enter Type of Disability" id="otherDisability" name="otherDisability">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="pwdID">Upload PWD ID or Certificate (Required if Yes PWD)</label>
                                            <input type="file" class="form-control" id="pwdID" name="pwdID" accept=".jpg,.jpeg,.png">
                                        </div>
                                    </div>

                                    <script>
                                        document.getElementById('pwd').addEventListener('change', function() {
                                            var selectedValue = this.value;
                                            var pwdDetailsContainer = document.getElementById('pwdDetailsContainer');
                                            var otherDisabilityContainer = document.getElementById('otherDisabilityContainer');

                                            if (selectedValue === '1') {
                                                pwdDetailsContainer.style.display = 'block';
                                                document.getElementById('pwdID').required = true;
                                            } else {
                                                pwdDetailsContainer.style.display = 'none';
                                                document.getElementById('pwdID').required = false;
                                            }
                                        });

                                        document.getElementById('disabilityType').addEventListener('change', function() {
                                            var selectedValue = this.value;
                                            var otherDisabilityContainer = document.getElementById('otherDisabilityContainer');

                                            if (selectedValue === 'Others') {
                                                otherDisabilityContainer.style.display = 'block';
                                            } else {
                                                otherDisabilityContainer.style.display = 'none';
                                            }
                                        });
                                    </script>


                                    <br>

                                    <div class="form-group">
                                        <label for="covid_agreement">Agreement (COVID-19) <span class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="covid_agreement" name="covid_agreement" required>
                                            <label class="form-check-label" for="covid_agreement">
                                                I agree to abide by the COVID-19 safety protocols and guidelines.
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="vax_card">Upload Vaccination Card<span class="text-danger"> *</span></label>
                                        <input type="file" class="form-control" id="vax_card" name="vax_card" accept=".jpg,.jpeg,.png" required>
                                        <small class="form-text text-muted">Please upload a scanned copy or photo of your vaccination card.</small>
                                        <br>
                                    </div>

                                    <div id="emailHelp" class="form-text">
                                        <em>In Case of Emergency</em>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="emergency_contact_name">Emergency Contact Name<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Enter Emergency Contact Name" name="emergency_contact_name" required>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="relationship">Relationship<span class="text-danger"> *</span></label>
                                        <select class="form-control" name="relationship" id="relationship" required>
                                            <option value="" selected disabled>Select Relationship</option>
                                            <option value="Spouse">Spouse</option>
                                            <option value="Father">Father</option>
                                            <option value="Mother">Mother</option>
                                            <option value="Sibling">Sibling</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="emergency_contact_number">Emergency Contact Number<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Enter Emergency Contact Number" name="emergency_contact_number" required>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="emergency_contact_address">Address<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Enter Address" name="emergency_contact_address" required>
                                        <br>
                                    </div>

                                    <div id="emailHelp" class="form-text">
                                        <em>Driving Information</em>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="driver_license_front">Driver's License (Front)<span class="text-danger"> *</span></label>
                                        <input type="file" class="form-control" id="driver_license_front" name="driver_license_front" accept=".jpg,.jpeg,.png" required>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="driver_license_back">Driver's License (Back)<span class="text-danger"> *</span></label>
                                        <input type="file" class="form-control" id="driver_license_back" name="driver_license_back" accept=".jpg,.jpeg,.png" required>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="expiration_date">Driver's License Expiration Date<span class="text-danger"> *</span></label>
                                        <input type="date" class="form-control" name="expiration_date" required>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="years_experience">Indicate Years of Experience<span class="text-danger"> *</span></label>
                                        <input type="number" class="form-control" name="years_experience" placeholder="Enter Years of Experience" required>
                                        <br>
                                    </div>


                                    <div class="form-group">
                                        <label for="age_above_sixty">Are you 60 years old and above?<span class="text-danger"> *</span></label>
                                        <select class="form-control" name="age_above_sixty" id="age_above_sixty" required>
                                            <option value="" selected disabled>Select Yes/No</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="nbi_police_cibi_document">Which document are you uploading?<span class="text-danger"> *</span></label>
                                        <select class="form-control" name="nbi_police_cibi_document" id="nbi_police_cibi_document" required>
                                            <option value="" selected disabled>Please choose one</option>
                                            <option value="NBI Clearance">NBI Clearance</option>
                                            <option value="Police Clearance">Police Clearance</option>
                                            <option value="CIBI Background Check Report">CIBI Background Check Report</option>
                                        </select>
                                        <br>
                                    </div>
                                    <div class="form-group">
                                        <label for="nbi_police_cibi_photo">Upload Photo (Selected if NBI / Police Clearance / CIBI)<span class="text-danger"> *</span></label>
                                        <input type="file" class="form-control" id="nbi_police_cibi_photo" name="nbi_police_cibi_photo" accept=".jpg,.jpeg,.png" required>
                                        <br>
                                    </div>

                                    <div class="form-group" id="dateIssuedContainer" style="display: none;">
                                        <label for="date_issued">CIBI Date of Issuance<span class="text-danger"> *</span></label>
                                        <input type="date" class="form-control" id="date_issued" name="date_issued">
                                        <div class="invalid-feedback">Please enter the date of issuance.</div>
                                    </div>

                                    <script>
                                        document.getElementById('nbi_police_cibi_document').addEventListener('change', function() {
                                            var selectedValue = this.value;
                                            var dateIssuedContainer = document.getElementById('dateIssuedContainer');

                                            if (selectedValue === 'CIBI Background Check Report') {
                                                dateIssuedContainer.style.display = 'block';
                                            } else {
                                                dateIssuedContainer.style.display = 'none';
                                            }
                                        });
                                    </script>

                                    <div id="emailHelp" class="form-text">
                                        <em>Consents<span class="text-danger"> *</span></em>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="privacy_policy" name="privacy_policy" required>
                                            <label class="form-check-label" for="privacy_policy">
                                                I have read, understand, and agree to TaraSabay's Privacy Policy.
                                            </label>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="terms_of_service" name="terms_of_service" required>
                                            <label class="form-check-label" for="terms_of_service">
                                                I have read, understand, and agree to TaraSabay's Terms of Service.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="code_of_conduct" name="code_of_conduct" required>
                                            <label class="form-check-label" for="code_of_conduct">
                                                I have read, understand, and agree to TaraSabay's Code of Conduct.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="personal_data_usage" name="personal_data_usage" required>
                                            <label class="form-check-label" for="personal_data_usage">
                                                I agree to let TaraSabay collect, use, process, and share my personal data only for Relevant Purposes.
                                            </label>
                                        </div>
                                    </div>
                                    <br>
                                    <div id="emailHelp" class="form-text">
                                        <em>Declarations<span class="text-danger"> *</span></em>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="driving_license_declaration" name="driving_license_declaration" required>
                                            <label class="form-check-label" for="driving_license_declaration">
                                                My driving license has not been disqualified or suspended.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="court_declaration" name="court_declaration" required>
                                            <label class="form-check-label" for="court_declaration">
                                                I have not been convicted by any court or tribunal.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="waiting_for_trial_declaration" name="waiting_for_trial_declaration" required>
                                            <label class="form-check-label" for="waiting_for_trial_declaration">
                                                I am not awaiting any type of trial in court.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="medical_condition_declaration" name="medical_condition_declaration" required>
                                            <label class="form-check-label" for="medical_condition_declaration">
                                                I do not have any medical condition that would make me unfit for safe driving.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="top_up_clawback_declaration" name="top_up_clawback_declaration" required>
                                            <label class="form-check-label" for="top_up_clawback_declaration">
                                                I agree to allow TaraSabay to top-up, add funds, and perform clawback or deductions from my cash wallet for any TaraSabay-related transactions.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="personal_data_usage_declaration" name="personal_data_usage_declaration" required>
                                            <label class="form-check-label" for="personal_data_usage_declaration">
                                                I consent to the use of my personal data by TaraSabay, including my government ID, personal information, and status, for conducting background checks and performing reasonable actions as stated in TaraSabay's privacy policy.
                                            </label>
                                        </div>
                                    </div>
                                    <br>
                                    <?php
                                        if (isset($_POST['submit'])) {
                                            $user_id = $_SESSION['user_id'];

                                            $license_expiration = $_POST['expiration_date'];

                                            $stmt = $db->prepare("UPDATE user_identification SET identity_expiration = ? WHERE user_id = ? AND identity_type = 'Driver License'");
                                            $stmt->bind_param("si", $license_expiration, $user_id);
                                            $stmt->execute();
                                            $stmt->close();

                                            // Update driver_identification table
                                            $profile_photo_path = $_FILES["profile_photo"]["tmp_name"];
                                            $profile_photo_filename = $_FILES["profile_photo"]["name"];
                                            $profile_photo_destination = "../assets/img/profile-photo/" . $profile_photo_filename;
                                            move_uploaded_file($profile_photo_path, $profile_photo_destination);

                                            $vax_card_path = $_FILES["vax_card"]["tmp_name"];
                                            $vax_card_filename = $_FILES["vax_card"]["name"];
                                            $vax_card_destination = "../assets/img/vax-card/" . $vax_card_filename;
                                            move_uploaded_file($vax_card_path, $vax_card_destination);

                                            $driver_license_front_path = $_FILES["driver_license_front"]["tmp_name"];
                                            $driver_license_front_filename = $_FILES["driver_license_front"]["name"];
                                            $driver_license_front_destination = "../assets/img/license/" . $driver_license_front_filename;
                                            move_uploaded_file($driver_license_front_path, $driver_license_front_destination);

                                            $driver_license_back_path = $_FILES["driver_license_back"]["tmp_name"];
                                            $driver_license_back_filename = $_FILES["driver_license_back"]["name"];
                                            $driver_license_back_destination = "../assets/img/license/" . $driver_license_back_filename;
                                            move_uploaded_file($driver_license_back_path, $driver_license_back_destination);

                                            $nbi_police_cbi_photo_path = $_FILES["nbi_police_cibi_photo"]["tmp_name"];
                                            $nbi_police_cbi_photo_filename = $_FILES["nbi_police_cibi_photo"]["name"];
                                            $nbi_police_cbi_photo_destination = "../assets/img/docx/" . $nbi_police_cbi_photo_filename;
                                            move_uploaded_file($nbi_police_cbi_photo_path, $nbi_police_cbi_photo_destination);

                                            $pwdID_path = $_FILES["pwdID"]["tmp_name"];
                                            $pwdID_filename = $_FILES["pwdID"]["name"];
                                            $pwdID_destination = "../assets/img/pwd/" . $pwdID_filename;
                                            move_uploaded_file($pwdID_path, $pwdID_destination);

                                            // Retrieve form data
                                            $profile_photo = $profile_photo_filename;
                                            $is_vaxxed = isset($_POST['covid_agreement']) ? 1 : 0;
                                            $vax_card = $vax_card_filename;
                                            $emergency_contact_name = $_POST['emergency_contact_name'];
                                            $relationship = $_POST['relationship'];
                                            $emergency_contact_number = $_POST['emergency_contact_number'];
                                            $emergency_contact_address = $_POST['emergency_contact_address'];
                                            $driver_license_front = $driver_license_front_filename;
                                            $driver_license_back = $driver_license_back_filename;
                                            $license_expiration = date('Y-m-d', strtotime($_POST['expiration_date']));
                                            $is_above_60 = $_POST['age_above_sixty'] === '1' ? 1 : 0;
                                            $nbi_police_cbi = $_POST['nbi_police_cibi_document'];
                                            $nbi_police_cbi_photo = $nbi_police_cbi_photo_filename;
                                            $pwd_docx = $pwdID_filename;
                                            $years_experience = $_POST['years_experience'];
                                            $date_issued = $_POST['date_issued'];

                                            // Update the driver_identification table
                                            if (isset($_POST['disabilityType'])) {
                                                if ($_POST['disabilityType'] == 'Others') {
                                                    $otherDisability = isset($_POST['otherDisability']) ? $_POST['otherDisability'] : '';
                                                } else {
                                                    $otherDisability = $_POST['disabilityType'];
                                                }
                                            } else {
                                                $otherDisability = '';
                                            }

                                            // Update the user_profile table
                                            $stmt = $db->prepare("UPDATE user_profile SET profile_photo = ?, is_vaxxed = ?, vax_card = ? WHERE user_id = ?");
                                            $stmt->bind_param("siis", $profile_photo, $is_vaxxed, $vax_card, $user_id);
                                            $stmt->execute();

                                            $stmt = $db->prepare("UPDATE driver_identification SET disability = ?, pwd_docx = ?, license_front = ?, license_back = ?, license_expiration = ?, is_above_60 = ?, nbi_police_cbi = ?, cbi_date_issued = ?, years_experience = ?, driver_stat = 'Renew' WHERE user_id = ?");
                                            $stmt->bind_param("sssssissis", $otherDisability, $pwd_docx, $driver_license_front, $driver_license_back, $license_expiration, $is_above_60, $nbi_police_cbi_photo_filename, $date_issued, $years_experience, $user_id);
                                            $stmt->execute();
                                            $stmt->close();

                                            // Update the emergency table
                                            $stmt = $db->prepare("UPDATE emergency SET name = ?, relationship = ?, phone = ?, address = ? WHERE user_id = ?");
                                            $stmt->bind_param("ssssi", $emergency_contact_name, $relationship, $emergency_contact_number, $emergency_contact_address, $user_id);
                                            $stmt->execute();

                                            // Send email notification
                                            $mail = new PHPMailer(true);

                                            // Fetch the email from the user_profile table
                                            $stmt = $db->prepare("SELECT email FROM user_profile WHERE user_id = ?");
                                            $stmt->bind_param("i", $user_id);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            $row = $result->fetch_assoc();
                                            $email = $row['email'];

                                            $stmt->close();
                                            $result->close();

                                            try {
                                                $mail->SMTPDebug = 0;
                                                $mail->isSMTP();
                                                $mail->Host = 'smtp.gmail.com';
                                                $mail->SMTPAuth = true;
                                                $mail->Username = 'carpoolapp01@gmail.com';
                                                $mail->Password = 'wzspvmmnnxhtbuxd';
                                                $mail->SMTPSecure = 'tls';
                                                $mail->Port = 587;

                                                $mail->setFrom('noreply@tarasabay.com', 'TaraSabay PH');
                                                $mail->addAddress($email);
                                                $mail->addCustomHeader('X-Priority', '1');
                                                $mail->addCustomHeader('Importance', 'High');

                                                $mail->isHTML(true);
                                                $mail->Subject = 'Driver Verification';
                                                $mail->Body = "
        <html>
        <head>
        <style>
            body {
                font-family: Arial, sans-serif;
                font-size: 16px;
                line-height: 1.6;
                color: #444;
            }
            h1 {
                font-size: 24px;
                font-weight: bold;
                color: #333;
                margin: 0 0 30px;
                text-align: center;
            }
            p {
                margin: 0 0 20px;
            }
            a {
                color: #0072C6;
                text-decoration: none;
            }
            a:hover {
                text-decoration: underline;
            }
            .container {
                max-width: 600px;
                margin: 0 auto;
            }
        </style>
        </head>
        <body>
        <div class=\"container\">
            <h1>Driver License Renewal Requirements Received!</h1>
            <p>Dear valued driver,</p>
            <p>We have received your requirements for driver's license renewal on TaraSabay. We appreciate your cooperation in this process.</p>
            <p>To proceed with the driver's license renewal, please ensure that you have gathered all the necessary documents listed below:</p>
            <ul>
                <li>Photocopy of your valid Driver's License</li>
                <li>Photocopy of the TIN (Tax Identification Number) ID</li>
                <li>Photocopy of Owner's Government ID with 3 Original Specimen Signatures</li>
                <li>Photocopy of either NBI Clearance, Police Clearance, or CIBI Clearance</li>
            </ul>
            <p>Now, after you have gathered all the necessary documents, please submit them to the nearest TaraSabay office in Iloilo City. You may contact our customer support for the exact location and office hours.</p>
            <p>By following these steps and submitting all the required documents, you can ensure the smooth continuation of your TaraSabay driver account.</p>
            <p>If you have any questions or need further assistance, please contact our support team at support@tarasabay.com. We are here to assist you throughout the driver's license renewal process.</p>
            <p>We appreciate your dedication to maintaining compliance with our policies and guidelines.</p>
            <p>Best regards,</p>
            <p>TaraSabay PH Team</p>
        </div>
        </body>
        </html>
        ";
                                                $mail->send();

                                                echo "<div style=\"text-align: center; font-family: 'Poppins', sans-serif; background-color: #FFFFFF; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;\">
            <img src=\"../assets/img/checked.png\" alt=\"Car Registration\" style=\"margin-bottom: 20px; width: 100px\">
            <h5 style=\"color: #4CAF50; font-size: 24px; margin-bottom: 20px;\">Driver Renewal Requirements Received!</h5>
            <p style=\"color: #333333; font-size: 16px; margin-bottom: 20px;\">An email has been sent to your email address with the requirements needed to be submitted to the nearest TaraSabay office in your city.</p>
            <p style=\"color: #333333; font-size: 16px;\">These requirements are necessary to become an official driver of the TaraSabay app.</p>
        </div>";
                                            } catch (Exception $e) {
                                                echo '<div style="text-align: center;">
            <h5 style="color: red">Error sending verification email: </h5>' . $mail->ErrorInfo . '
        </div>';
                                            }
                                        }
                                    ?>

                                    <button type="submit" name="submit" class="btn btn-success">Register</button>
                                    <br>
                                <?php

                                    }
                                ?>
                            </div>
                            <?php
                            $user_id = $_SESSION['user_id'];
                            $query = "SELECT * FROM driver_identification WHERE user_id = $user_id AND driver_stat = 'Active'";
                            $result = mysqli_query($db, $query);

                            if (mysqli_num_rows($result) > 0) {
                                $car = mysqli_fetch_assoc($result);
                            ?>
                                <br>
                                <em style="color: green;"><b>Status Update: </b>Your Driver Registration has been Approved.</em>
                                <?php
                            } else if (mysqli_num_rows($result) == 0) {
                                $user_id = $_SESSION['user_id'];
                                $query = "SELECT * FROM driver_identification WHERE user_id = $user_id AND driver_stat = 'Pending' || driver_stat = 'Approved' || driver_stat = 'Expired'";
                                $result = mysqli_query($db, $query);

                                if (mysqli_num_rows($result) == 0) {
                                ?>
                                    <em>Personal Information</em>
                                    <div class="form-group">
                                        <label for="profile_photo">Profile Photo<span class="text-danger"> *</span></label>
                                        <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept=".jpg,.jpeg,.png" value="" required>
                                    </div>
                                    <small class="form-text text-muted"> - Your profile photo helps people recognize you.<br>Please note that once you submit your profile photo it cannot be changed.
                                        <br>
                                        <br>1. Face the camera directly with your eyes and mouth clearly visible.
                                        <br>2. Make sure the photo is well lit, free of glare, and in focus.
                                        <br>3. No photos of a photo, filters, or alterations.</small>
                                    <br>
                                    <div class="form-group">
                                        <label for="pwd">Are you a PWD? <span class="text-danger">*</span></label>
                                        <select class="form-control" id="pwd" name="pwd" required>
                                            <option value="" selected disabled>Select Yes/No</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>

                                    <div id="pwdDetailsContainer" style="display: none;">
                                        <div class="form-group">
                                            <label for="disabilityType">Choose type of disability</label>
                                            <select class="form-control" id="disabilityType" name="disabilityType">
                                                <option value="" selected disabled>Select Type of disability</option>
                                                <option value="Deaf/Hearing Impairment">Deaf/Hearing Impairment</option>
                                                <option value="Blind/Low Vision">Blind/Low Vision</option>
                                                <option value="Speech/Language Disability">Speech/Language Disability</option>
                                                <option value="Physical/Muscular/Orthopedic Disability">Physical/Muscular/Orthopedic Disability</option>
                                                <option value="intellectual">Intellectual/Mental/Learning Disability</option>
                                                <option value="Others">Others</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a type of disability.</div>
                                        </div>


                                        <div id="otherDisabilityContainer" style="display: none;">
                                            <div class="form-group">
                                                <label for="otherDisability">Indicate Type of Disability</label>
                                                <input type="text" class="form-control" placeholder="Enter Type of Disability" id="otherDisability" name="otherDisability">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="pwdID">Upload PWD ID or Certificate (Required if Yes PWD)</label>
                                            <input type="file" class="form-control" id="pwdID" name="pwdID" accept=".jpg,.jpeg,.png">
                                        </div>
                                    </div>

                                    <script>
                                        document.getElementById('pwd').addEventListener('change', function() {
                                            var selectedValue = this.value;
                                            var pwdDetailsContainer = document.getElementById('pwdDetailsContainer');
                                            var otherDisabilityContainer = document.getElementById('otherDisabilityContainer');

                                            if (selectedValue === '1') {
                                                pwdDetailsContainer.style.display = 'block';
                                                document.getElementById('pwdID').required = true;
                                            } else {
                                                pwdDetailsContainer.style.display = 'none';
                                                document.getElementById('pwdID').required = false;
                                            }
                                        });

                                        document.getElementById('disabilityType').addEventListener('change', function() {
                                            var selectedValue = this.value;
                                            var otherDisabilityContainer = document.getElementById('otherDisabilityContainer');

                                            if (selectedValue === 'Others') {
                                                otherDisabilityContainer.style.display = 'block';
                                            } else {
                                                otherDisabilityContainer.style.display = 'none';
                                            }
                                        });
                                    </script>

                                    <br>

                                    <div class="form-group">
                                        <label for="covid_agreement">Agreement (COVID-19) <span class="text-danger">*</span></label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="covid_agreement" name="covid_agreement" required>
                                            <label class="form-check-label" for="covid_agreement">
                                                I agree to abide by the COVID-19 safety protocols and guidelines.
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="vax_card">Upload Vaccination Card<span class="text-danger"> *</span></label>
                                        <input type="file" class="form-control" id="vax_card" name="vax_card" accept=".jpg,.jpeg,.png" required>
                                        <small class="form-text text-muted">Please upload a scanned copy or photo of your vaccination card.</small>
                                        <br>
                                    </div>

                                    <div id="emailHelp" class="form-text">
                                        <em>In Case of Emergency</em>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="emergency_contact_name">Emergency Contact Name<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Enter Emergency Contact Name" name="emergency_contact_name" required>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="relationship">Relationship<span class="text-danger"> *</span></label>
                                        <select class="form-control" name="relationship" id="relationship" required>
                                            <option value="" selected disabled>Select Relationship</option>
                                            <option value="Spouse">Spouse</option>
                                            <option value="Father">Father</option>
                                            <option value="Mother">Mother</option>
                                            <option value="Sibling">Sibling</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="emergency_contact_number">Emergency Contact Number<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Enter Emergency Contact Number" name="emergency_contact_number" required>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="emergency_contact_address">Address<span class="text-danger"> *</span></label>
                                        <input type="text" class="form-control" placeholder="Enter Address" name="emergency_contact_address" required>
                                        <br>
                                    </div>

                                    <div id="emailHelp" class="form-text">
                                        <em>Driving Information</em>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <label for="driver_license_front">Driver's License (Front)<span class="text-danger"> *</span></label>
                                        <input type="file" class="form-control" id="driver_license_front" name="driver_license_front" accept=".jpg,.jpeg,.png" required>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="driver_license_back">Driver's License (Back)<span class="text-danger"> *</span></label>
                                        <input type="file" class="form-control" id="driver_license_back" name="driver_license_back" accept=".jpg,.jpeg,.png" required>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="expiration_date">Driver's License Expiration Date<span class="text-danger"> *</span></label>
                                        <input type="date" class="form-control" name="expiration_date" required>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="years_experience">Indicate Years of Experience<span class="text-danger"> *</span></label>
                                        <input type="number" class="form-control" name="years_experience" placeholder="Enter Years of Experience" required>
                                        <br>
                                    </div>


                                    <div class="form-group">
                                        <label for="age_above_sixty">Are you 60 years old and above?<span class="text-danger"> *</span></label>
                                        <select class="form-control" name="age_above_sixty" id="age_above_sixty" required>
                                            <option value="" selected disabled>Select Yes/No</option>
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        <br>
                                    </div>

                                    <div class="form-group">
                                        <label for="nbi_police_cibi_document">Which document are you uploading?<span class="text-danger"> *</span></label>
                                        <select class="form-control" name="nbi_police_cibi_document" id="nbi_police_cibi_document" required>
                                            <option value="" selected disabled>Please choose one</option>
                                            <option value="NBI Clearance">NBI Clearance</option>
                                            <option value="Police Clearance">Police Clearance</option>
                                            <option value="CIBI Background Check Report">CIBI Background Check Report</option>
                                        </select>
                                        <br>
                                    </div>
                                    <div class="form-group">
                                        <label for="nbi_police_cibi_photo">Upload Photo (Selected if NBI / Police Clearance / CIBI)<span class="text-danger"> *</span></label>
                                        <input type="file" class="form-control" id="nbi_police_cibi_photo" name="nbi_police_cibi_photo" accept=".jpg,.jpeg,.png" required>
                                        <br>
                                    </div>

                                    <div class="form-group" id="dateIssuedContainer" style="display: none;">
                                        <label for="date_issued">CIBI Date of Issuance<span class="text-danger"> *</span></label>
                                        <input type="date" class="form-control" id="date_issued" name="date_issued">
                                        <div class="invalid-feedback">Please enter the date of issuance.</div>
                                    </div>

                                    <script>
                                        document.getElementById('nbi_police_cibi_document').addEventListener('change', function() {
                                            var selectedValue = this.value;
                                            var dateIssuedContainer = document.getElementById('dateIssuedContainer');

                                            if (selectedValue === 'CIBI Background Check Report') {
                                                dateIssuedContainer.style.display = 'block';
                                            } else {
                                                dateIssuedContainer.style.display = 'none';
                                            }
                                        });
                                    </script>

                                    <div id="emailHelp" class="form-text">
                                        <em>Consents<span class="text-danger"> *</span></em>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="privacy_policy" name="privacy_policy" required>
                                            <label class="form-check-label" for="privacy_policy">
                                                I have read, understand, and agree to TaraSabay's Privacy Policy.
                                            </label>
                                        </div>

                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="terms_of_service" name="terms_of_service" required>
                                            <label class="form-check-label" for="terms_of_service">
                                                I have read, understand, and agree to TaraSabay's Terms of Service.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="code_of_conduct" name="code_of_conduct" required>
                                            <label class="form-check-label" for="code_of_conduct">
                                                I have read, understand, and agree to TaraSabay's Code of Conduct.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="personal_data_usage" name="personal_data_usage" required>
                                            <label class="form-check-label" for="personal_data_usage">
                                                I agree to let TaraSabay collect, use, process, and share my personal data only for Relevant Purposes.
                                            </label>
                                        </div>
                                    </div>
                                    <br>
                                    <div id="emailHelp" class="form-text">
                                        <em>Declarations<span class="text-danger"> *</span></em>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="driving_license_declaration" name="driving_license_declaration" required>
                                            <label class="form-check-label" for="driving_license_declaration">
                                                My driving license has not been disqualified or suspended.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="court_declaration" name="court_declaration" required>
                                            <label class="form-check-label" for="court_declaration">
                                                I have not been convicted by any court or tribunal.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="waiting_for_trial_declaration" name="waiting_for_trial_declaration" required>
                                            <label class="form-check-label" for="waiting_for_trial_declaration">
                                                I am not awaiting any type of trial in court.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="medical_condition_declaration" name="medical_condition_declaration" required>
                                            <label class="form-check-label" for="medical_condition_declaration">
                                                I do not have any medical condition that would make me unfit for safe driving.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="top_up_clawback_declaration" name="top_up_clawback_declaration" required>
                                            <label class="form-check-label" for="top_up_clawback_declaration">
                                                I agree to allow TaraSabay to top-up, add funds, and perform clawback or deductions from my cash wallet for any TaraSabay-related transactions.
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="personal_data_usage_declaration" name="personal_data_usage_declaration" required>
                                            <label class="form-check-label" for="personal_data_usage_declaration">
                                                I consent to the use of my personal data by TaraSabay, including my government ID, personal information, and status, for conducting background checks and performing reasonable actions as stated in TaraSabay's privacy policy.
                                            </label>
                                        </div>
                                    </div>
                                    <br>
                                    <?php
                                    if (isset($_POST['submit'])) {
                                        $user_id = $_SESSION['user_id'];

                                        // Process and move uploaded files
                                        $profile_photo_path = $_FILES["profile_photo"]["tmp_name"];
                                        $profile_photo_filename = $_FILES["profile_photo"]["name"];
                                        $profile_photo_destination = "../assets/img/profile-photo/" . $profile_photo_filename;
                                        move_uploaded_file($profile_photo_path, $profile_photo_destination);

                                        $vax_card_path = $_FILES["vax_card"]["tmp_name"];
                                        $vax_card_filename = $_FILES["vax_card"]["name"];
                                        $vax_card_destination = "../assets/img/vax-card/" . $vax_card_filename;
                                        move_uploaded_file($vax_card_path, $vax_card_destination);

                                        $driver_license_front_path = $_FILES["driver_license_front"]["tmp_name"];
                                        $driver_license_front_filename = $_FILES["driver_license_front"]["name"];
                                        $driver_license_front_destination = "../assets/img/license/" . $driver_license_front_filename;
                                        move_uploaded_file($driver_license_front_path, $driver_license_front_destination);

                                        $driver_license_back_path = $_FILES["driver_license_back"]["tmp_name"];
                                        $driver_license_back_filename = $_FILES["driver_license_back"]["name"];
                                        $driver_license_back_destination = "../assets/img/license/" . $driver_license_back_filename;
                                        move_uploaded_file($driver_license_back_path, $driver_license_back_destination);

                                        $nbi_police_cbi_photo_path = $_FILES["nbi_police_cibi_photo"]["tmp_name"];
                                        $nbi_police_cbi_photo_filename = $_FILES["nbi_police_cibi_photo"]["name"];
                                        $nbi_police_cbi_photo_destination = "../assets/img/docx/" . $nbi_police_cbi_photo_filename;
                                        move_uploaded_file($nbi_police_cbi_photo_path, $nbi_police_cbi_photo_destination);

                                        $pwdID_path = $_FILES["pwdID"]["tmp_name"];
                                        $pwdID_filename = $_FILES["pwdID"]["name"];
                                        $pwdID_destination = "../assets/img/pwd/" . $pwdID_filename;
                                        move_uploaded_file($pwdID_path, $pwdID_destination);

                                        // Retrieve form data
                                        $profile_photo = $profile_photo_filename;
                                        $is_vaxxed = isset($_POST['covid_agreement']) ? 1 : 0;
                                        $vax_card = $vax_card_filename;
                                        $emergency_contact_name = $_POST['emergency_contact_name'];
                                        $relationship = $_POST['relationship'];
                                        $emergency_contact_number = $_POST['emergency_contact_number'];
                                        $emergency_contact_address = $_POST['emergency_contact_address'];
                                        $driver_license_front = $driver_license_front_filename;
                                        $driver_license_back = $driver_license_back_filename;
                                        $license_expiration = $_POST['expiration_date'];
                                        $is_above_60 = $_POST['age_above_sixty'] === '1' ? 1 : 0;
                                        $nbi_police_cbi = $_POST['nbi_police_cibi_document'];
                                        $nbi_police_cbi_photo = $nbi_police_cbi_photo_filename;
                                        $pwdID = $pwdID_filename;
                                        $years_experience = $_POST['years_experience'];
                                        $date_issued = $_POST['date_issued'];

                                        // Update the user_profile table
                                        $stmt = $db->prepare("UPDATE user_profile SET profile_photo = ?, is_vaxxed = ?, vax_card = ? WHERE user_id = ?");
                                        $stmt->bind_param("siis", $profile_photo, $is_vaxxed, $vax_card, $user_id);
                                        $stmt->execute();

                                        // Insert into the emergency table
                                        $stmt = $db->prepare("INSERT INTO emergency (user_id, name, relationship, phone, address) VALUES (?, ?, ?, ?, ?)");
                                        $stmt->bind_param("issss", $user_id, $emergency_contact_name, $relationship, $emergency_contact_number, $emergency_contact_address);
                                        $stmt->execute();

                                        // Update the driver_identification table
                                        if (isset($_POST['disabilityType'])) {
                                            if ($_POST['disabilityType'] == 'Others') {
                                                $otherDisability = isset($_POST['otherDisability']) ? $_POST['otherDisability'] : '';
                                            } else {
                                                $otherDisability = $_POST['disabilityType'];
                                            }
                                        } else {
                                            $otherDisability = '';
                                        }

                                        $stmt = $db->prepare("INSERT INTO driver_identification (user_id, pwd_docx, license_front, license_back, license_expiration, is_above_60, nbi_police_cbi, cbi_date_issued, years_experience, disability) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                                        $stmt->bind_param("issssissss", $user_id, $pwdID, $driver_license_front, $driver_license_back, $license_expiration, $is_above_60, $nbi_police_cbi_photo_filename, $date_issued, $years_experience, $otherDisability);
                                        $stmt->execute();

                                        // Close the statement
                                        $stmt->close();

                                        // Send verification email
                                        $mail = new PHPMailer(true);

                                        // Fetch the email from the user_profile table
                                        $stmt = $db->prepare("SELECT email FROM user_profile WHERE user_id = ?");
                                        $stmt->bind_param("i", $user_id);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $row = $result->fetch_assoc();
                                        $email = $row['email'];

                                        $stmt->close();
                                        $result->close();

                                        try {
                                            $mail->SMTPDebug = 0;
                                            $mail->isSMTP();
                                            $mail->Host = 'smtp.gmail.com';
                                            $mail->SMTPAuth = true;
                                            $mail->Username = 'carpoolapp01@gmail.com';
                                            $mail->Password = 'wzspvmmnnxhtbuxd';
                                            $mail->SMTPSecure = 'tls';
                                            $mail->Port = 587;

                                            $mail->setFrom('noreply@tarasabay.com', 'TaraSabay PH');
                                            $mail->addAddress($email);
                                            $mail->addCustomHeader('X-Priority', '1');
                                            $mail->addCustomHeader('Importance', 'High');

                                            $mail->isHTML(true);
                                            $mail->Subject = 'Driver Verification';
                                            $mail->Body = "
                                            <html>
                                            <head>
                                            <style>
                                                body {
                                                font-family: Arial, sans-serif;
                                                font-size: 16px;
                                                line-height: 1.6;
                                                color: #444;
                                                }
                                                h1 {
                                                font-size: 24px;
                                                font-weight: bold;
                                                color: #333;
                                                margin: 0 0 30px;
                                                text-align: center;
                                                }
                                                p {
                                                margin: 0 0 20px;
                                                }
                                                a {
                                                color: #0072C6;
                                                text-decoration: none;
                                                }
                                                a:hover {
                                                text-decoration: underline;
                                                }
                                                .container {
                                                max-width: 600px;
                                                margin: 0 auto;
                                                }
                                            </style>
                                            </head>
                                            <body>
                                            <div class=\"container\">
                                            <h1>Driver Registration Requirements Received!</h1>
                                            <p>Dear valued driver,</p>
                                            <p>We have received your requirements for becoming a driver on TaraSabay. We appreciate your interest in joining our platform.</p>
                                            <p>To be officially qualified as a driver, we kindly request you to register a car on the TaraSabay App. This will involve providing the necessary details and documentation related to your vehicle.</p>
                                            <p>Registering a car will allow you to offer rides and maximize your earning potential on our platform. It's an essential step towards becoming an active driver.</p>
                                            <p>Please reload or log in again to the TaraSabay Web App and navigate to the car registration section to complete the process. Follow the instructions provided to complete the process.</p>
                                            <p>If you encounter any difficulties or have any questions, feel free to reach out to our support team at support@tarasabay.com. We are here to assist you throughout the car registration process.</p>
                                            <p>We look forward to having you as a qualified driver on TaraSabay!</p>
                                            <p>Best regards,</p>
                                            <p>TaraSabay PH Team</p>
                                            </div>
                                            </body>
                                            
                                            </html>";
                                            $mail->send();

                                            echo '<div style="text-align: center; font-family: \'Poppins\', sans-serif; background-color: #FFFFFF; padding: 20px; border-radius: 10px; max-width: 600px; margin: 0 auto;">
                                            <img src=\"../assets/img/checked.png\" alt=\"Driver Registration\" style=\"margin-bottom: 20px; width: 100px\">
                                            <h5 style="color: #4CAF50; font-size: 24px; margin-bottom: 20px;">Driver registration requirements received!</h5>
                                        <p style="color: #333333; font-size: 16px; margin-bottom: 20px;">Thank you for submitting your requirements to become a driver on TaraSabay. We have successfully received your information.</p>
                                        <p style="color: #333333; font-size: 16px;">To be officially qualified as a driver, we kindly request you to register a car on the TaraSabay App. This will allow you to offer rides and maximize your earning potential on our platform.</p>
                                        <p style="color: #333333; font-size: 16px;">Please log in again to the TaraSabay Web App and navigate to the car registration section to complete the process. If you encounter any difficulties or have any questions, our support team at support@tarasabay.com is ready to assist you.</p>
                                        <p style="color: #333333; font-size: 16px;">Alternatively, you may use or click the button below to redirect to the car registration page:</p>
                                        <p style="color: #333333; font-size: 16px;">We look forward to having you as a qualified driver on TaraSabay!</p>
                                        <br>
                                        <a href="addCar.php" class="btn btn-primary">Register a Car</a>
                                    </div>';
                                        } catch (Exception $e) {
                                            echo '<div style="text-align: center;">
                                                <h5 style="color: red">Error sending verification email: </h5>' . $mail->ErrorInfo . '
                                            </div>';
                                        }
                                    }
                                    ?>
                                    <button type="submit" name="submit" class="btn btn-success">Register</button>
                                    <br>

                        </form>
                <?php
                                }
                            }
                ?>
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