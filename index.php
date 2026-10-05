<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Robust path resolution: checks both flat and nested folder structures
$base_dir = __DIR__;
if (!file_exists($base_dir . '/core/core.php') && file_exists($base_dir . '/ecomlab/core/core.php')) {
    $base_dir = __DIR__ . '/ecomlab';
}

// 1. Load core settings and session
require_once $base_dir . '/core/core.php';

// 2. Include the navbar layout header (TanShop navbar with Register & Login)
require_once $base_dir . '/views/layout/header.php';
?>

<div style="padding: 2rem 2.5rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    <h1 style="font-size: 2.2rem; font-weight: 700; margin-bottom: 1.5rem; color: #000;">
        This is ecom lab started
    </h1>

    <div style="font-size: 1.05rem;">
        <a href="<?= BASE_URL; ?>/views/register.php" style="color: #4a148c; text-decoration: underline;">Register Customer</a>
        <span style="color: #000; margin: 0 0.4rem;">|</span>
        <a href="<?= BASE_URL; ?>/views/customers.php" style="color: #4a148c; text-decoration: underline;">View All Customers</a>
        
        <?php if (function_exists('is_admin') && is_admin()): ?>
            <span style="color: #000; margin: 0 0.4rem;">|</span>
            <a href="<?= BASE_URL; ?>/views/admin/brand.php" style="color: #b45309; font-weight: 600; text-decoration: underline;">Manage Brands (Admin)</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>