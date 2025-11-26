-- InventoryManagement-Project: database snapshot (schema + sample data)
-- Generated as a best-effort SQL file from migrations and seeders so frontend devs can import quickly.

-- NOTE: This file was created without running migrations; import into MySQL 8+ and adjust as needed.

CREATE DATABASE IF NOT EXISTS `inventory` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `inventory`;

-- ingredients
CREATE TABLE `ingredients` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(191) NOT NULL,
  `quantity_on_hand` DECIMAL(12,2) NOT NULL DEFAULT 0,
  `reorder_level` DECIMAL(12,2) DEFAULT NULL,
  `unit_price` DECIMAL(12,2) DEFAULT 0,
  `unit` VARCHAR(50) DEFAULT NULL,
  `category` VARCHAR(100) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL
);

-- products
CREATE TABLE `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(191) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `base_price` DECIMAL(12,2) DEFAULT 0,
  `category` VARCHAR(100) DEFAULT NULL,
  `image_url` VARCHAR(255) DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL
);

-- toppings
CREATE TABLE `toppings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(191) NOT NULL,
  `price` DECIMAL(12,2) DEFAULT 0,
  `quantity_on_hand` DECIMAL(12,2) DEFAULT 0,
  `reorder_level` DECIMAL(12,2) DEFAULT NULL,
  `unit` VARCHAR(50) DEFAULT NULL,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL
);

-- product_ingredients pivot
CREATE TABLE `product_ingredients` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `ingredient_id` INT NOT NULL,
  `quantity_required` DECIMAL(12,2) NOT NULL,
  `unit` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  INDEX (`product_id`),
  INDEX (`ingredient_id`)
);

-- product_toppings pivot
CREATE TABLE `product_toppings` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `product_id` INT NOT NULL,
  `topping_id` INT NOT NULL,
  `quantity_required` DECIMAL(12,2) DEFAULT 1,
  `unit` VARCHAR(50) DEFAULT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL
);

-- sales and sale_items
CREATE TABLE `sales` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `total_amount` DECIMAL(12,2) DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE `sale_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `sale_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT DEFAULT 1,
  `unit_price` DECIMAL(12,2) DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  INDEX (`sale_id`),
  INDEX (`product_id`)
);

-- weekly reports
CREATE TABLE `weekly_reports` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `week_start` DATE NOT NULL,
  `total_income` DECIMAL(12,2) DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL
);

CREATE TABLE `weekly_report_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `weekly_report_id` INT NOT NULL,
  `product_id` INT NOT NULL,
  `quantity` INT DEFAULT 0,
  `unit_price` DECIMAL(12,2) DEFAULT 0,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL
);

-- sample data (matching seeders)
INSERT INTO `ingredients` (`id`,`name`,`quantity_on_hand`,`reorder_level`,`unit_price`,`unit`,`category`,`created_at`) VALUES
(1,'Chicken Breast',15000.00,5000.00,80.00,'g','Protein',NOW()),
(2,'Rice',20000.00,8000.00,50.00,'g','Grain',NOW()),
(3,'Spicy Sauce',3.00,1.50,2500.00,'bottle','Sauce',NOW()),
(4,'Sweet Sauce',2.50,1.00,2500.00,'bottle','Sauce',NOW());

INSERT INTO `products` (`id`,`name`,`description`,`base_price`,`category`,`image_url`,`is_active`,`created_at`) VALUES
(1,'Chicken Bowl','Grilled chicken with rice',25000.00,'Bowl','/images/chicken-bowl.jpg',1,NOW()),
(2,'Rice Bowl','Rice with vegetables',23000.00,'Bowl','/images/rice-bowl.jpg',1,NOW());

INSERT INTO `product_ingredients` (`product_id`,`ingredient_id`,`quantity_required`,`unit`,`created_at`) VALUES
(1,1,200.00,'g',NOW()),
(1,2,300.00,'g',NOW()),
(2,2,400.00,'g',NOW());

INSERT INTO `toppings` (`id`,`name`,`price`,`quantity_on_hand`,`reorder_level`,`unit`,`is_active`,`created_at`) VALUES
(1,'Sausage',5000.00,20.00,5.00,'pcs',1,NOW()),
(2,'Boiled Egg',3000.00,30.00,10.00,'pcs',1,NOW());

-- a sample sale and items
INSERT INTO `sales` (`id`,`total_amount`,`created_at`) VALUES (1,500000.00,NOW());
INSERT INTO `sale_items` (`sale_id`,`product_id`,`quantity`,`unit_price`,`created_at`) VALUES
(1,1,10,25000.00,NOW()),
(1,2,5,23000.00,NOW());

-- sample weekly report
INSERT INTO `weekly_reports` (`id`,`week_start`,`total_income`,`created_at`) VALUES (1,'2025-11-17',3750000.00,NOW());
INSERT INTO `weekly_report_items` (`weekly_report_id`,`product_id`,`quantity`,`unit_price`,`created_at`) VALUES
(1,1,120,25000.00,NOW()),
(1,2,45,23000.00,NOW());

-- End of snapshot
