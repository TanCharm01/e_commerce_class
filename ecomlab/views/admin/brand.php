<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

// Enforce admin privileges
if (function_exists('require_admin')) {
    require_admin();
}

require_once __DIR__ . '/../layout/header.php';

$controller = new ProductController();

// Check if edit mode is active via GET parameter ?edit_id=N
$edit_id = filter_input(INPUT_GET, 'edit_id', FILTER_VALIDATE_INT);
$editBrand = null;

if ($edit_id && $edit_id > 0) {
    $editBrand = $controller->getBrandById($edit_id);
}

// Fetch all brands for the table listing
$brands = $controller->getAllBrands();
?>

<main style="max-width: 900px; margin: 2rem auto; padding: 0 1rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    
    <!-- Display Flash Messages -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 1rem;">
            <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 1rem;">
            <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); margin-bottom: 2rem;">
        <h2 style="margin-top: 0;">
            <?= $editBrand ? 'Edit Brand' : 'Add New Brand'; ?>
        </h2>

        <!-- Form dynamically changes action based on whether edit_id is present -->
        <form action="<?= BASE_URL; ?>/actions/<?= $editBrand ? 'update_brand_action.php' : 'add_brand_action.php'; ?>" method="POST">
            <?php if ($editBrand): ?>
                <!-- Hidden input carrying the brand_id for update -->
                <input type="hidden" name="brand_id" value="<?= htmlspecialchars($editBrand['brand_id']); ?>">
            <?php endif; ?>

            <div style="display: flex; gap: 1rem; align-items: center;">
                <input 
                    type="text" 
                    name="brand_name" 
                    placeholder="Enter brand name" 
                    value="<?= htmlspecialchars($editBrand['brand_name'] ?? ''); ?>" 
                    required 
                    style="flex: 1; padding: 0.65rem 0.8rem; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;"
                >
                <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 0.65rem 1.4rem; border-radius: 6px; font-weight: 600; cursor: pointer;">
                    <?= $editBrand ? 'Update Brand' : 'Save Brand'; ?>
                </button>
                
                <?php if ($editBrand): ?>
                    <a href="<?= BASE_URL; ?>/views/admin/brand.php" style="color: #64748b; text-decoration: none; font-size: 0.95rem;">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Brands Table -->
    <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
        <h3 style="margin-top: 0;">Existing Brands</h3>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0; color: #475569;">
                    <th style="padding: 10px 8px;">ID</th>
                    <th style="padding: 10px 8px;">Brand Name</th>
                    <th style="padding: 10px 8px; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($brands)): ?>
                    <tr><td colspan="3" style="padding: 12px 8px; color: #94a3b8;">No brands created yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($brands as $b): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px 8px;"><?= htmlspecialchars($b['brand_id']); ?></td>
                            <td style="padding: 10px 8px; font-weight: 500;"><?= htmlspecialchars($b['brand_name']); ?></td>
                            <td style="padding: 10px 8px; text-align: right;">
                                <a href="<?= BASE_URL; ?>/views/admin/brand.php?edit_id=<?= urlencode($b['brand_id']); ?>" style="color: #2563eb; text-decoration: none; font-weight: 600;">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>