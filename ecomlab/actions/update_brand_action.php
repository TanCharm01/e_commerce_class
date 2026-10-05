<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

// Enforce admin privileges
if (function_exists('require_admin')) {
    require_admin();
}

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/views/admin/brand.php");
    exit();
}

$brand_id   = filter_input(INPUT_POST, 'brand_id', FILTER_VALIDATE_INT);
$brand_name = trim($_POST['brand_name'] ?? '');

// Validation: brand_id must be a positive integer, brand_name must not be empty
if (!$brand_id || $brand_id <= 0 || empty($brand_name)) {
    $_SESSION['error'] = "Invalid input. Please provide a valid brand name.";
    $redirectUrl = $brand_id ? "/views/admin/brand.php?edit_id=" . $brand_id : "/views/admin/brand.php";
    header("Location: " . BASE_URL . $redirectUrl);
    exit();
}

$controller = new ProductController();
$updated = $controller->updateBrand($brand_id, $brand_name);

if ($updated) {
    $_SESSION['success'] = "Brand updated successfully!";
} else {
    $_SESSION['error'] = "Failed to update brand. No changes were made or brand not found.";
}

header("Location: " . BASE_URL . "/views/admin/brand.php");
exit();