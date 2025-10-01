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

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['group_name'])) {
        $group_name = $conn->real_escape_string($_POST['group_name']);
        $sql = "INSERT INTO employee_groups (group_name) VALUES ('$group_name')";
        if ($conn->query($sql) === TRUE) {
            echo "Group created successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    } elseif (isset($_POST['group_id']) && isset($_POST['employee_id'])) {
        $group_id = intval($_POST['group_id']);
        $employee_id = intval($_POST['employee_id']);
        $sql = "INSERT INTO group_members (group_id, employee_id) VALUES ($group_id, $employee_id)";
        if ($conn->query($sql) === TRUE) {
            echo "Member added successfully.";
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

$groups_result = $conn->query("SELECT * FROM employee_groups");
$employees_result = $conn->query("SELECT * FROM employees");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employee Groups</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { max-width: 1100px; margin: auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        h2 { text-align: center; color: #333; }
        .btn { display: block; width: 100%; padding: 10px; background-color: #0056b3; color: white; border: none; border-radius: 5px; cursor: pointer; text-align: center; text-decoration: none; }
        .btn:hover { background-color: #004bb3; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table th { background-color: #f4f4f4; }
        table td { background-color: #fafafa; }
        .section { margin-top: 20px; }
        select { padding: 5px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Manage Employee Groups</h2>
        <a href="logout.php" class="btn">Logout</a>

        <div class="section">
            <h2>Create Group</h2>
            <form method="POST" action="">
                <label for="group_name">Group Name:</label>
                <input type="text" id="group_name" name="group_name" required>
                <button type="submit" class="btn">Create Group</button>
            </form>
        </div>

        <div class="section">
            <h2>Add Member to Group</h2>
            <form method="POST" action="">
                <label for="group_id">Group:</label>
                <select id="group_id" name="group_id" required>
                    <?php if ($groups_result && $groups_result->num_rows > 0): ?>
                        <?php while($group = $groups_result->fetch_assoc()): ?>
                            <option value="<?= $group['group_id'] ?>"><?= htmlspecialchars($group['group_name']) ?></option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
                <br>
                <label for="employee_id">Employee:</label>
                <select id="employee_id" name="employee_id" required>
                    <?php if ($employees_result && $employees_result->num_rows > 0): ?>
                        <?php while($employee = $employees_result->fetch_assoc()): ?>
                            <option value="<?= $employee['employee_id'] ?>"><?= htmlspecialchars($employee['employee_name']) ?></option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
                <button type="submit" class="btn">Add Member</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
