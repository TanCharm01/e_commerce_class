<?php
// Must have 2 sets of '../' because this file is inside views/layout/
require_once __DIR__ . '/../../core/core.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Commerce Store</title>
    <style>
        .navbar {
            background-color: #343a40;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #ffffff;
            font-family: Arial, sans-serif;
        }
        .navbar a {
            color: #ffffff;
            text-decoration: none;
            margin-left: 1rem;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .nav-links {
            display: flex;
            align-items: center;
        }
        .welcome-text {
            color: #ffc107;
            margin-right: 0.5rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

<header class="navbar">
    <div class="brand">
        <a href="<?= BASE_URL; ?>/index.php" style="margin-left: 0; font-size: 1.2rem; font-weight: bold;">TanShop</a>
    </div>
    <nav class="nav-links">
        <?php if (is_logged_in()): ?>
            <span class="welcome-text">Welcome, <?= htmlspecialchars($_SESSION['customer_name'] ?? 'User'); ?>!</span>
            <a href="<?= BASE_URL; ?>/views/account/my_account.php">My Account</a>
            <a href="<?= BASE_URL; ?>/actions/logout_action.php">Logout</a>
        <?php else: ?>
            <a href="<?= BASE_URL; ?>/views/register.php">Register</a>
            <a href="<?= BASE_URL; ?>/views/login.php">Login</a>
        <?php endif; ?>
    </nav>
</header>