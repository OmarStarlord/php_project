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

$result_students = null;

        if (isset($_GET['selectedGroup'])) {
    
    $selectedGroup = $db->real_escape_string($_GET['selectedGroup']);

    
    $sql_students = "SELECT s.id_student, s.nom_student, s.prenom_student, s.email, s.GroupId, s.AcademicYear, s.FiliereId,
                 COUNT(m.CourseId) AS enrolledCourses,
                 AVG(m.Mark) AS averageMark
                 FROM student s
                 LEFT JOIN marks m ON s.id_student = m.StudentId
                 WHERE s.AcademicYear = '$academicYearId' AND s.FiliereId = '$filiereId' AND s.GroupId = '$selectedGroup'
                 GROUP BY s.id_student
                 ORDER BY s.GroupId";

    $result_students = $db->query($sql_students);

   if ($result_students === false) {
    
    die("Query failed: " . $db->error);
} elseif ($result_students === null) {
    
    die("No records returned from the query.");
}
}
    } else {
        die("No records found for the provided email and password");
    }

    
    $db->close();
} else {
    
    header("location: ../loginProfessor.php");
    exit();
}

if (isset($_GET['logout'])) {
  // Unset all session variables
  $_SESSION = array();

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
          <a class="sidebar-brand brand-logo" href="index.html"><img src="assets/images/logo.svg" alt="logo" /></a>
          <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
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
                <i class="mdi mdi-chart-bar"></i>
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
            <div class="content-wrapper">
            <div class="row">
    <div class="col-md-6">
        <form action="" method="get">
            <div class="form-group">
                <label for="selectGroup">Select Group:</label>
                <select class="form-control" id="selectGroup" name="selectedGroup">
                    <?php
                    // Display fixed GroupId values from 1 to 7
                    for ($i = 1; $i <= 7; $i++) {
                        echo "<option value='$i'>$i</option>";
                    }
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Show Students</button>
        </form>
    </div>
</div>
    
                <!-- ... (your existing HTML content) ... -->

                <div class="row">
                    <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-muted font-weight-normal">Teacher Id</h6>
                                <p class="mb-0 font-weight-normal"><?php echo $teacherId; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="text-muted font-weight-normal">Teacher Name</h6>
                                <p class="mb-0 font-weight-normal"><?php echo $teacherFullName; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-sm-6 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <p class="mb-0 font-weight-normal">Email: <?php echo $teacherEmail; ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- Display the students in a table -->
                    <div class="col-md-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Students</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Student Name</th>
                            <th>Academic Year</th>
                            <th>Filiere</th>
                            <th>Group</th>
                            <th>Enrolled Courses</th>
                             <th>Average Mark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($row_student = $result_students->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row_student['id_student'] . "</td>";
                            echo "<td>" . $row_student['nom_student'] . ' ' . $row_student['prenom_student'] . "</td>";
                            echo "<td>" . $row_student['AcademicYear'] . "</td>";
                            echo "<td>" . $row_student['FiliereId'] . "</td>";
                            echo "<td>" . $row_student['GroupId'] . "</td>";
                            echo "<td>" . $row_student['enrolledCourses'] . "</td>";
                            echo "<td>" . number_format($row_student['averageMark'], 2) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
                    <!-- ... (your existing HTML content) ... -->
                

                <!-- ... (your existing HTML content) ... -->
            
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
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