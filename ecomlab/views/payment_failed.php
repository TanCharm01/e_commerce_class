<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/layout/header.php';
require_once __DIR__ . '/../core/paystack_cred.php';

$errorMessage = $_SESSION['payment_error'] ?? "Your payment could not be completed or verified.";
unset($_SESSION['payment_error']);
?>

<main style="max-width: 600px; margin: 3rem auto; padding: 0 1rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div style="background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); text-align: center;">
        <div style="background: #fee2e2; color: #991b1b; padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; font-weight: 600;">
            Payment Unsuccessful
        </div>
        <p style="color: #475569; margin-bottom: 2rem;"><?= htmlspecialchars($errorMessage); ?></p>
        <a href="<?= BASE_URL; ?>/views/payment.php" style="background: #2563eb; color: #fff; padding: 0.75rem 1.5rem; border-radius: 6px; text-decoration: none; font-weight: 600;">
            Try Again
        </a>
    </div>
</main>
</body>
</html>