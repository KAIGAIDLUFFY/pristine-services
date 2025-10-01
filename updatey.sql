CREATE TABLE `update` (
    `group_id` INT AUTO_INCREMENT PRIMARY KEY,
    `Address` VARCHAR(255) NOT NULL,
    `Service` VARCHAR(255) NOT NULL,
    `Price` DECIMAL(10, 2) NOT NULL,
    `Date` DATE NOT NULL,
    `Status` ENUM('Started', 'Cancelled', 'Pending') NOT NULL
);
