<?php
// Include header (which initializes core, CartController, get_ip(), and session)
require_once __DIR__ . '/layout/header.php';

// Flash message display for feedback messages
if (isset($_SESSION['cart_message'])) {
    echo '<div style="background-color: #fef3c7; color: #92400e; padding: 1rem 2rem; border-bottom: 1px solid #fde68a;">' 
         . htmlspecialchars($_SESSION['cart_message']) 
         . '</div>';
    unset($_SESSION['cart_message']);
}

// Fetch cart items for the visitor's IP
$cart_items = $cartController->getCartItems($ip);
$subtotal = 0.0;
?>

<main style="max-width: 1100px; margin: 2rem auto; padding: 0 1rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <h2 style="margin-bottom: 1.5rem; font-size: 1.75rem; color: #1e293b;">Your Shopping Cart</h2>

    <?php if (empty($cart_items)): ?>
        <div style="background-color: #f9fafb; padding: 2rem; text-align: center; border-radius: 8px; border: 1px solid #e5e7eb;">
            <p style="color: #4b5563; font-size: 1.1rem; margin-bottom: 1rem;">Your cart is currently empty.</p>
            <a href="<?= BASE_URL; ?>/index.php" style="display: inline-block; background-color: #2563eb; color: #ffffff; padding: 0.6rem 1.2rem; border-radius: 4px; text-decoration: none;">Continue Shopping</a>
        </div>
    <?php else: ?>
        <table style="width: 100%; border-collapse: collapse; text-align: left; margin-bottom: 1.5rem;">
            <thead>
                <tr style="border-bottom: 2px solid #e5e7eb; color: #374151; font-size: 0.95rem;">
                    <th style="padding: 0.75rem;">Remove</th>
                    <th style="padding: 0.75rem;">Product</th>
                    <th style="padding: 0.75rem;">Quantity</th>
                    <th style="padding: 0.75rem;">Unit Price</th>
                    <th style="padding: 0.75rem;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart_items as $item): 
                    $item_total = (int)$item['qty'] * (float)$item['product_price'];
                    $subtotal += $item_total;
                ?>
                    <tr style="border-bottom: 1px solid #e5e7eb; vertical-align: middle;">
                        <!-- Remove Form Submission Column -->
                        <td style="padding: 0.75rem; text-align: center;">
                            <input type="checkbox" name="remove[]" value="<?= htmlspecialchars($item['p_id']); ?>" form="remove-cart-form">
                        </td>

                        <!-- Product Title and Image -->
                        <td style="padding: 0.75rem; display: flex; align-items: center; gap: 1rem;">
                            <?php if (!empty($item['product_image'])): ?>
                                <img src="<?= BASE_URL; ?>/images/products/<?= htmlspecialchars($item['product_image']); ?>" 
                                     alt="<?= htmlspecialchars($item['product_title']); ?>" 
                                     width="60" height="60" 
                                     style="object-fit: cover; border-radius: 4px; border: 1px solid #e5e7eb;">
                            <?php endif; ?>
                            <span style="font-weight: 500; color: #1f2937;"><?= htmlspecialchars($item['product_title']); ?></span>
                        </td>

                        <!-- Quantity Column Linked to Update Form -->
                        <td style="padding: 0.75rem;">
                            <!-- Hidden identifier input matched with quantity input -->
                            <input type="hidden" name="update[]" value="<?= htmlspecialchars($item['p_id']); ?>" form="update-cart-form">
                            <input type="number" name="qty[]" value="<?= (int)$item['qty']; ?>" min="1" form="update-cart-form" style="width: 65px; padding: 0.3rem; border: 1px solid #d1d5db; border-radius: 4px;">
                        </td>

                        <!-- Unit Price -->
                        <td style="padding: 0.75rem; color: #4b5563;">
                            $<?= number_format((float)$item['product_price'], 2); ?>
                        </td>

                        <!-- Line Item Total -->
                        <td style="padding: 0.75rem; font-weight: 600; color: #111827;">
                            $<?= number_format($item_total, 2); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr style="border-top: 2px solid #e5e7eb; font-size: 1.1rem;">
                    <td colspan="4" style="text-align: right; padding: 1rem 0.75rem; font-weight: 700; color: #111827;">Subtotal:</td>
                    <td style="padding: 1rem 0.75rem; font-weight: 700; color: #111827;">$<?= number_format($subtotal, 2); ?></td>
                </tr>
            </tfoot>
        </table>

        <!-- Dedicated Forms for MVC Separation -->
        <form id="remove-cart-form" method="POST" action="<?= BASE_URL; ?>/actions/remove_from_cart_action.php" style="display: none;"></form>
        <form id="update-cart-form" method="POST" action="<?= BASE_URL; ?>/actions/update_qty_action.php" style="display: none;"></form>

        <!-- Cart Action Controls -->
        <div style="display: flex; gap: 0.75rem; justify-content: flex-end; align-items: center; flex-wrap: wrap;">
            <button type="submit" form="remove-cart-form" style="background-color: #ef4444; color: #ffffff; border: none; padding: 0.6rem 1.2rem; border-radius: 4px; cursor: pointer;">Remove Selected</button>
            <button type="submit" form="update-cart-form" style="background-color: #4b5563; color: #ffffff; border: none; padding: 0.6rem 1.2rem; border-radius: 4px; cursor: pointer;">Update Quantities</button>
            <a href="<?= BASE_URL; ?>/views/home.php" style="display: inline-block; background-color: #e5e7eb; color: #1f2937; padding: 0.6rem 1.2rem; border-radius: 4px; text-decoration: none;">Continue Shopping</a>
            <a href="<?= BASE_URL; ?>/views/checkout.php" style="display: inline-block; background-color: #10b981; color: #ffffff; padding: 0.6rem 1.2rem; border-radius: 4px; text-decoration: none; font-weight: 600;">Checkout</a>
        </div>
    <?php endif; ?>
</main>

<?php 
if (file_exists(__DIR__ . '/layout/footer.php')) {
    require_once __DIR__ . '/layout/footer.php';
}
?>