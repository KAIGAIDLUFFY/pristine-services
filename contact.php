<?php
// MySQL database credentials
$servername = "localhost";
$username ="root";
$password = "";
$database = "book";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>
<?php
session_start();
include('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $Name = $_POST['Name'];
    $Email = $_POST['Email'];
    $Message = $_POST['Message'];

    // Insert the user's reply into the database
    $insertQuery = "INSERT INTO contact (Name, Email, Message) VALUES ('$Name', '$Email', '$Message')";
    if ($conn->query($insertQuery) === TRUE) {
        // Successfully inserted the reply into the database
        // You can redirect the user back to the index.html page or display a success message
        header("Location: contact.php");
        exit;
    } else {
        // An error occurred while inserting the reply into the database
        // You can redirect the user back to the index.html page or display an error message
        header("Location: contact.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="styles.css">
    <header>
        <nav>
            <ul>
                <li><a href="index.html">Home</a></li>
                <li><a href="about.html">About Us</a></li>
                <li><a href="paybooking.php">Book Now</a></li>
                <li><a href="reviews.html">Reviews</a></li>
                <li><a href="gallery.html">Gallery</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li class="dropdown">
                    <a href="javascript:void(0)" class="dropbtn">Services</a>
                    <div class="dropdown-content">
                        <a href="residential.html">Residential Services</a>
                        <a href="commercial.html">Commercial Services</a>
                        <a href="Additional Services.html">Additional Services</a>
                        <li><a href="logout.php" class="btn">Logout</a>"></a></li>
                    </div>
                </li>
            </ul>
        </nav>
    </header>                
</head>
<body>
    <div class="container">
        <h1>Contact Us</h1>
        <form action="contact.php" method="post">
            <label for="Name">Name</label>
            <input type="text" id="Name" name="Name" required>

            <label for="Email">Email</label>
            <input type="text" id="Email" name="Email" required>

            <label for="Message">Message</label>
            <textarea id="Message" name="Message" rows="5" required></textarea>

            <input type="submit" value="Submit">
        </form>
    </div>
</body>
<footer>
        <div class="container">
            <div class="footer-content">
                <p>&copy; 2024 Pristine Cleaning. All rights reserved.</p>
                <nav>
                    <ul>
                        <li><a href="index.html">Home</a></li>
                        <li><a href="privacy policy.html">Privacy Policy</a></li>
                        <li><a href="terms of service.html">Terms of Service</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </footer>
    <script src="contact.js"></script>
</body>
</html>