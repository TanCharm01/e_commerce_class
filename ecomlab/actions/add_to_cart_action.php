<?php
// Include core settings (which starts session and provides get_ip())
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';

// Check if ?add_cart=N is present in the GET request
if (isset($_GET['add_cart'])) {
    $product_id = (int)$_GET['add_cart'];
    $ip = get_ip();
    $qty = 1; // Default quantity for single add-to-cart clicks

    $cartController = new CartController();

    // Check duplicate detection
    if ($cartController->isInCart($product_id, $ip)) {
        $_SESSION['cart_message'] = "This item is already in your cart. Go to cart to update quantity.";
        header("Location: ../views/cart.php");
        exit();
    }

    // Customer ID if logged in, null for guests
    $customer_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    // Add item to cart
    $cartController->addToCart($product_id, $qty, $ip, $customer_id);

    // Redirect back to referring page (or product list / cart if referer not set)
    $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '../views/cart.php';
    header("Location: " . $referer);
    exit();
} else {
    // If accessed without ?add_cart, redirect to index
    header("Location: ../index.php");
    exit();
}