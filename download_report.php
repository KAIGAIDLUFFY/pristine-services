<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

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

// Fetch data from the booking payment table
$sql = "SELECT Name, Email, Phone, Address, Service, Price, Date FROM `booking payment` ORDER BY Name DESC";
$booking_payment = $conn->query($sql);

// Check for query errors
if ($booking_payment === false) {
    die("SQL Error: " . $conn->error);
}

// Set the headers to indicate a file download
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=booking_report.csv');

// Create a file pointer connected to the output stream
$output = fopen('php://output', 'w');

// Output the column headings
fputcsv($output, array('Name', 'Email', 'Phone', 'Address', 'Service', 'Price', 'Date'));

// Output the rows
if ($booking_payment->num_rows > 0) {
    while ($row = $booking_payment->fetch_assoc()) {
        fputcsv($output, $row);
    }
}

// Close the connection
$conn->close();
?>
