CREATE TABLE employees (
    employee_id INT AUTO_INCREMENT PRIMARY KEY,
    employee_name VARCHAR(255) NOT NULL
);

CREATE TABLE group_members (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    employee_id INT NOT NULL,
    FOREIGN KEY (group_id) REFERENCES employee_groups(group_id),
    FOREIGN KEY (employee_id) REFERENCES employees(employee_id)
);
