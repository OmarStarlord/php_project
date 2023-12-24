<?php
session_start();


include_once "config.php"; // Adjust the path as needed

if (isset($_SESSION['login_email']) && isset($_SESSION['login_password'])) {
    // Retrieve email and password from session variables
    $email = $_SESSION['login_email'];
    $password = $_SESSION['login_password'];

    // Fetch student information based on email and password
    $sql = $sql = "SELECT id_student, nom_student, prenom_student, AcademicYear, FiliereId
            FROM Student
            WHERE email = '$email' AND password = '$password'";

    $result = $db->query($sql);

    if (!$result) {
        die("Query failed: " . $db->error);
    }

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $studentId = $row['id_student'];
            $fullName = $row['nom_student'] . ' ' . $row['prenom_student'];
            $level = $row['AcademicYear'];
            $filiereId = $row['FiliereId'];
            $currentAcademicYear = $level;
                  $currentFiliereId = $filiereId;
            
        }
    } else {
        die("No records found for the provided email and password");
    }

} else {
    // Redirect to the login page if session variables are not set
    header("location: ../login.php");
    exit();
}


if (isset($_GET['logout'])) {
  // Unset all session variables
  $_SESSION = array();

  // Destroy the session
  session_destroy();

  // Redirect to the login page
  header("location: ../LoginStudent.php");
  exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['complaint_text'])) {
    // Retrieve form data
    $complaint_text = $_POST['complaint_text'];

    // Handle file upload
    $complaint_image = file_get_contents($_FILES['complaint_image']['tmp_name']);

    // Get the selected course ID
    $course_id = $_POST['course'];

    // Prepare and execute SQL query to add a complaint
    $stmt = $db->prepare("INSERT INTO complaint (student_id, course_id, complaint_text, complaint_image, academic_year, filiere) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $studentId, $course_id, $complaint_text, $complaint_image, $currentAcademicYear, $currentFiliereId);

    if ($stmt->execute()) {
        echo "Complaint added successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}



$db->close();
?>




$db->close();

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Student Dashboard</title>
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
                  <h5 class="mb-0 font-weight-normal"><?php echo $fullName; ?></h5>
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
            <a class="nav-link" href="updateprogress.php">
              <span class="menu-icon">
                <i class="mdi mdi-chart-bar"></i>
              </span>
              <span class="menu-title">Update progress</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="uploadcertificate.php">
              <span class="menu-icon">
                <i class="mdi mdi-speedometer"></i>
              </span>
              <span class="menu-title">Upload Certificate</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="enroll.php">
              <span class="menu-icon">
                <i class="mdi mdi-contacts"></i>
              </span>
              <span class="menu-title">Enroll in Course</span>
            </a>
          </li>
          <li class="nav-item menu-items">
            <a class="nav-link" href="complaint.php">
              <span class="menu-icon">
                <i class="mdi mdi-file-document-box"></i>
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
                    <h5 class="mb-0 font-weight-normal"><?php echo $fullName; ?></h5>
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
                  <a class="dropdown-item preview-item" href="?logout=true">
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
        <!-- main-panel starts -->
        <!-- Add Complaint Section -->
<div class="row">
    <div class="col-md-12 grid-margin">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-weight-bold mb-0">Add a Complaint</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="complaint_text">Complaint Text:</label>
                        <textarea class="form-control" name="complaint_text" rows="10" required></textarea>
                    </div>
                    <div class="form-group">
                        <!-- Hidden input field to capture the selected course ID -->
                        <input type="hidden" name="course_id" id="course_id" value="">
                        
                        <label for="course">Choose a Course:</label>
                        <select class="form-control" name="course" id="course" required>
                            <?php
                            try {
                                // Establish a PDO connection
                                $pdo = new PDO("mysql:host=localhost;dbname=platformcoursera", "omar", "omar");

                                // Set the PDO error mode to exception
                                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                                // Prepare and execute the query
                                $stmt = $pdo->prepare("SELECT id1_course, CourseName FROM courses WHERE AcademicYear = :academicYear AND FiliereId = :filiereId");
                                $stmt->bindParam(':academicYear', $currentAcademicYear, PDO::PARAM_STR);
                                $stmt->bindParam(':filiereId', $currentFiliereId, PDO::PARAM_STR);
                                $stmt->execute();

                                // Check if there are rows in the result set
                                if ($stmt->rowCount() > 0) {
                                    while ($courseRow = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                        echo "<option value='" . $courseRow['id1_course'] . "'>" . $courseRow['CourseName'] . "</option>";
                                    }
                                } else {
                                    echo "<option value='' disabled>No courses available</option>";
                                }
                            } catch (PDOException $e) {
                                echo "Error: " . $e->getMessage();
                            } finally {
                                // Close the PDO connection
                                $pdo = null;
                            }
                            ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="complaint_image">Complaint Image:</label>
                        <input type="file" class="form-control-file" name="complaint_image" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Complaint</button>
                </form>
            </div>
        </div>
    </div>
</div>


        <!-- main-panel ends -->

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