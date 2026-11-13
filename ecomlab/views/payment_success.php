<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../core/paystack_cred.php';

$order_id = filter_input(INPUT_GET, 'order_id', FILTER_VALIDATE_INT);

if (!$order_id || $order_id <= 0) {
    header("Location: " . BASE_URL . "/views/home.php");
    exit();
}

/** @var CartController $cartController */
$cartController = new CartController();
$order = $cartController->getOrderById($order_id);

if (!$order) {
    header("Location: " . BASE_URL . "/views/home.php");
    exit();
}

if (!empty($order['customer_email'])) {
    $to      = $order['customer_email'];
    $subject = "Order Confirmation - Invoice #" . $order['invoice_no'];
    $message = "Thank you for your order!\n\nInvoice Number: #" . $order['invoice_no'] . "\nStatus: PAID\nDate: " . $order['order_date'];
    $headers = "From: no-reply@" . ($_SERVER['HTTP_HOST'] ?? 'localhost');
    @mail($to, $subject, $message, $headers);
}

require_once __DIR__ . '/layout/header.php';
?>

<main style="max-width: 800px; margin: 2.5rem auto; padding: 0 1rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <div style="background: #fff; padding: 2.25rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
        <div style="background: #dcfce7; color: #166534; padding: 1rem 1.25rem; border-radius: 6px; margin-bottom: 1.5rem; font-weight: 600;">
            Payment Successful! Your order has been confirmed.
        </div>

        <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 1rem;">
            <div>
                <h2 style="margin: 0 0 0.25rem 0; color: #1e293b;">Invoice #<?= htmlspecialchars($order['invoice_no']); ?></h2>
                <span style="color: #64748b; font-size: 0.9rem;">Order ID: <?= (int)$order['order_id']; ?> | Date: <?= htmlspecialchars($order['order_date']); ?></span>
            </div>
            <div style="text-align: right;">
                <span style="background: #059669; color: #fff; padding: 4px 10px; border-radius: 999px; font-size: 0.8rem; font-weight: 600; text-transform: uppercase;">
                    <?= htmlspecialchars($order['order_status']); ?>
                </span>
            </div>
        </div>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0; color: #475569; font-size: 0.9rem;">
                    <th style="padding: 10px 8px;">Item</th>
                    <th style="padding: 10px 8px; text-align: center;">Quantity</th>
                    <th style="padding: 10px 8px; text-align: right;">Unit Price</th>
                    <th style="padding: 10px 8px; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php $grandTotal = 0; ?>
                <?php foreach ($order['items'] as $item): ?>
                    <?php 
                        $lineTotal = (float)$item['product_price'] * (int)$item['qty']; 
                        $grandTotal += $lineTotal;
                    ?>
                    <tr style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 10px 8px; font-weight: 500;"><?= htmlspecialchars($item['product_title']); ?></td>
                        <td style="padding: 10px 8px; text-align: center;"><?= (int)$item['qty']; ?></td>
                        <td style="padding: 10px 8px; text-align: right;">GHS <?= number_format((float)$item['product_price'], 2); ?></td>
                        <td style="padding: 10px 8px; text-align: right; font-weight: 600;">GHS <?= number_format($lineTotal, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 2px solid #e2e8f0;">
            <a href="<?= BASE_URL; ?>/views/home.php" style="color: #2563eb; text-decoration: none; font-weight: 600;">&larr; Continue Shopping</a>
            <div style="font-size: 1.4rem; font-weight: 700; color: #1e293b;">
                Grand Total: GHS <?= number_format($grandTotal, 2); ?>
            </div>
        </div>
    </div>
</main>
</body>
</html>