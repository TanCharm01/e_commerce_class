<?php
// Ensure session and core functions are available
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Go up two levels: from views/layout/ -> views/ -> ecomlab/ -> core/ or controllers/
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/CartController.php';

$ip = get_ip();
$cartController = new CartController();
$cart_count = $cartController->getCartCount($ip);
$cart_total = $cartController->getCartTotal($ip);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TanShop</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #212529;
            color: #ffffff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            text-decoration: none;
        }
        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            list-style: none;
        }
        .navbar-nav a {
            color: #ffffff;
            text-decoration: none;
            font-size: 1rem;
            transition: color 0.2s ease;
        }
        .navbar-nav a:hover {
            color: #adb5bd;
        }
        .welcome-msg {
            color: #f59e0b;
            font-weight: 600;
        }
        .admin-link {
            color: #f59e0b !important;
            font-weight: 600;
        }
        /* Cart summary bar styling */
        .cart-summary-bar {
            background-color: #f8f9fa;
            border-bottom: 1px solid #e5e7eb;
            padding: 0.6rem 2rem;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 0.95rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            color: #374151;
        }
        .cart-summary-bar a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
            margin-left: 0.5rem;
        }
        .cart-summary-bar a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<header class="navbar">
    <a href="<?= BASE_URL; ?>/index.php" class="navbar-brand">TanShop</a>

    <nav>
        <ul class="navbar-nav">
            <?php if (function_exists('is_logged_in') && is_logged_in()): ?>
                <!-- Rendered ONCE for any logged-in user -->
                <li class="welcome-msg">
                    Welcome, <?= htmlspecialchars($_SESSION['customer_name'] ?? 'Customer'); ?>!
                </li>

                <!-- Admin-only link inserted inline -->
                <?php if (function_exists('is_admin') && is_admin()): ?>
                    <li>
                        <a href="<?= BASE_URL; ?>/views/admin/dashboard.php" class="admin-link">Admin Dashboard</a>
                    </li>
                <?php endif; ?>

                <li>
                    <a href="<?= BASE_URL; ?>/views/account/my_account.php">My Account</a>
                </li>
                <li>
                    <a href="<?= BASE_URL; ?>/actions/logout_action.php">Logout</a>
                </li>

            <?php else: ?>
                <!-- Rendered for guests -->
                <li>
                    <a href="<?= BASE_URL; ?>/views/register.php">Register</a>
                </li>
                <li>
                    <a href="<?= BASE_URL; ?>/views/login.php">Login</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<!-- Task 11 Cart Summary Bar -->
<div class="cart-summary-bar">
    <span>
        Shopping Cart — Total Items: <strong><?= $cart_count; ?></strong> | Total Price: <strong>$<?= number_format($cart_total, 2); ?></strong> | <a href="<?= BASE_URL; ?>/views/cart.php">Go to Cart</a>
    </span>
</div>