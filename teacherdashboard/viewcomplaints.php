<?php
session_start();

include_once "config.php"; 

if (isset($_SESSION['login_email']) && isset($_SESSION['login_password'])) {
    
    $email = $_SESSION['login_email'];
    $password = $_SESSION['login_password'];

    
    $sql_teacher = "SELECT id_prof, nom_prof, prenom_prof, email, academic_year_id, filiere_id
                    FROM professor
                    WHERE email = '$email' AND password = '$password'";

    $result_teacher = $db->query($sql_teacher);

    if (!$result_teacher) {
        die("Query failed: " . $db->error);
    }

    if ($result_teacher->num_rows > 0) {
        $row_teacher = $result_teacher->fetch_assoc();

        $teacherId = $row_teacher['id_prof'];
        $teacherFullName = $row_teacher['nom_prof'] . ' ' . $row_teacher['prenom_prof'];
        $teacherEmail = $row_teacher['email'];
        $academicYearId = $row_teacher['academic_year_id'];
        $filiereId = $row_teacher['filiere_id'];

        
        $sql_students = "SELECT id_student, nom_student, prenom_student, email, GroupId, AcademicYear, FiliereId
                 FROM student
                 WHERE AcademicYear = '$academicYearId' AND FiliereId = '$filiereId' AND GroupId = '$teacherId'
                 ORDER BY GroupId";


        $result_students = $db->query($sql_students);

        if (!$result_students) {
            die("Query failed: " . $db->error);
        }
    } else {
        die("No records found for the provided email and password");
    }

  $sql_complaints = "SELECT c.complaint_id, c.student_id, c.course_id, c.complaint_text, c.complaint_image, c.academic_year, c.filiere
                  FROM complaint c
                  JOIN student s ON c.student_id = s.id_student
                  WHERE s.AcademicYear = '$academicYearId' AND s.FiliereId = '$filiereId'
                  ORDER BY c.complaint_id DESC";  

$result_complaints = $db->query($sql_complaints);

if (!$result_complaints) {
    die("Query failed: " . $db->error);
}
    

    
} else {
    
    header("location: login.php");
    exit();
}

if (isset($_GET['logout'])) {
  // Unset all session variables
  $_SESSION = array();

  // close database connection
    $db->close();

  // Destroy the session
  session_destroy();

  // Redirect to the login page
  header("location: ../LoginProfessor.php");
  exit();
}



?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Professor Dashboard</title>
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
          <a class="sidebar-brand brand-logo" href="index.php"><img src="assets/images/logo.svg" alt="logo" /></a>
          <a class="sidebar-brand brand-logo-mini" href="index.php"><img src="assets/images/logo.svg" alt="logo" /></a>
        </div>
        <ul class="nav">
          <li class="nav-item profile">
            <div class="profile-desc">
              
                <div class="profile-name">
                  <h5 class="mb-0 font-weight-normal"><?php echo $teacherFullName; ?></h5>
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
            <a class="nav-link" href="viewcomplaints.php">
              <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
              </span>
              <span class="menu-title">Complaints</span>
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
                    <h5 class="mb-0 font-weight-normal"><?php echo $teacherFullName; ?></h5>
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
            <div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="font-weight-bold mb-0">Professor Dashboard</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Professor Information -->
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Professor Information</h4>
                        <p><strong>ID:</strong> <?php echo $teacherId; ?></p>
                <p><strong>Name:</strong> <?php echo $teacherFullName; ?></p>
                <p><strong>Email:</strong> <?php echo $teacherEmail; ?></p>
                <p><strong>Academic Year ID:</strong> <?php echo $academicYearId; ?></p>
                <p><strong>Filiere ID:</strong> <?php echo $filiereId; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Display Complaints Section -->
        <div class="row">
        <div class="col-md-12 grid-margin">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="font-weight-bold mb-0">Complaints</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Complaint ID</th>
                                    <th>Student ID</th>
                                    <th>Course ID</th>
                                    <th>Complaint Text</th>
                                    <th>Complaint Image</th>
                                    <th>Academic Year</th>
                                    <th>Filiere</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($row_complaint = $result_complaints->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>{$row_complaint['complaint_id']}</td>";
                                    echo "<td>{$row_complaint['student_id']}</td>";
                                    echo "<td>{$row_complaint['course_id']}</td>";
                                    echo "<td>{$row_complaint['complaint_text']}</td>";
                                    echo "<td><img src='display_image.php?complaint_id={$row_complaint['complaint_id']}' width='100'></td>";
                                    echo "<td>{$row_complaint['academic_year']}</td>";
                                    echo "<td>{$row_complaint['filiere']}</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
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