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
$sql = "SELECT id, Name, Email, Phone, Address, Service, Price, Date, group_id, status FROM `booking payment` ORDER BY Name DESC";
$booking_payment = $conn->query($sql);

// Check for query errors
if ($booking_payment === false) {
    die("SQL Error: " . $conn->error);
}

// Fetch employee groups
$groups_sql = "SELECT id, group_name FROM `employee_groups`";
$groups = $conn->query($groups_sql);

// Check for query errors
if ($groups === false) {
    die("SQL Error: " . $conn->error);
}

// Handle form submission for assigning tasks
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    $service_id = $_POST['service_id'];
    $group_id = $_POST['group_id'] ?? null;
    $action = $_POST['action'];

    if ($action == 'assign' && $group_id !== null) {
        $assign_sql = "UPDATE `booking payment` SET group_id = ?, status = 'Assigned' WHERE id = ?";
        $stmt = $conn->prepare($assign_sql);
        $stmt->bind_param('ii', $group_id, $service_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'reassign' && $group_id !== null) {
        $reassign_sql = "UPDATE `booking payment` SET group_id = ?, status = 'Reassigned' WHERE id = ?";
        $stmt = $conn->prepare($reassign_sql);
        $stmt->bind_param('ii', $group_id, $service_id);
        $stmt->execute();
        $stmt->close();
    } elseif ($action == 'cancel') {
        $cancel_sql = "UPDATE `booking payment` SET status = 'Cancelled' WHERE id = ?";
        $stmt = $conn->prepare($cancel_sql);
        $stmt->bind_param('i', $service_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Pristine Cleaning Services</title>
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
            <h1>Admin Dashboard</h1>
            <a href="logout.php" class="btn">Logout</a>
        </div>

        <div class="section">
            <h2>Bookings</h2>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>Service</th>
                    <th>Price</th>
                    <th>Date</th>
                    <th>Group</th>
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
                            <td><?= htmlspecialchars($row['group_id']) ?></td>
                            <td><?= htmlspecialchars($row['status']) ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="service_id" value="<?= $row['id'] ?>">
                                    <select name="group_id">
                                        <?php if ($groups && $groups->num_rows > 0): ?>
                                            <?php while($group = $groups->fetch_assoc()): ?>
                                                <option value="<?= $group['id'] ?>"><?= htmlspecialchars($group['group_name']) ?></option>
                                            <?php endwhile; ?>
                                        <?php endif; ?>
                                    </select>
                                    <button type="submit" name="action" value="assign" class="btn">Assign</button>
                                    <button type="submit" name="action" value="reassign" class="btn">Reassign</button>
                                    <button type="submit" name="action" value="cancel" class="btn">Cancel</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">No bookings found</td>
                    </tr>
                <?php endif; ?>
            </table>
        </div>
        <div class="section print-section">
            <h2>Report Actions</h2>
            <form method="post" action="download_report.php" style="display: inline;">
                <button type="submit" class="btn">Download Report</button>
            </form>
            <button onclick="printReport()" class="btn">Print Report</button>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
