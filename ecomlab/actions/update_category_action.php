<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

// 1. Enforce Admin Access
if (function_exists('require_admin')) {
    require_admin();
}

// 2. Reject non-POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/views/admin/dashboard.php");
    exit();
}

// 3. Sanitise and validate inputs
$cat_id = filter_input(INPUT_POST, 'cat_id', FILTER_VALIDATE_INT);
$cat_name = trim($_POST['cat_name'] ?? '');

// Determine redirect destination (supports both unified dashboard and standalone category view)
$target_view = (isset($_POST['redirect']) && $_POST['redirect'] === 'category')
    ? "/views/admin/category.php"
    : "/views/admin/dashboard.php";

if (!$cat_id || $cat_id <= 0) {
    $_SESSION['error'] = "Invalid category ID provided.";
    header("Location: " . BASE_URL . $target_view);
    exit();
}

if (empty($cat_name)) {
    $_SESSION['error'] = "Category name cannot be empty.";
    header("Location: " . BASE_URL . $target_view . "?edit_cat_id=" . urlencode($cat_id));
    exit();
}

$controller = new ProductController();

// 4. Duplicate name check (exclude the current category being edited)
$allCategories = $controller->getAllCategories();
foreach ($allCategories as $category) {
    if ((int)$category['cat_id'] !== $cat_id && strcasecmp($category['cat_name'], $cat_name) === 0) {
        $_SESSION['error'] = "Another category named '" . htmlspecialchars($cat_name) . "' already exists.";
        header("Location: " . BASE_URL . $target_view . "?edit_cat_id=" . urlencode($cat_id));
        exit();
    }
}

// 5. Update category
$updated = $controller->updateCategory($cat_id, $cat_name);

if ($updated) {
    $_SESSION['success'] = "Category updated successfully to '" . htmlspecialchars($cat_name) . "'!";
} else {
    $_SESSION['error'] = "No changes were made or update failed.";
}

header("Location: " . BASE_URL . $target_view);
exit();