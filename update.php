<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$database = "book";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form data is received
if (isset($_POST['group_id']) && isset($_POST['status'])) {
    $group_id = $conn->real_escape_string($_POST['group_id']);
    $status = $conn->real_escape_string($_POST['status']);

    // Update query
    $sql = "UPDATE `booking payment` SET Status='$status' WHERE group_id='$group_id'";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error updating record: " . $conn->error;
    }
}

$conn->close();

// Redirect back to the original page or to a success page
header("Location: emp.php"); // Replace with the actual page you want to redirect to
exit();
?>
