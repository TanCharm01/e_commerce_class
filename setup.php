<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "db.php";

$sql = "CREATE TABLE IF NOT EXISTS catalog_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    status ENUM('available', 'low_stock', 'out_of_stock') DEFAULT 'available',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "<h2>Catalog Setup complete!</h2>";
    echo "<p><a href='index.php'>Go to Catalog</a></p>";
} else {
    echo "Error creating table: " . $conn->error;
}

$conn->close();
?>