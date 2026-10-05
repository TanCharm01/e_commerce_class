<?php
// Ensure session and core functions are available
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
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