<?php

session_start();

include_once "config.php";

if (isset($_SESSION['login_email']) && isset($_SESSION['login_password'])) {
  // Retrieve username and password from session variables
  $username = $_SESSION['login_email'];
  $password = $_SESSION['login_password'];

  // Fetch admin information based on username and password
  $sql = "SELECT username, password
          FROM admin
          WHERE username = ? AND password = ?";
  
  $stmt = $db->prepare($sql);

  if ($stmt) {
      $stmt->bind_param("ss", $username, $password);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              $adminId = $row['username'];
              $password = $row['password'];
          }
      } else {
          die("No records found for the provided username and password");
      }

      $stmt->close();
  } else {
      die("Query preparation failed: " . $db->error);
  }

  
} else {
  // Redirect to the login page if session variables are not set
  header("location: login.php");
  exit();
}
// ADD COURSE to database
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Retrieve course information from form submission
  $courseName = $_POST['courseName'];
  $endDate = $_POST['endDate'];
  $filiereId = $_POST['filiereId'];
  $academicYear = $_POST['academicYear'];


  // Insert course information into database
  $sql = "INSERT INTO courses (courseName, end_Date, filiereId, academicYear)
          VALUES (?, ?, ? , ?)";
  
  $stmt = $db->prepare($sql);

  if ($stmt) {
      $stmt->bind_param("ssii", $courseName, $endDate, $filiereId, $academicYear);
      $stmt->execute();
      $stmt->close();
      header("location: index.php");
      exit();
  } else {
      die("Query preparation failed: " . $db->error);
  }

  
  
}

if (isset($_GET['logout'])) {
// Unset all session variables
$_SESSION = array();
$db->close();
// Destroy the session
session_destroy();

// Redirect to the login page
header("location: ../LoginAdmin.php");
exit();
}


?>



<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Admin Dashboard</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="assets/vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="assets/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="assets/vendors/jvectormap/jquery-jvectormap.css">
  <link rel="stylesheet" href="assets/vendors/flag-icon-css/css/flag-icon.min.css">
  <link rel="stylesheet" href="assets/vendors/owl-carousel-2/owl.carousel.min.css">
  <link rel="stylesheet" href="assets/vendors/owl-carousel-2/owl.theme.default.min.css">
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <!-- endinject -->
  <!-- Layout styles -->
  <link rel="stylesheet" href="assets/css/style.css">
  <!-- End layout styles -->
  <link rel="shortcut icon" href="assets/images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_sidebar.html -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar" aria-label="Sidebar Navigation">
      <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="index.html"><img src="assets/images/logo.svg" alt="logo" /></a>
        <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
      </div>
      <ul class="nav">
        <li class="nav-item profile">
          <div class="profile-desc">
            
              <div class="profile-name">
                <h5 class="mb-0 font-weight-normal"><?php echo $adminId; ?></h5>
              </div>

            <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
            <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list" aria-labelledby="profile-dropdown">
              <a href="#" class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-settings text-primary"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
                </div>
              </a>
              <div class="dropdown-divider"></div>
              <a href="#" class="dropdown-item preview-item">
                <div class="preview-thumbnail">
                  <div class="preview-icon bg-dark rounded-circle">
                    <i class="mdi mdi-onepassword  text-info"></i>
                  </div>
                </div>
                <div class="preview-item-content">
                  <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
                </div>
              </a>
            </div>
          </div>
        </li>
        <li class="nav-item nav-category">
          <span class="nav-link">Navigation</span>
        </li>
        <li class="nav-item menu-items">
          <a class="nav-link" href="index.php">
            <span class="menu-icon">
              <i class="mdi mdi-speedometer"></i>
            </span>
            <span class="menu-title">Dashboard</span>
          </a>
        </li>
        <li class="nav-item menu-items">
          <a class="nav-link" href="addprofessor.php">
            <span class="menu-icon">
              <i class="mdi mdi-chart-bar"></i>
            </span>
            <span class="menu-title">Add Professor</span>
          </a>
        </li>
        <li class="nav-item menu-items">
          <a class="nav-link" href="addcourse.php">
            <span class="menu-icon">
              <i class="mdi mdi-speedometer"></i>
            </span>
            <span class="menu-title">Add new Course</span>
          </a>
        </li>
      </ul>
      
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_navbar.html -->
      <nav class="navbar p-0 fixed-top d-flex flex-row" aria-label="Main Navigation">
        <div class="navbar-brand-wrapper d-flex d-lg-none align-items-center justify-content-center">
          <a class="navbar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
        </div>
        <div class="navbar-menu-wrapper flex-grow d-flex align-items-stretch">
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-menu"></span>
          </button>
          <ul class="navbar-nav navbar-nav-right">
            </li>
            <li class="nav-item nav-settings d-none d-lg-block">
              <a class="nav-link" href="#">
                <i class="mdi mdi-view-grid"></i>
              </a>
            </li>
            </li>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link" id="profileDropdown" href="#" data-toggle="dropdown">
                <div class="navbar-profile">
                  <h5 class="mb-0 font-weight-normal"><?php echo $adminId; ?></h5>
                  <i class="mdi mdi-menu-down d-none d-sm-block"></i>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-right navbar-dropdown preview-list" aria-labelledby="profileDropdown">
                <h6 class="p-3 mb-0">Profile</h6>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item">
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-settings text-success"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject mb-1">Settings</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item preview-item" href="?logout=true">>
                  <div class="preview-thumbnail">
                    <div class="preview-icon bg-dark rounded-circle">
                      <i class="mdi mdi-logout text-danger"></i>
                    </div>
                  </div>
                  <div class="preview-item-content">
                    <p class="preview-subject mb-1">Log out</p>
                  </div>
                </a>
                <div class="dropdown-divider"></div>
                <p class="p-3 mb-0 text-center">Advanced settings</p>
              </div>
            </li>
          </ul>
          <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
            <span class="mdi mdi-format-line-spacing"></span>
          </button>
        </div>
      </nav>
      <!-- partial -->
      <div class="main-panel">
<div class="content-wrapper">

  <!-- Existing content -->

  <!-- New content: Form for entering a new student -->
  <div class="row">
  <div class="col-md-12 grid-margin stretch-card">
      <div class="card">
          <div class="card-body">
              <h4 class="card-title">Add New Course</h4>

              <!-- Course Form -->
              <form  method="post">

  <!-- Course Name -->
  <div class="form-group">
      <label for="courseName">Course Name:</label>
      <input type="text" class="form-control" id="courseName" name="courseName" required>
  </div>

  <!-- End Date -->
  <div class="form-group">
      <label for="endDate">End Date:</label>
      <input type="date" class="form-control" id="endDate" name="endDate" required>
  </div>

  <!-- Academic Year -->
  <div class="form-group">
      <label for="academicYear">Academic Year (1 to 5):</label>
      <input type="number" class="form-control" id="academicYear" name="academicYear" min="1" max="5" required>
  </div>
  <!-- FiliereId (Dropdown for selecting Filiere) -->
<div class="form-group">
<label for="filiereId">Filiere:</label>
<select class="form-control" id="filiereId" name="filiereId" required>
  <option value="1">Ingénierie Informatique et Réseaux</option>
  <option value="2">Ingénierie Financière et Audit</option>
  <option value="3">Génie Industriel</option>
  <option value="4">Génie Civil, Bâtiments et Travaux Publics (BTP)</option>
  <option value="5">Ingénierie Automatismes et Informatique Industrielle</option>
  <!-- Add more options as needed -->
</select>
</div>

  <!-- Submit button -->
  <button type="submit" class="btn btn-primary">Submit</button>

</form>

              <!-- End Course Form -->

          </div>
      </div>
  </div>
</div>


  <!-- End New content: Form for entering a new student -->

  <!-- Existing content -->

</div>

<!-- Existing content -->

<!-- partial:partials/_footer.html -->
<footer class="footer">
  <div class="d-sm-flex justify-content-center justify-content-sm-between">
    <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2020</span>
  </div>
</footer>
<!-- partial -->

</div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="assets/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="assets/vendors/chart.js/Chart.min.js"></script>
  <script src="assets/vendors/progressbar.js/progressbar.min.js"></script>
  <script src="assets/vendors/jvectormap/jquery-jvectormap.min.js"></script>
  <script src="assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
  <script src="assets/vendors/owl-carousel-2/owl.carousel.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="assets/js/off-canvas.js"></script>
  <script src="assets/js/hoverable-collapse.js"></script>
  <script src="assets/js/misc.js"></script>
  <script src="assets/js/settings.js"></script>
  <script src="assets/js/todolist.js"></script>
  <!-- endinject -->
  <!-- Custom js for this page -->
  <script src="assets/js/dashboard.js"></script>
  <!-- End custom js for this page -->
</body>
</html>