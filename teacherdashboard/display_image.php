<?php

include_once "config.php";


$complaintId = $_GET['complaint_id'];


$sql_image = "SELECT complaint_image FROM complaint WHERE complaint_id = $complaintId";
$result_image = $db->query($sql_image);

if (!$result_image) {
    die("Query failed: " . $db->error);
}

if ($result_image->num_rows > 0) {
    $row_image = $result_image->fetch_assoc();

    
    header('Content-Type: image/jpeg');

   
    echo $row_image['complaint_image'];
} else {
    
    die("Image not found");
}


$db->close();
?>