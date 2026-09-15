<?php
require "db.php";

$id = intval($_GET["id"] ?? 0);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"]);
    $price = floatval($_POST["price"]);
    $status = $_POST["status"];
    $post_id = intval($_POST["id"]);

    $stmt = $conn->prepare("UPDATE catalog_items SET name=?, price=?, status=? WHERE id=?");
    $stmt->bind_param("sdsi", $name, $price, $status, $post_id);
    $stmt->execute();
    $stmt->close();
    $conn->close();

    header("Location: index.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM catalog_items WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) { die("Item not found."); }
?>
<!DOCTYPE html>
<html>
<head><title>Edit Item</title></head>
<body>
    <h2>Edit Item</h2>
    <form method="POST" action="edit.php">
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

        <label>Item Name</label><br>
        <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br><br>

        <label>Price ($)</label><br>
        <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br><br>

        <label>Status</label><br>
        <select name="status">
            <option value="available" <?php echo $product['status']==='available'?'selected':''; ?>>Available</option>
            <option value="low_stock" <?php echo $product['status']==='low_stock'?'selected':''; ?>>Low Stock</option>
            <option value="out_of_stock" <?php echo $product['status']==='out_of_stock'?'selected':''; ?>>Out of Stock</option>
        </select><br><br>

        <button type="submit">Update Item</button>
    </form>
    <br><a href="index.php">Back</a>
</body>
</html>