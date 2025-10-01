<?php
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service = $_POST['service'];
    $group = $_POST['group'];
    
    // Insert task into database
    $sql = "INSERT INTO tasks (service, group_id) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $service, $group);
    
    if ($stmt->execute()) {
        echo "Task assigned successfully.";
    } else {
        echo "Error: " . $stmt->error;
    }
    
    $stmt->close();
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pristine Cleaning Services - Task Assignment</title>
</head>
<body>
    <h1>Assign Tasks to Groups</h1>
    <form action="assign_task.php" method="post">
        <label for="service">Service:</label><br>
        <input type="text" id="service" name="service" required><br><br>
        
        <label for="group">Group:</label><br>
        <select id="group" name="group" required>
            <option value="1">Group 1</option>
            <option value="2">Group 2</option>
            <option value="3">Group 3</option>
            <option value="4">Group 4</option>
        </select><br><br>
        
        <input type="submit" value="Assign Task">
    </form>
    
    <h2>Assigned Tasks</h2>
    <div id="tasks"></div>
    
    <script>
        // Fetch and display tasks
        async function fetchTasks() {
            const response = await fetch('fetch_tasks.php');
            const tasks = await response.json();
            const tasksDiv = document.getElementById('tasks');
            tasksDiv.innerHTML = '';
            tasks.forEach(task => {
                const taskElement = document.createElement('div');
                taskElement.innerText = `Service: ${task.service}, Group: ${task.group}`;
                tasksDiv.appendChild(taskElement);
            });
        }
        
        // Load tasks on page load
        window.onload = fetchTasks;
    </script>
</body>
</html>
