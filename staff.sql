-- Create the employees table
CREATE TABLE employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_name VARCHAR(100) NOT NULL,
    employee_email VARCHAR(100) UNIQUE NOT NULL,
    employee_phone VARCHAR(15),
    employee_address VARCHAR(255)
);

-- Create the employee_groups table
CREATE TABLE employee_groups (
    group_id INT AUTO_INCREMENT PRIMARY KEY,
    group_name VARCHAR(100) NOT NULL
);

-- Create the group_members table
CREATE TABLE group_members (
    group_id INT,
    employee_id INT,
    PRIMARY KEY (group_id, employee_id),
    FOREIGN KEY (group_id) REFERENCES employee_groups(group_id) ON DELETE CASCADE,
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id) ON DELETE CASCADE
);
