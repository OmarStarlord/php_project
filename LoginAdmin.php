<?php
include("config.php");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $myemail = mysqli_real_escape_string($db, $_POST['name']);
    $mypassword = mysqli_real_escape_string($db, $_POST['password']);

    $sql = "SELECT * FROM admin WHERE username = '$myemail' AND password = '$mypassword'";
    $result = mysqli_query($db, $sql);

    if (!$result) {
        die("Query failed: " . mysqli_error($db));
    }

    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($row) {
        // Store email and password in session variables
        $_SESSION['login_email'] = $myemail;
        $_SESSION['login_password'] = $mypassword;

        header("location: admindashboard/index.php");
        exit(); // Make sure to exit after the header to prevent further execution
    } else {
        $error = "Your Username or Password is invalid";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>

    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #555;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <form method="post">
        <h2>Admin Login</h2>

        <label for="name">Admind Id:</label>
        <input type="text" name="name" required>

        <label for="password">Password:</label>
        <input type="password" name="password" required>

        <input type="submit" value="Login">
    </form>
</body>
</html>