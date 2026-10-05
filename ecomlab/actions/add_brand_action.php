<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

// 1. Enforce Admin Access
require_admin();

// 2. Reject non-POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/views/admin/brand.php");
    exit();
}

$brand_name = trim($_POST['brand_name'] ?? '');

// 3. Validation: Non-empty name
if (empty($brand_name)) {
    $_SESSION['error'] = "Brand name cannot be empty.";
    header("Location: " . BASE_URL . "/views/admin/brand.php");
    exit();
}

$controller = new ProductController();

// 4. Prevent duplicate brand names
$allBrands = $controller->getAllBrands();
foreach ($allBrands as $brand) {
    if (strcasecmp($brand['brand_name'], $brand_name) === 0) {
        $_SESSION['error'] = "A brand with the name '" . htmlspecialchars($brand_name) . "' already exists.";
        header("Location: " . BASE_URL . "/views/admin/brand.php");
        exit();
    }
}

// 5. Insert brand
$created = $controller->addBrand($brand_name);

if ($created) {
    $_SESSION['success'] = "Brand '" . htmlspecialchars($brand_name) . "' added successfully!";
} else {
    $_SESSION['error'] = "Failed to add brand. Please try again.";
}

header("Location: " . BASE_URL . "/views/admin/brand.php");
exit();