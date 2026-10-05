<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

// Enforce admin privileges
if (function_exists('require_admin')) {
    require_admin();
}

require_once __DIR__ . '/../layout/header.php';

$controller = new ProductController();

// Edit state for Brand
$edit_brand_id = filter_input(INPUT_GET, 'edit_brand_id', FILTER_VALIDATE_INT);
$editBrand = ($edit_brand_id && $edit_brand_id > 0) ? $controller->getBrandById($edit_brand_id) : null;

// Edit state for Category
$edit_cat_id = filter_input(INPUT_GET, 'edit_cat_id', FILTER_VALIDATE_INT);
$editCategory = ($edit_cat_id && $edit_cat_id > 0 && method_exists($controller, 'getCategoryById')) 
    ? $controller->getCategoryById($edit_cat_id) 
    : null;

// Fetch data
$brands = $controller->getAllBrands();
$categories = $controller->getAllCategories();
?>

<main style="max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    <h1 style="margin-bottom: 1.5rem; font-size: 1.8rem; color: #1e293b;">Store Management Dashboard</h1>

    <!-- Display Flash Messages -->
    <?php if (!empty($_SESSION['success'])): ?>
        <div style="background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 6px; margin-bottom: 1.5rem;">
            <?= htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($_SESSION['error'])): ?>
        <div style="background: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 6px; margin-bottom: 1.5rem;">
            <?= htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
        </div>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(480px, 1fr)); gap: 2rem; align-items: start;">

        <!-- ================= BRAND SECTION ================= -->
        <section style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Brand Form -->
            <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                <h2 style="margin-top: 0; font-size: 1.3rem; color: #0f172a;">
                    <?= $editBrand ? 'Edit Brand' : 'Add Brand'; ?>
                </h2>

                <form action="<?= BASE_URL; ?>/actions/<?= $editBrand ? 'update_brand_action.php' : 'add_brand_action.php'; ?>" method="POST">
                    <?php if ($editBrand): ?>
                        <input type="hidden" name="brand_id" value="<?= htmlspecialchars($editBrand['brand_id']); ?>">
                    <?php endif; ?>
                    <input type="hidden" name="redirect" value="dashboard">

                    <div style="display: flex; gap: 0.75rem;">
                        <input 
                            type="text" 
                            name="brand_name" 
                            placeholder="e.g. Apple, Samsung" 
                            value="<?= htmlspecialchars($editBrand['brand_name'] ?? ''); ?>" 
                            required 
                            style="flex: 1; padding: 0.65rem 0.8rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.95rem;"
                        >
                        <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 0.65rem 1.2rem; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            <?= $editBrand ? 'Update' : 'Save'; ?>
                        </button>
                        <?php if ($editBrand): ?>
                            <a href="<?= BASE_URL; ?>/views/admin/dashboard.php" style="padding: 0.65rem 0.8rem; color: #64748b; text-decoration: none;">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Brand Table -->
            <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                <h3 style="margin-top: 0; font-size: 1.1rem; color: #334155; margin-bottom: 1rem;">Brands List</h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0; color: #64748b; text-align: left;">
                            <th style="padding: 8px;">ID</th>
                            <th style="padding: 8px;">Name</th>
                            <th style="padding: 8px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($brands)): ?>
                            <tr><td colspan="3" style="padding: 12px 8px; color: #94a3b8;">No brands added yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($brands as $b): ?>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 9px 8px; color: #64748b;"><?= htmlspecialchars($b['brand_id']); ?></td>
                                    <td style="padding: 9px 8px; font-weight: 500;"><?= htmlspecialchars($b['brand_name']); ?></td>
                                    <td style="padding: 9px 8px; text-align: right;">
                                        <a href="<?= BASE_URL; ?>/views/admin/dashboard.php?edit_brand_id=<?= urlencode($b['brand_id']); ?>" style="color: #2563eb; text-decoration: none; font-weight: 600;">Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- ================= CATEGORY SECTION ================= -->
        <section style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Category Form -->
            <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                <h2 style="margin-top: 0; font-size: 1.3rem; color: #0f172a;">
                    <?= $editCategory ? 'Edit Category' : 'Add Category'; ?>
                </h2>

                <form action="<?= BASE_URL; ?>/actions/<?= $editCategory ? 'update_category_action.php' : 'add_category_action.php'; ?>" method="POST">
                    <?php if ($editCategory): ?>
                        <input type="hidden" name="cat_id" value="<?= htmlspecialchars($editCategory['cat_id']); ?>">
                    <?php endif; ?>
                    <input type="hidden" name="redirect" value="dashboard">

                    <div style="display: flex; gap: 0.75rem;">
                        <input 
                            type="text" 
                            name="cat_name" 
                            placeholder="e.g. Laptops, Accessories" 
                            value="<?= htmlspecialchars($editCategory['cat_name'] ?? ''); ?>" 
                            required 
                            style="flex: 1; padding: 0.65rem 0.8rem; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 0.95rem;"
                        >
                        <button type="submit" style="background: #059669; color: #fff; border: none; padding: 0.65rem 1.2rem; border-radius: 6px; font-weight: 600; cursor: pointer;">
                            <?= $editCategory ? 'Update' : 'Save'; ?>
                        </button>
                        <?php if ($editCategory): ?>
                            <a href="<?= BASE_URL; ?>/views/admin/dashboard.php" style="padding: 0.65rem 0.8rem; color: #64748b; text-decoration: none;">Cancel</a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Category Table -->
            <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.06);">
                <h3 style="margin-top: 0; font-size: 1.1rem; color: #334155; margin-bottom: 1rem;">Categories List</h3>
                <table style="width: 100%; border-collapse: collapse; font-size: 0.95rem;">
                    <thead>
                        <tr style="border-bottom: 2px solid #e2e8f0; color: #64748b; text-align: left;">
                            <th style="padding: 8px;">ID</th>
                            <th style="padding: 8px;">Name</th>
                            <th style="padding: 8px; text-align: right;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($categories)): ?>
                            <tr><td colspan="3" style="padding: 12px 8px; color: #94a3b8;">No categories added yet.</td></tr>
                        <?php else: ?>
                            <?php foreach ($categories as $cat): ?>
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 9px 8px; color: #64748b;"><?= htmlspecialchars($cat['cat_id']); ?></td>
                                    <td style="padding: 9px 8px; font-weight: 500;"><?= htmlspecialchars($cat['cat_name']); ?></td>
                                    <td style="padding: 9px 8px; text-align: right;">
                                        <a href="<?= BASE_URL; ?>/views/admin/dashboard.php?edit_cat_id=<?= urlencode($cat['cat_id']); ?>" style="color: #059669; text-decoration: none; font-weight: 600;">Edit</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

    </div>
</main>
</body>
</html>