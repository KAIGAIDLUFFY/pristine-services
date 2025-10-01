<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];  // Assuming username is stored in session

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

// Update booking status if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['service']) && isset($_POST['status'])) {
    $service = $conn->real_escape_string($_POST['service']);
    $status = $conn->real_escape_string($_POST['status']);
    
    // Assuming `username` is the column that links bookings to users
    $update_sql = "UPDATE `booking_payment` SET `Status` = '$status' WHERE `Service` = '$service' AND `username` = '$username'";
    $conn->query($update_sql);
}

// Fetch data for the logged-in user from the booking payment table
$sql = "SELECT Name, Email, Phone, Address, Service, Price, Date, Status FROM `booking payment` WHERE `username` = '$username' ORDER BY Name DESC";
$booking_payment = $conn->query($sql);

// Check for query errors
if ($booking_payment === false) {
    die("SQL Error: " . $conn->error);
}

// Fetch data from the contact table - Example, remove if not needed
$sql_contacts = "SELECT Name, Email, Message FROM `contact` WHERE `username` = '$username' ORDER BY Name DESC";
$contact_results = $conn->query($sql_contacts);

// Check for query errors
if ($contact_results === false) {
    die("SQL Error: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard - Pristine Cleaning Services</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; line-height: 1.6; background-color: #f9f9f9; }
        .container { max-width: 900px; margin: auto; overflow: hidden; padding: 0 20px; background: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 8px; }
        .header { text-align: center; padding: 20px 0; background: #f4f4f4; border-bottom: 1px solid #ddd; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; color: #333; }
        .section { margin: 20px 0; }
        .section h2 { margin-bottom: 10px; color: #0056b3; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .btn { display: inline-block; background-color: #0056b3; color: #fff; border: none; padding: 10px 20px; text-align: center; border-radius: 5px; font-size: 16px; cursor: pointer; margin: 20px 0; text-decoration: none; }
        .btn:hover { background-color: #004bb3; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background-color: #f4f4f4; }
        table td { background-color: #fafafa; }
        .print-section { display: flex; justify-content: space-between; align-items: center; }
    </style>
    <script>
        function printReport() {
            var printWindow = window.open('print_report.php', '_blank');
            printWindow.addEventListener('load', function() {
                printWindow.print();
            });
        }
    </script>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>User Dashboard</h1>
            <a href="logout.php" class="btn">Logout</a>
        </div>

        <div class="section">
            <h2>My Bookings</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Service</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                <?php if ($booking_payment && $booking_payment->num_rows > 0): ?>
                    <?php while($row = $booking_payment->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Name']) ?></td>
                            <td><?= htmlspecialchars($row['Email']) ?></td>
                            <td><?= htmlspecialchars($row['Phone']) ?></td>
                            <td><?= htmlspecialchars($row['Address']) ?></td>
                            <td><?= htmlspecialchars($row['Service']) ?></td>
                            <td><?= htmlspecialchars($row['Price']) ?></td>
                            <td><?= htmlspecialchars($row['Date']) ?></td>
                            <td><?= htmlspecialchars($row['Status']) ?></td>
                            <td>
                                <form method="post" class="status-form">
                                    <input type="hidden" name="service" value="<?= htmlspecialchars($row['Service']) ?>">
                                    <select name="status">
                                        <option value="Pending" <?= $row['Status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="Started" <?= $row['Status'] == 'Started' ? 'selected' : '' ?>>Started</option>
                                        <option value="Canceled" <?= $row['Status'] == 'Canceled' ? 'selected' : '' ?>>Canceled</option>
                                    </select>
                                    <button type="submit" class="btn">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9">No bookings found</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>

        <!-- Optionally, display messages if needed -->
        <!--
        <div class="section">
            <h2>Messages</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                </tr>
                <?php if ($contact_results && $contact_results->num_rows > 0): ?>
                    <?php while($row = $contact_results->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['Name']) ?></td>
                            <td><?= htmlspecialchars($row['Email']) ?></td>
                            <td><?= htmlspecialchars($row['Message']) ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No messages found</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
        -->

        <div class="section print-section">
            <h2>Report Actions</h2>
            <form method="post" action="download_report.php" style="display: inline;">
                <button type="submit" class="btn">Download Report</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
