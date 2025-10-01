CREATE TABLE employee_tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_name VARCHAR(255) NOT NULL,
    service VARCHAR(255) NOT NULL,
    group_id INT NOT NULL,
    status VARCHAR(255) NOT NULL DEFAULT 'Pending'
);
