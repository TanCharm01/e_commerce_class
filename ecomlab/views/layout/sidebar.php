<?php
/** @var ProductController $controller */
if (!isset($controller)) {
    require_once __DIR__ . '/../../controllers/ProductController.php';
    $controller = new ProductController();
}

$sidebarCategories = $controller->getAllCategories();
$sidebarBrands     = $controller->getAllBrands();
?>

<aside style="width: 240px; flex-shrink: 0; display: flex; flex-direction: column; gap: 1.5rem;">
    <!-- Search Bar -->
    <div style="background: #fff; padding: 1.25rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
        <h3 style="margin-top: 0; font-size: 1.1rem; color: #1e293b; margin-bottom: 0.75rem;">Search Store</h3>
        <form action="<?= BASE_URL; ?>/views/search_results.php" method="GET" style="display: flex; gap: 0.5rem;">
            <input type="text" name="user_query" placeholder="Search..." required style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 4px; font-size: 0.9rem;">
            <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 0.5rem 0.75rem; border-radius: 4px; cursor: pointer;">Go</button>
        </form>
    </div>

    <!-- Categories List -->
    <div style="background: #fff; padding: 1.25rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
        <h3 style="margin-top: 0; font-size: 1.1rem; color: #1e293b; margin-bottom: 0.75rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">Categories</h3>
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem;">
            <li><a href="<?= BASE_URL; ?>/views/home.php" style="color: #475569; text-decoration: none; font-size: 0.95rem;">All Products</a></li>
            <?php foreach ($sidebarCategories as $c): ?>
                <li>
                    <a href="<?= BASE_URL; ?>/views/home.php?cat=<?= urlencode($c['cat_id']); ?>" style="color: #2563eb; text-decoration: none; font-size: 0.95rem;">
                        <?= htmlspecialchars($c['cat_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <!-- Brands List -->
    <div style="background: #fff; padding: 1.25rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
        <h3 style="margin-top: 0; font-size: 1.1rem; color: #1e293b; margin-bottom: 0.75rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.5rem;">Brands</h3>
        <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem;">
            <?php foreach ($sidebarBrands as $b): ?>
                <li>
                    <a href="<?= BASE_URL; ?>/views/home.php?brand=<?= urlencode($b['brand_id']); ?>" style="color: #2563eb; text-decoration: none; font-size: 0.95rem;">
                        <?= htmlspecialchars($b['brand_name']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</aside>