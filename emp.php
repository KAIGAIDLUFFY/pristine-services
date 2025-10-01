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

// Fetch data from the booking payment table
$sql = "SELECT Address, Service, Price, Date, Status, group_id FROM `booking payment` ORDER BY Name DESC";
$booking_payment = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tasks Table - Pristine Cleaning Services</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; line-height: 1.6; background-color: #f9f9f9; }
        .container { max-width: 1000px; margin: auto; overflow: hidden; padding: 0 20px; background: white; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); border-radius: 8px; }
        .header { text-align: center; padding: 20px 0; background: #f4f4f4; border-bottom: 1px solid #ddd; border-radius: 8px 8px 0 0; }
        .header h1 { margin: 0; color: #333; }
        .section { margin: 20px 0; }
        .section h2 { margin-bottom: 10px; color: #0056b3; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background-color: #f4f4f4; }
        table td { background-color: #fafafa; }
        .btn { display: inline-block; background-color: #0056b3; color: #fff; border: none; padding: 10px 20px; text-align: center; border-radius: 5px; font-size: 16px; cursor: pointer; margin: 20px 0; text-decoration: none; }
        .btn:hover { background-color: #004bb3; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Tasks Table</h1>
        </div>

        <div class="section">
            <h2>Tasks</h2>
            <table>
                <tr>
                    <th>Group ID</th>
                    <th>Service</th>
                    <th>Address</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
                <?php if ($booking_payment && $booking_payment->num_rows > 0): ?>
                    <?php while($row = $booking_payment->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['group_id']) ?></td>
                            <td><?= htmlspecialchars($row['Service']) ?></td>
                            <td><?= htmlspecialchars($row['Address']) ?></td>
                            <td><?= htmlspecialchars($row['Price']) ?></td>
                            <td><?= htmlspecialchars($row['Date']) ?></td>
                            <td>
                                <form method="post" action="update.php">
                                    <input type="hidden" name="group_id" value="<?= htmlspecialchars($row['group_id']) ?>">
                                    <select name="status">
                                        <option value="Started" <?= $row['Status'] == 'Started' ? 'selected' : '' ?>>Started</option>
                                        <option value="Cancelled" <?= $row['Status'] == 'Cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                        <option value="Pending" <?= $row['Status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                    </select>
                                    <button type="submit" class="btn">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">No tasks found</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
    </div>
</body>
</html>
