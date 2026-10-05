<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

// Enforce admin privileges
if (function_exists('require_admin')) {
    require_admin();
}

require_once __DIR__ . '/../layout/header.php';

$controller = new ProductController();

// Check for edit mode via GET parameter ?edit_id=N
$edit_id = filter_input(INPUT_GET, 'edit_id', FILTER_VALIDATE_INT);
$editCategory = null;

if ($edit_id && $edit_id > 0) {
    $editCategory = $controller->getCategoryById($edit_id);
}

$categories = $controller->getAllCategories();
?>

<main style="max-width: 900px; margin: 2rem auto; padding: 0 1rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    
    <!-- Flash Messages -->
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

    <!-- Form: switches action and button label dynamically -->
    <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); margin-bottom: 2rem;">
        <h2 style="margin-top: 0; color: #212529;">
            <?= $editCategory ? 'Edit Category' : 'Add New Category'; ?>
        </h2>

        <form action="<?= BASE_URL; ?>/actions/<?= $editCategory ? 'update_category_action.php' : 'add_category_action.php'; ?>" method="POST">
            <?php if ($editCategory): ?>
                <input type="hidden" name="cat_id" value="<?= htmlspecialchars($editCategory['cat_id']); ?>">
            <?php endif; ?>
            <input type="hidden" name="redirect" value="category">

            <div style="display: flex; gap: 1rem; align-items: center;">
                <input 
                    type="text" 
                    name="cat_name" 
                    placeholder="Enter category name" 
                    value="<?= htmlspecialchars($editCategory['cat_name'] ?? ''); ?>" 
                    required 
                    style="flex: 1; padding: 0.65rem 0.8rem; border: 1px solid #ccc; border-radius: 6px; font-size: 1rem;"
                >
                <button type="submit" style="background: #059669; color: #fff; border: none; padding: 0.65rem 1.4rem; border-radius: 6px; font-weight: 600; cursor: pointer;">
                    <?= $editCategory ? 'Update Category' : 'Save Category'; ?>
                </button>
                <?php if ($editCategory): ?>
                    <a href="<?= BASE_URL; ?>/views/admin/category.php" style="color: #64748b; text-decoration: none; font-size: 0.95rem;">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Existing Categories Listing -->
    <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
        <h3 style="margin-top: 0; color: #212529;">Existing Categories</h3>
        <table style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0; color: #475569;">
                    <th style="padding: 10px 8px; width: 80px;">ID</th>
                    <th style="padding: 10px 8px;">Category Name</th>
                    <th style="padding: 10px 8px; text-align: right; width: 100px;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                    <tr><td colspan="3" style="padding: 12px 8px; color: #94a3b8;">No categories created yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($categories as $cat): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 10px 8px;"><?= htmlspecialchars($cat['cat_id']); ?></td>
                            <td style="padding: 10px 8px; font-weight: 500;"><?= htmlspecialchars($cat['cat_name']); ?></td>
                            <td style="padding: 10px 8px; text-align: right;">
                                <a href="<?= BASE_URL; ?>/views/admin/category.php?edit_id=<?= urlencode($cat['cat_id']); ?>" style="color: #059669; text-decoration: none; font-weight: 600;">Edit</a>
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