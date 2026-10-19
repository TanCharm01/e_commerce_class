<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Allow POST only
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/cart.php");
    exit();
}

$ip = get_ip();
$cartController = new CartController();

// Check if remove checkboxes were submitted
if (!empty($_POST['remove'])) {
    $items_to_remove = is_array($_POST['remove']) ? $_POST['remove'] : [$_POST['remove']];

    foreach ($items_to_remove as $raw_id) {
        $product_id = filter_var($raw_id, FILTER_VALIDATE_INT);

        // Validate that product_id is a positive integer
        if ($product_id && $product_id > 0) {
            $cartController->removeFromCart($product_id, $ip);
        }
    }
    $_SESSION['cart_message'] = "Item(s) removed from cart.";
} else {
    $_SESSION['cart_message'] = "Please select at least one item to remove.";
}

// Redirect back to cart view
header("Location: ../views/cart.php");
exit();