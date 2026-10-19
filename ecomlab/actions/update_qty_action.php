<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Allow POST requests only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/cart.php");
    exit();
}

$ip = get_ip();
$cartController = new CartController();

// Check if update array (containing product IDs) and qty array are present
if (!empty($_POST['update']) && is_array($_POST['update']) && !empty($_POST['qty']) && is_array($_POST['qty'])) {
    foreach ($_POST['update'] as $index => $raw_product_id) {
        $product_id = filter_var($raw_product_id, FILTER_VALIDATE_INT);
        
        // Grab corresponding quantity from the qty array by index
        $raw_qty = $_POST['qty'][$index] ?? null;
        $qty = filter_var($raw_qty, FILTER_VALIDATE_INT);

        // Validate that quantity is a positive integer (>= 1)
        if ($product_id && $product_id > 0 && $qty && $qty >= 1) {
            $cartController->updateQty($product_id, $qty, $ip);
        }
    }
    $_SESSION['cart_message'] = "Cart quantities updated successfully.";
} else {
    $_SESSION['cart_message'] = "No items to update.";
}

// Redirect back to cart view
header("Location: ../views/cart.php");
exit();