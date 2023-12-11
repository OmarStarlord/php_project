<?php
$servername = "localhost";
$username = "Omar";
$password = "omar";
$dbname = "platformcoursera";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST["name"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT * FROM admins WHERE AdminID = ? AND Password = ?");
    
    if (!$stmt) {
        die("Error in SQL query: " . $conn->error);
    }

    $stmt->bind_param("is", $name, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        header("Location: admindashboard/admin_dashboard.php");
        exit();
    } else {
        echo "Invalid admin credentials.";
    }
    $stmt->close();
}

$conn->close();
?>
