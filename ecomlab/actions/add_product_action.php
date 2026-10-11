<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/ProductController.php';

// 1. Enforce Admin Access
if (function_exists('require_admin')) {
    require_admin();
}

// 2. Reject non-POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: " . BASE_URL . "/views/admin/product.php");
    exit();
}

// 3. Collect & Sanitise Inputs
$title    = trim($_POST['product_title'] ?? '');
$cat_id   = filter_input(INPUT_POST, 'product_cat', FILTER_VALIDATE_INT);
$brand_id = filter_input(INPUT_POST, 'product_brand', FILTER_VALIDATE_INT);
$price    = filter_input(INPUT_POST, 'product_price', FILTER_VALIDATE_FLOAT);
$desc     = trim($_POST['product_desc'] ?? '');
$keywords = trim($_POST['product_keywords'] ?? '');

if (empty($title) || !$cat_id || !$brand_id || $price === false || $price < 0 || empty($desc)) {
    $_SESSION['error'] = "Please fill in all required fields with valid values.";
    header("Location: " . BASE_URL . "/views/admin/product.php");
    exit();
}

// 4. Handle Image Upload
if (!isset($_FILES['product_image']) || $_FILES['product_image']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['error'] = "Please upload a valid product image.";
    header("Location: " . BASE_URL . "/views/admin/product.php");
    exit();
}

$file = $_FILES['product_image'];
$allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

// Server-side MIME type verification
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime  = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mime, $allowed_types, true)) {
    $_SESSION['error'] = "Invalid file format. Allowed formats: JPG, PNG, GIF, WEBP.";
    header("Location: " . BASE_URL . "/views/admin/product.php");
    exit();
}

// Max 5MB file size limit
if ($file['size'] > (5 * 1024 * 1024)) {
    $_SESSION['error'] = "Image size exceeds maximum limit of 5MB.";
    header("Location: " . BASE_URL . "/views/admin/product.php");
    exit();
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$clean_name = preg_replace('/[^a-zA-Z0-9_\.-]/', '', basename($file['name']));
$filename  = uniqid('prod_', true) . '_' . $clean_name;
$upload_dir = __DIR__ . '/../images/products/';

if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0775, true);
}

$destination = $upload_dir . $filename;

if (!move_uploaded_file($file['tmp_name'], $destination)) {
    $_SESSION['error'] = "Failed to save uploaded image. Check directory permissions.";
    header("Location: " . BASE_URL . "/views/admin/product.php");
    exit();
}

// 5. Delegate to Controller
$controller = new ProductController();
$created = $controller->addProduct($cat_id, $brand_id, $title, $price, $desc, $filename, $keywords);

if ($created) {
    $_SESSION['success'] = "Product '" . htmlspecialchars($title) . "' added successfully!";
} else {
    // Delete uploaded image if database query fails
    if (file_exists($destination)) {
        unlink($destination);
    }
    $_SESSION['error'] = "Database insertion failed. Please try again.";
}

header("Location: " . BASE_URL . "/views/admin/product.php");
exit();