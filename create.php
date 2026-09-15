<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $price = floatval($_POST["price"]);
    $status = $_POST["status"];

    $stmt = $conn->prepare("INSERT INTO catalog_items (name, price, status) VALUES (?, ?, ?)");
    $stmt->bind_param("sds", $name, $price, $status);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Add Item</title></head>
<body>
    <h2>Add New Item</h2>
    <form method="POST" action="create.php">
        <label>Item Name</label><br>
        <input type="text" name="name" required><br><br>

        <label>Price ($)</label><br>
        <input type="number" step="0.01" name="price" required><br><br>

        <label>Status</label><br>
        <select name="status">
            <option value="available">Available</option>
            <option value="low_stock">Low Stock</option>
            <option value="out_of_stock">Out of Stock</option>
        </select><br><br>

        <button type="submit">Save Item</button>
    </form>
    <br><a href="index.php">Back</a>
</body>
</html>