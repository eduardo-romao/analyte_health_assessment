DROP DATABASE IF EXISTS `customer_db`;

CREATE DATABASE IF NOT EXISTS `customer_db` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `customer_db`;

CREATE TABLE `customers` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(50) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `addresses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_id` INT NOT NULL UNIQUE,
  `address` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE
);

INSERT INTO `customers` (`name`, `email`) VALUES
('John Doe', 'john.doe@example.com'),
('Jane Smith', 'jane.smith@example.com'),
('Robert Jones', 'robert.jones@example.com');

INSERT INTO `addresses` (`customer_id`, `address`) VALUES
(1, '123 Main St Anytown - CA'),
(2, '789 Oak Ln Sometown - NY'),
(3, '1000 Pine St Othertown - TX');
