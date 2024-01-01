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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
            width: 300px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            background-color: #4caf50;
            color: #fff;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        h2 {
            text-align: center;
        }

        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form method="post" action="">
        <h2>Login</h2>
        Email: <input type="text" name="email"><br>
        Password: <input type="password" name="password"><br>
        <input type="submit" value="Login">
        <?php if (isset($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
    </form>
</body>
</html>
