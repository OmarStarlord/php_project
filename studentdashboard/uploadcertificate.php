    <?php
    session_start();

    include_once "config.php";
    include_once "classes/scape.php";

    if (isset($_SESSION['login_email']) && isset($_SESSION['login_password'])) {

        $email = $_SESSION['login_email'];
        $password = $_SESSION['login_password'];

      $sql = "SELECT s.id_student, s.nom_student, s.prenom_student, s.AcademicYear, s.GroupId, s.FiliereId
            FROM Student s
            INNER JOIN Courses c ON s.FiliereId = c.FiliereId
            WHERE s.email = '$email' AND s.password = '$password'";



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

        header("location: login.php");
        exit();
    }

    if (isset($_GET['logout'])) {
      // Unset all session variables
      $_SESSION = array();

      // Destroy the session
      session_destroy();
      $db->close();
      // Redirect to the login page
      header("location: ../LoginStudent.php");
      exit();
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        
        $selectedCourseId = $_POST["course"];
        // from table courses i extract coursename by id
        $sql = "SELECT CourseName FROM courses WHERE id1_course = '$selectedCourseId'";
        $result_course = mysqli_query($db, $sql);


    if ($result_course) {
        
        $row = mysqli_fetch_assoc($result_course);

        
        if ($row) {
            
            $courseName = $row["CourseName"];
            echo "Course Name: " . $courseName;
        } else {
            echo "No course found for the selected ID.";
        }

      
        mysqli_free_result($result_course);
    } else {
        echo "Error executing the query: " . mysqli_error($db);
    }


        
        $url = $_POST["linkInput"];

        
        $webScraper = new WebScraper($url);

        
        $webScraper->scrape();

        
        $courseName = $webScraper->getCourseName();
        $StudentName = $webScraper->getStudentName();
        $formattedDate = $webScraper->getFormattedDate();

      

        
        echo "Course Name: $courseName<br>";
        echo "Student Name: $StudentName<br>";
        echo "Formatted Date: $formattedDate<br>";

      $courseEndDateQuery = "SELECT end_date FROM courses WHERE id1_course = '$selectedCourseId'";
    $resultEndDate = mysqli_query($db, $courseEndDateQuery);

    if ($resultEndDate) {
        $rowEndDate = mysqli_fetch_assoc($resultEndDate);
        if ($rowEndDate) {
            $courseEndDate = $rowEndDate["end_date"];
        } else {
            echo "Error: No end date found for the selected course ID.";
            
        }
        
        mysqli_free_result($resultEndDate);
    } else {
        echo "Error executing the query to fetch the course end date: " . mysqli_error($db);
    }

    if (strtotime($formattedDate) <= strtotime($courseEndDate)) {
        
        $mark = 20;
    } else {
        
        $mark = max(min(10, -1 + (strtotime($formattedDate) - strtotime($courseEndDate)) / (60*60*24*365)), 10);
    }


    $courseId = $selectedCourseId;


    $updateSql = "UPDATE Marks SET Mark = '$mark' WHERE StudentId = '$studentId' AND CourseId = '$courseId'";
      if (mysqli_query($db, $updateSql)) {
          echo "Mark updated successfully.";
      } else {
          echo "Error updating mark: " . mysqli_error($db);
      }




    }
    ?>



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
              <a class="sidebar-brand brand-logo" href="index.html"><img src="assets/images/logo.svg" alt="logo" /></a>
              <a class="sidebar-brand brand-logo-mini" href="index.html"><img src="assets/images/logo-mini.svg" alt="logo" /></a>
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
                      <a class="dropdown-item preview-item">
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
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="row">
                <div class="col-md-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Update Progress</h4>

                            <!-- Form to update progress -->
                            <form method="post" >
                                <div class="form-group">
                                    <label for="courseSelect">Select Course:</label>
                                    <select class="form-control" name="course" id="course" required>
      <?php
      try {
        
        $pdo = new PDO("mysql:host=localhost;dbname=platformcoursera", "omar", "omar");

        
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        
        $stmt = $pdo->prepare("SELECT id1_course, CourseName FROM courses WHERE AcademicYear = :academicYear AND FiliereId = :filiereId");
        $stmt->bindParam(':academicYear', $currentAcademicYear, PDO::PARAM_STR);
        $stmt->bindParam(':filiereId', $currentFiliereId, PDO::PARAM_STR);
        $stmt->execute();

        
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
        
        $pdo = null;
      }
      ?>
    </select>
                                </div>
                                <div class="form-group">
                                    <label for="linkInput">Link:</label>
                                    <input type="url" class="form-control" id="linkInput" name="linkInput" placeholder="Enter link" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                            <!-- End Form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- content-wrapper ends -->
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