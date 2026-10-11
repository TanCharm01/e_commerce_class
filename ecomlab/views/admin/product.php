<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

// Enforce admin privileges
if (function_exists('require_admin')) {
    require_admin();
}

require_once __DIR__ . '/../layout/header.php';

$controller = new ProductController();

// Check for edit mode
$edit_id = filter_input(INPUT_GET, 'edit_id', FILTER_VALIDATE_INT);
$editProduct = ($edit_id && $edit_id > 0) ? $controller->getProductById($edit_id) : null;

// Populate dropdowns & product list
$categories = $controller->getAllCategories();
$brands     = $controller->getAllBrands();
$products   = $controller->getAllProducts();
?>

<main style="max-width: 1100px; margin: 2rem auto; padding: 0 1rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">

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

    <!-- Product Form Card -->
    <div style="background: #fff; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08); margin-bottom: 2.5rem;">
        <h2 style="margin-top: 0; color: #1e293b;">
            <?= $editProduct ? 'Edit Product' : 'Add New Product'; ?>
        </h2>

        <form id="productForm" 
              action="<?= BASE_URL; ?>/actions/<?= $editProduct ? 'update_product_action.php' : 'add_product_action.php'; ?>" 
              method="POST" 
              enctype="multipart/form-data" 
              onsubmit="return validateProductForm();">

            <?php if ($editProduct): ?>
                <input type="hidden" name="product_id" value="<?= htmlspecialchars($editProduct['product_id']); ?>">
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($editProduct['product_image']); ?>">
            <?php endif; ?>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 1.25rem;">
                <!-- Product Title -->
                <div style="grid-column: span 2;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.4rem;">Product Title *</label>
                    <input type="text" name="product_title" value="<?= htmlspecialchars($editProduct['product_title'] ?? ''); ?>" required style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                </div>

                <!-- Category Dropdown -->
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.4rem;">Category *</label>
                    <select name="product_cat" required style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                        <option value="">-- Select Category --</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat['cat_id']); ?>" <?= (isset($editProduct['product_cat']) && (int)$editProduct['product_cat'] === (int)$cat['cat_id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($cat['cat_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Brand Dropdown -->
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.4rem;">Brand *</label>
                    <select name="product_brand" required style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                        <option value="">-- Select Brand --</option>
                        <?php foreach ($brands as $brand): ?>
                            <option value="<?= htmlspecialchars($brand['brand_id']); ?>" <?= (isset($editProduct['product_brand']) && (int)$editProduct['product_brand'] === (int)$brand['brand_id']) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($brand['brand_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Price -->
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.4rem;">Price ($) *</label>
                    <input type="number" step="0.01" min="0" name="product_price" value="<?= htmlspecialchars($editProduct['product_price'] ?? ''); ?>" required style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                </div>

                <!-- Keywords -->
                <div>
                    <label style="display: block; font-weight: 600; margin-bottom: 0.4rem;">Keywords (comma separated)</label>
                    <input type="text" name="product_keywords" value="<?= htmlspecialchars($editProduct['product_keywords'] ?? ''); ?>" placeholder="e.g. apple, phone, 5g" style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;">
                </div>

                <!-- Description -->
                <div style="grid-column: span 2;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.4rem;">Description *</label>
                    <textarea name="product_desc" rows="4" required style="width: 100%; padding: 0.65rem; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box;"><?= htmlspecialchars($editProduct['product_desc'] ?? ''); ?></textarea>
                </div>

                <!-- Image File Input -->
                <div style="grid-column: span 2;">
                    <label style="display: block; font-weight: 600; margin-bottom: 0.4rem;">
                        Product Image <?= $editProduct ? '(leave empty to retain current)' : '*'; ?>
                    </label>
                    <input type="file" id="product_image" name="product_image" accept="image/jpeg,image/png,image/gif,image/webp" <?= $editProduct ? '' : 'required'; ?> style="width: 100%; padding: 0.5rem 0;">

                    <!-- Image Preview Area -->
                    <?php if ($editProduct && !empty($editProduct['product_image'])): ?>
                        <div style="margin-top: 0.75rem;">
                            <span style="display: block; font-size: 0.85rem; color: #64748b; margin-bottom: 0.25rem;">Current Image:</span>
                            <img src="<?= BASE_URL; ?>/images/products/<?= htmlspecialchars($editProduct['product_image']); ?>" 
                                 alt="Current Product Image" 
                                 style="max-width: 120px; max-height: 120px; border-radius: 6px; border: 1px solid #e2e8f0; object-fit: cover;">
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div style="display: flex; gap: 1rem; align-items: center;">
                <button type="submit" style="background: #2563eb; color: #fff; border: none; padding: 0.75rem 1.6rem; border-radius: 6px; font-weight: 600; cursor: pointer;">
                    <?= $editProduct ? 'Update Product' : 'Save Product'; ?>
                </button>
                <?php if ($editProduct): ?>
                    <a href="<?= BASE_URL; ?>/views/admin/product.php" style="color: #64748b; text-decoration: none;">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Products Table -->
    <div style="background: #fff; padding: 1.5rem; border-radius: 8px; box-shadow: 0 2px 6px rgba(0,0,0,0.08);">
        <h3 style="margin-top: 0; color: #1e293b;">Product Inventory</h3>
        <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
            <thead>
                <tr style="border-bottom: 2px solid #e2e8f0; color: #475569;">
                    <th style="padding: 10px 8px;">Image</th>
                    <th style="padding: 10px 8px;">Title</th>
                    <th style="padding: 10px 8px;">Category</th>
                    <th style="padding: 10px 8px;">Brand</th>
                    <th style="padding: 10px 8px;">Price</th>
                    <th style="padding: 10px 8px; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($products)): ?>
                    <tr><td colspan="6" style="padding: 12px 8px; color: #94a3b8;">No products added yet.</td></tr>
                <?php else: ?>
                    <?php foreach ($products as $p): ?>
                        <tr style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 8px;">
                                <img src="<?= BASE_URL; ?>/images/products/<?= htmlspecialchars($p['product_image']); ?>" 
                                     alt="<?= htmlspecialchars($p['product_title']); ?>" 
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px; border: 1px solid #e2e8f0;">
                            </td>
                            <td style="padding: 8px; font-weight: 500;"><?= htmlspecialchars($p['product_title']); ?></td>
                            <td style="padding: 8px; color: #64748b;"><?= htmlspecialchars($p['cat_name'] ?? 'N/A'); ?></td>
                            <td style="padding: 8px; color: #64748b;"><?= htmlspecialchars($p['brand_name'] ?? 'N/A'); ?></td>
                            <td style="padding: 8px; font-weight: 600;">$<?= number_format((float)$p['product_price'], 2); ?></td>
                            <td style="padding: 8px; text-align: right;">
                                <a href="<?= BASE_URL; ?>/views/admin/product.php?edit_id=<?= urlencode($p['product_id']); ?>" style="color: #2563eb; text-decoration: none; font-weight: 600;">Edit</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</main>

<!-- Client-side Validation Script -->
<script>
function validateProductForm() {
    const fileInput = document.getElementById('product_image');
    if (fileInput.files.length > 0) {
        const file = fileInput.files[0];
        const validTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        
        if (!validTypes.includes(file.type)) {
            alert('Please select a valid image file (JPG, PNG, GIF, or WEBP).');
            return false;
        }

        // 5MB Limit Check
        if (file.size > 5 * 1024 * 1024) {
            alert('Image size exceeds 5MB limit.');
            return false;
        }
    }
    return true;
}
</script>
</body>
</html>