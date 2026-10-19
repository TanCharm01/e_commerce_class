<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ip = get_ip();
    $cartController = new CartController();
    $action_type = $_POST['action_type'] ?? '';

    // Action 1: Remove checked items
    if ($action_type === 'remove') {
        if (!empty($_POST['remove_items']) && is_array($_POST['remove_items'])) {
            foreach ($_POST['remove_items'] as $product_id) {
                $cartController->removeFromCart((int)$product_id, $ip);
            }
            $_SESSION['cart_message'] = "Selected item(s) removed from cart.";
        } else {
            $_SESSION['cart_message'] = "Please select at least one item to remove.";
        }
    }

    // Action 2: Update quantities
    if ($action_type === 'update') {
        if (!empty($_POST['qty']) && is_array($_POST['qty'])) {
            foreach ($_POST['qty'] as $product_id => $qty) {
                $qty = (int)$qty;
                if ($qty > 0) {
                    $cartController->updateCartQuantity((int)$product_id, $qty, $ip);
                } else {
                    // If quantity set to 0 or less, remove the item
                    $cartController->removeFromCart((int)$product_id, $ip);
                }
            }
            $_SESSION['cart_message'] = "Cart quantities updated successfully.";
        }
    }

    header("Location: ../views/cart.php");
    exit();
} else {
    header("Location: ../views/cart.php");
    exit();
}