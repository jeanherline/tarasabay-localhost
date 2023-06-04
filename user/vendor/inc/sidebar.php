<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<ul class="sidebar navbar-nav">
  <li class="nav-item active">
    <a class="nav-link" href="dashboard.php">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>
  </li>
  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-fw fa-users"></i>
      <span>Profile</span>
    </a>
    <div class="dropdown-menu" aria-labelledby="pagesDropdown">
      <h6 class="dropdown-header">Manage</h6>
      <a class="dropdown-item" href="viewProfile.php">View Profile</a>
      <a class="dropdown-item" href="changePass.php">Change Password</a>
      <!-- <a class="dropdown-item" href="admin-manage-user.php">Manage</a> -->
    </div>
  </li>

  <?php
  if ($_SESSION['identity_type'] == "Driver's License" && $_SESSION['role'] == "Passenger") {
  ?>
    <?php
    $user_id = $_SESSION['user_id'];
    $query = "SELECT * FROM driver_identification WHERE user_id = $user_id AND driver_stat = 'Pending'";
    $result = mysqli_query($db, $query);

    if (mysqli_num_rows($result) > 0) {
      $car = mysqli_fetch_assoc($result);
    ?>
      <li class="nav-item">
        <a class="nav-link" href="apply-as-driver.php">
          <i class="fas fa-fw fa-exchange-alt"></i>
          <span>Apply As A Driver</span></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="addCar.php">
          <i class="fas fa-fw fa-car"></i>
          <span>Register A Car</span></a>
      </li>
    <?php
    } else {
      // User does not have a car, show the option to apply as a driver
    ?>
      <li class="nav-item">
        <a class="nav-link" href="apply-as-driver.php">
          <i class="fas fa-fw fa-exchange-alt"></i>
          <span>Apply As A Driver</span></a>
      </li>
  <?php
    }
  }
  ?>

  <?php
  if ($_SESSION['role'] == "Driver") {
  ?>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-fw fa-id-card"></i>
        <span>Driver</span>
      </a>
      <div class="dropdown-menu" aria-labelledby="pagesDropdown">
        <h6 class="dropdown-header">Cars</h6>
        <a class="dropdown-item" href="addCar.php">Add Car</a>
        <a class="dropdown-item" href="registeredCars.php">Registered Cars</a>
        <a class="dropdown-item" href="pendingCars.php">Pending Cars</a>
        <a class="dropdown-item" href="declinedCars.php">Declined Cars</a>
      </div>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-fw fa-bus"></i>
        <span>Wallet</span>
      </a>
      <div class="dropdown-menu" aria-labelledby="pagesDropdown">
        <h6 class="dropdown-header">GCash</h6>
        <a class="dropdown-item" href="cash-in.php">Cash-In</a>

        <a class="dropdown-item" href="cash-out.php">Cash-Out</a>
      </div>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-fw fa-exchange-alt"></i> <!-- Change the icon class here -->
        <span>History</span>
      </a>
      <div class="dropdown-menu" aria-labelledby="pagesDropdown">
        <h6 class="dropdown-header">Wallet</h6>
        <a class="dropdown-item" href="cash-in-history.php">Cash-In</a>
        <a class="dropdown-item" href="cash-out-history.php">Cash-Out</a>
      </div>
    </li>
  <?php
  }
  ?>


  <?php
  if ($_SESSION['role'] == "Passenger") {
  ?>
    <li class="nav-item">
      <a class="nav-link" href="cash-in.php">
        <i class="fas fa-fw fa-bus"></i>
        <span>Cash-In</span></a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="cash-in-history.php">
        <i class="fas fa-fw fa-exchange-alt"></i>
        <span>History</span></a>
    </li>
  <?php
  }
  ?>

  <?php
  if ($_SESSION['role'] == "admin") {
  ?>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-fw fa-book"></i> <!-- Change the icon class here -->
        <span>Wallet</span>
      </a>
      <div class="dropdown-menu" aria-labelledby="pagesDropdown">
        <h6 class="dropdown-header">Users</h6>
        <a class="dropdown-item" href="cash-in-manage.php">Cash-In</a>
        <a class="dropdown-item" href="cash-out-manage.php">Cash-Out</a>
      </div>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="tickets.php">
        <i class="fa fa-ticket"></i>
        <span>Tickets</span></a>
    </li>
  <?php
  }
  ?>




  <!-- <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-fw fa-book"></i>
      <span>Bookings</span>
    </a>
    <div class="dropdown-menu" aria-labelledby="pagesDropdown">
      <h6 class="dropdown-header">Bookings:</h6>
      <a class="dropdown-item" href="admin-add-booking.php">Add</a>
      <a class="dropdown-item" href="admin-view-booking.php">View</a>
      <a class="dropdown-item" href="admin-manage-booking.php">Manage</a>
    </div>
  </li>

  <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="pagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fas fa-fw fa-comments"></i>
      <span>Feedbacks</span>
    </a>
    <div class="dropdown-menu" aria-labelledby="pagesDropdown">
      <a class="dropdown-item" href="admin-view-feedback.php">View</a>
      <a class="dropdown-item" href="admin-publish-feedback.php">Manage</a>
    </div>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="admin-pwd-resets.php">
      <i class="fas fa-fw fa-key"></i>
      <span>Password Resets</span></a>
  </li>

  <li class="nav-item">
    <a class="nav-link" href="admin-view-syslogs.php">
      <i class="fas fa-fw fa fa-history"></i>
      <span>System Logs</span></a>
  </li> -->
</ul>