-- Create database and user for InventoryManagement-Project
-- Adjust password and host as needed before running.

CREATE DATABASE IF NOT EXISTS `inventory` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Replace 'inventorypass' with a secure password if desired
CREATE USER IF NOT EXISTS 'inventoryuser'@'localhost' IDENTIFIED BY 'inventorypass';
GRANT ALL PRIVILEGES ON `inventory`.* TO 'inventoryuser'@'localhost';
FLUSH PRIVILEGES;
