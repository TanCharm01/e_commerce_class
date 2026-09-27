<?php
// setup_db.php
$host = "127.0.0.1";
$port = 3307;
$user = "root";
$pass = "";
$dbname = "ecommerce_2026A_tanatswa_mhiribidi";

try {
    // Connect to MySQL server on port 3307 without specifying a database
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Create the database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    echo "<p style='color:green;'>1. Database `$dbname` created on port 3307!</p>";

    // 2. Select the database
    $pdo->exec("USE `$dbname`");

    // 3. Create the customer table
    $tableSql = "CREATE TABLE IF NOT EXISTS `customer` (
        `customer_id` INT AUTO_INCREMENT PRIMARY KEY,
        `customer_name` VARCHAR(100) NOT NULL,
        `customer_email` VARCHAR(100) NOT NULL UNIQUE,
        `customer_pass` VARCHAR(255) NOT NULL,
        `customer_country` VARCHAR(50) NOT NULL,
        `customer_city` VARCHAR(50) NOT NULL,
        `customer_contact` VARCHAR(30) NOT NULL,
        `customer_image` VARCHAR(255) DEFAULT NULL,
        `user_role` INT DEFAULT 2
    )";
    $pdo->exec($tableSql);
    echo "<p style='color:green;'>2. Table `customer` created successfully on port 3307!</p>";

} catch (PDOException $e) {
    die("<p style='color:red;'>Setup failed: " . $e->getMessage() . "</p>");
}