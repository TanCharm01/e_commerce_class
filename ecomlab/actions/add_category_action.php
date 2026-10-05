<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

// 1. Enforce Admin Access
if (function_exists('require_admin')) {
    require_admin();
}

// 2. Reject non-POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/views/admin/category.php");
    exit();
}

// 3. Sanitise input
$cat_name = trim($_POST['cat_name'] ?? '');

if (empty($cat_name)) {
    $_SESSION['error'] = "Category name cannot be empty.";
    header("Location: " . BASE_URL . "/views/admin/category.php");
    exit();
}

$controller = new ProductController();

// 4. Duplicate check
$allCategories = $controller->getAllCategories();
foreach ($allCategories as $category) {
    if (strcasecmp($category['cat_name'], $cat_name) === 0) {
        $_SESSION['error'] = "Category '" . htmlspecialchars($cat_name) . "' already exists.";
        header("Location: " . BASE_URL . "/views/admin/category.php");
        exit();
    }
}

// 5. Insert category
$created = $controller->addCategory($cat_name);

if ($created) {
    $_SESSION['success'] = "Category '" . htmlspecialchars($cat_name) . "' added successfully!";
} else {
    $_SESSION['error'] = "Failed to add category. Please try again.";
}

header("Location: " . BASE_URL . "/views/admin/dashboard.php");
exit();