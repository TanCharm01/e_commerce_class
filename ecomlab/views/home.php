<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

$controller = new ProductController();

$cat_id   = filter_input(INPUT_GET, 'cat', FILTER_VALIDATE_INT);
$brand_id = filter_input(INPUT_GET, 'brand', FILTER_VALIDATE_INT);

if ($cat_id) {
    $products = $controller->getProductsByCategory($cat_id);
    $page_heading = "Category Products";
} elseif ($brand_id) {
    $products = $controller->getProductsByBrand($brand_id);
    $page_heading = "Brand Products";
} else {
    $products = $controller->getFeaturedProducts(6);
    $page_heading = "Featured Products";
}

require_once __DIR__ . '/layout/header.php';
?>

<div style="max-width: 1200px; margin: 2rem auto; padding: 0 1rem; display: flex; gap: 2rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    
    <?php require_once __DIR__ . '/layout/sidebar.php'; ?>

    <main style="flex: 1;">
        <h2 style="margin-top: 0; margin-bottom: 1.5rem; color: #1e293b;"><?= htmlspecialchars($page_heading); ?></h2>

        <?php if (empty($products)): ?>
            <div style="background: #fff; padding: 2rem; border-radius: 8px; text-align: center; color: #64748b; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                <p style="font-size: 1.1rem; margin: 0;">No products found in this selection.</p>
            </div>
        <?php else: ?>
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.5rem;">
                <?php foreach ($products as $p): ?>
                    <div style="background: #fff; border-radius: 8px; padding: 1.25rem; box-shadow: 0 2px 6px rgba(0,0,0,0.06); display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <div style="width: 100%; height: 180px; overflow: hidden; border-radius: 6px; margin-bottom: 1rem; background: #f8fafc; display: flex; align-items: center; justify-content: center;">
                                <img src="<?= BASE_URL; ?>/images/products/<?= htmlspecialchars($p['product_image']); ?>" 
                                     alt="<?= htmlspecialchars($p['product_title']); ?>" 
                                     style="max-width: 100%; max-height: 100%; object-fit: contain;">
                            </div>
                            <h3 style="font-size: 1.05rem; margin: 0 0 0.5rem 0; color: #1e293b;">
                                <?= htmlspecialchars($p['product_title']); ?>
                            </h3>
                            <div style="font-size: 0.85rem; color: #64748b; margin-bottom: 0.5rem;">
                                <?= htmlspecialchars($p['brand_name'] ?? ''); ?> | <?= htmlspecialchars($p['cat_name'] ?? ''); ?>
                            </div>
                            <div style="font-weight: 700; color: #059669; font-size: 1.15rem; margin-bottom: 1rem;">
                                $<?= number_format((float)$p['product_price'], 2); ?>
                            </div>
                        </div>

                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?= BASE_URL; ?>/views/single_product.php?pro_id=<?= urlencode($p['product_id']); ?>" 
                               style="flex: 1; text-align: center; background: #e2e8f0; color: #1e293b; padding: 0.5rem; border-radius: 4px; text-decoration: none; font-size: 0.9rem; font-weight: 600;">
                                Details
                            </a>
                            <a href="<?= BASE_URL; ?>/index.php?add_cart=<?= urlencode($p['product_id']); ?>" 
                               style="flex: 1; text-align: center; background: #2563eb; color: #fff; padding: 0.5rem; border-radius: 4px; text-decoration: none; font-size: 0.9rem; font-weight: 600;">
                                Add to Cart
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </main>
</div>
</body>
</html>