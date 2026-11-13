<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../core/paystack_cred.php';

if (!isset($_SESSION['customer_id'])) {
    $_SESSION['error'] = "Please log in to proceed to payment.";
    header("Location: " . BASE_URL . "/views/login.php");
    exit();
}

/** @var CartController $cartController */
$cartController = new CartController();
$ip = $_SERVER['REMOTE_ADDR'];
$cartItems = $cartController->getCartItems($ip);

if (empty($cartItems)) {
    header("Location: " . BASE_URL . "/views/cart.php");
    exit();
}

$total = 0;
foreach ($cartItems as $item) {
    $total += ((float)$item['product_price'] * (int)$item['qty']);
}

$customer_email = $_SESSION['customer_email'] ?? 'customer@example.com';

require_once __DIR__ . '/layout/header.php';
?>

<main style="max-width: 800px; margin: 2.5rem auto; padding: 0 1rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div style="background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
        <h2 style="margin-top: 0; color: #1e293b; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.75rem;">Checkout Summary</h2>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 0.9rem;">
                    <th style="padding: 10px 8px;">Product</th>
                    <th style="padding: 10px 8px; text-align: center;">Qty</th>
                    <th style="padding: 10px 8px; text-align: right;">Unit Price</th>
                    <th style="padding: 10px 8px; text-align: right;">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cartItems as $item): ?>
                    <?php $subtotal = (float)$item['product_price'] * (int)$item['qty']; ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 8px; font-weight: 500;"><?= htmlspecialchars($item['product_title']); ?></td>
                        <td style="padding: 10px 8px; text-align: center;"><?= (int)$item['qty']; ?></td>
                        <td style="padding: 10px 8px; text-align: right;">GHS <?= number_format((float)$item['product_price'], 2); ?></td>
                        <td style="padding: 10px 8px; text-align: right; font-weight: 600;">GHS <?= number_format($subtotal, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 2px solid #e2e8f0;">
            <div>
                <span style="color: #64748b; font-size: 0.95rem;">Total Payable:</span>
                <div style="font-size: 1.6rem; font-weight: 700; color: #059669;">GHS <?= number_format($total, 2); ?></div>
            </div>

            <button type="button" onclick="payWithPaystack()" style="background: #0ba4db; color: #fff; border: none; padding: 0.85rem 2rem; border-radius: 6px; font-weight: 600; font-size: 1.05rem; cursor: pointer;">
                Pay with Paystack
            </button>
        </div>
    </div>
</main>

<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
function payWithPaystack() {
    var handler = PaystackPop.setup({
        key: '<?= htmlspecialchars(PAYSTACK_PUBLIC_KEY, ENT_QUOTES, 'UTF-8'); ?>',
        email: '<?= htmlspecialchars($customer_email, ENT_QUOTES, 'UTF-8'); ?>',
        amount: <?= (int)round($total * 100); ?>,
        currency: 'GHS',
        onSuccess: function(response) {
            window.location.href = '<?= BASE_URL; ?>/actions/process_payment_action.php?ref=' + encodeURIComponent(response.reference);
        },
        onCancel: function() {
            alert('Payment cancelled.');
        }
    });
    handler.openIframe();
}
</script>
</body>
</html>