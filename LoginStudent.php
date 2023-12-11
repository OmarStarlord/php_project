<?php
include("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // email and password sent from form
    $myemail = mysqli_real_escape_string($db, $_POST['email']);
    $mypassword = mysqli_real_escape_string($db, $_POST['password']);

    $sql = "SELECT * FROM student WHERE email = '$myemail' AND password = '$mypassword'";
    $result = mysqli_query($db, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($db));
    }

    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($row) {
        // Store email and password in session variables
        $_SESSION['login_email'] = $myemail;
        $_SESSION['login_password'] = $mypassword;

        header("location: studentdashboard/index.php");
        exit(); // Make sure to exit after the header to prevent further execution
    } else {
        $error = "Your Login Email or Password is invalid";
    }
}
?>

<html>
<body>
    <!-- Add the form tag and method attribute -->
    <form method="post" action="">
        Email: <input type="text" name="email"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
