<?php
$servername = "localhost";
$username = "your_username";
$password = "your_password";
$database = "your_database";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $employeeNames = explode(',', $_POST['employees']);
    $employeeNames = array_map('trim', $employeeNames);
    $employeeNames = array_filter($employeeNames); // Remove empty values

    if (count($employeeNames) > 0) {
        // Insert employees into the database
        $stmt = $conn->prepare("INSERT INTO user (username, role) VALUES (?, 'employee')");
        foreach ($employeeNames as $name) {
            $stmt->bind_param("s", $name);
            $stmt->execute();
        }
        $stmt->close();
    }

    // Fetch and group employees
    $sql = "SELECT * FROM user WHERE role = 'employee'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $employees = [];
        while ($row = $result->fetch_assoc()) {
            $employees[] = $row;
        }

        // Group employees into 3 groups
        $groups = [[], [], []];
        foreach ($employees as $index => $employee) {
            $groups[$index % 3][] = $employee;
        }

        // Display grouped employees
        $output = '<h1>Employees</h1>';
        foreach ($groups as $i => $group) {
            $output .= "<h2>Group " . ($i + 1) . "</h2><ul>";
            foreach ($group as $employee) {
                $output .= "<li>" . htmlspecialchars($employee['username']) . "</li>";
            }
            $output .= "</ul>";
        }

        echo $output;
    } else {
        echo "No employees found.";
    }
}

$conn->close();
?>
