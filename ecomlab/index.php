<?php
// Must be 1 slash: index.php is at root
require_once __DIR__ . '/core/core.php';

// Include layout header
include __DIR__ . '/views/layout/header.php';
?>

<main style="padding: 20px; font-family: Arial, sans-serif;">
    <?php if (isset($_SESSION['error'])): ?>
        <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; padding: 12px; margin-bottom: 20px; border-radius: 4px; max-width: 600px;">
            <strong>Notice:</strong> <?= htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <h1>This is ecom lab started</h1>

    <nav>
        <a href="views/register.php">Register Customer</a> |
        <a href="view/customers.php">View All Customers</a>
    </nav>
</main>

</body>
</html>