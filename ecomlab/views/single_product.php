<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$pro_id = filter_input(INPUT_GET, 'pro_id', FILTER_VALIDATE_INT);

if (!$pro_id || $pro_id <= 0) {
    header("Location: " . BASE_URL . "/views/home.php");
    exit();
}

$controller = new ProductController();
$product = $controller->getProductById($pro_id);

if (!$product) {
    header("Location: " . BASE_URL . "/views/home.php");
    exit();
}

require_once __DIR__ . '/layout/header.php';
?>

<div style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem; display: flex; gap: 2rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <main style="flex: 1; background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06); display: grid; grid-template-columns: 1fr 1fr; gap: 2.5rem; align-items: start;">
        <div style="text-align: center; background: #f8fafc; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0;">
            <img src="<?= BASE_URL; ?>/images/products/<?= htmlspecialchars($product['product_image']); ?>" 
                 alt="<?= htmlspecialchars($product['product_title']); ?>" 
                 style="max-width: 100%; max-height: 400px; object-fit: contain;">
        </div>

        <div>
            <h1 style="margin-top: 0; color: #1e293b; font-size: 1.8rem;"><?= htmlspecialchars($product['product_title']); ?></h1>
            
            <div style="margin-bottom: 1rem; font-size: 0.95rem; color: #64748b;">
                <strong>Category:</strong> <?= htmlspecialchars($product['cat_name'] ?? 'Unassigned'); ?><br>
                <strong>Brand:</strong> <?= htmlspecialchars($product['brand_name'] ?? 'Unassigned'); ?>
            </div>

            <div style="font-size: 1.75rem; font-weight: 700; color: #059669; margin-bottom: 1.5rem;">
                $<?= number_format((float)$product['product_price'], 2); ?>
            </div>

            <div style="margin-bottom: 1.5rem; line-height: 1.6; color: #334155;">
                <h3 style="margin-bottom: 0.5rem; font-size: 1.1rem; color: #1e293b;">Description</h3>
                <p style="margin: 0; white-space: pre-line;"><?= htmlspecialchars($product['product_desc']); ?></p>
            </div>

            <?php if (!empty($product['product_keywords'])): ?>
                <div style="margin-bottom: 2rem; font-size: 0.85rem; color: #64748b;">
                    <strong>Tags:</strong> <?= htmlspecialchars($product['product_keywords']); ?>
                </div>
            <?php endif; ?>

            <a href="<?= BASE_URL; ?>/index.php?add_cart=<?= urlencode($product['product_id']); ?>" 
               style="display: inline-block; background: #2563eb; color: #fff; padding: 0.85rem 2rem; border-radius: 6px; text-decoration: none; font-weight: 600; font-size: 1.05rem;">
               Add to Cart
            </a>
        </div>
    </main>
</div>
</body>
</html>