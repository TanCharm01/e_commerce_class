<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require "db.php";

$result = $conn->query("SELECT * FROM catalog_items ORDER BY id DESC");
if (!$result) {
    die("Query error: " . $conn->error);
}
?>
<!DOCTYPE html>
<html>
<head><title>Product Catalog</title></head>
<body>
    <h1>Product Catalog</h1>
    <a href="create.php">+ Add Item</a>
    <hr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div style="margin-bottom: 15px;">
            <strong><?php echo htmlspecialchars($row['name']); ?></strong> - 
            $<?php echo htmlspecialchars($row['price']); ?> 
            (<em><?php echo $row['status']; ?></em>)
            <a href="edit.php?id=<?php echo $row['id']; ?>">Edit</a> | 
            <a href="delete.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Delete item?');">Delete</a>
        </div>
    <?php endwhile; ?>
</body>
</html>
<?php $conn->close(); ?>