<?php
include("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $myemail = mysqli_real_escape_string($db, $_POST['email']);
    $mypassword = mysqli_real_escape_string($db, $_POST['password']);

    $sql = "SELECT * FROM student WHERE email = '$myemail' AND password = '$mypassword'";
    $result = mysqli_query($db, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($db));
    }

    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($row) {
        
        $_SESSION['login_email'] = $myemail;
        $_SESSION['login_password'] = $mypassword;

        header("location: studentdashboard/index.php");
        exit();
    } else {
        $error = "Your Login Email or Password is invalid";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Login</title>
</head>
<body>
    <!-- Add the form tag and method attribute -->
    <form method="post" action="">
        Email: <input type="text" name="email"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>

