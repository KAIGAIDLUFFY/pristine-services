<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Input</title>
</head>
<body>
    <h1>Employee Input Form</h1>
    <form id="employeeForm">
        <label for="employees">Enter employee names (comma-separated):</label><br>
        <input type="text" id="employees" name="employees" required><br><br>
        <input type="submit" value="Submit">
    </form>
    <div id="result"></div>

    <script>
        document.getElementById('employeeForm').addEventListener('submit', function(event) {
            event.preventDefault();
            const employees = document.getElementById('employees').value;

            fetch('process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: new URLSearchParams({ employees })
            })
            .then(response => response.text())
            .then(data => {
                document.getElementById('result').innerHTML = data;
            })
            .catch(error => console.error('Error:', error));
        });
    </script>
</body>
</html>
