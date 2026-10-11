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
$product_id     = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
$title          = trim($_POST['product_title'] ?? '');
$cat_id         = filter_input(INPUT_POST, 'product_cat', FILTER_VALIDATE_INT);
$brand_id       = filter_input(INPUT_POST, 'product_brand', FILTER_VALIDATE_INT);
$price          = filter_input(INPUT_POST, 'product_price', FILTER_VALIDATE_FLOAT);
$desc           = trim($_POST['product_desc'] ?? '');
$keywords       = trim($_POST['product_keywords'] ?? '');
$existing_image = trim($_POST['existing_image'] ?? '');

if (!$product_id || empty($title) || !$cat_id || !$brand_id || $price === false || $price < 0 || empty($desc)) {
    $_SESSION['error'] = "Please complete all required fields with valid values.";
    header("Location: " . BASE_URL . "/views/admin/product.php?edit_id=" . urlencode($product_id));
    exit();
}

$filename = $existing_image;

// 4. Handle Optional New Image Upload
if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['product_image'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime  = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);

    if (!in_array($mime, $allowed_types, true)) {
        $_SESSION['error'] = "Invalid file type. Allowed: JPG, PNG, GIF, WEBP.";
        header("Location: " . BASE_URL . "/views/admin/product.php?edit_id=" . urlencode($product_id));
        exit();
    }

    if ($file['size'] > (5 * 1024 * 1024)) {
        $_SESSION['error'] = "Image size cannot exceed 5MB.";
        header("Location: " . BASE_URL . "/views/admin/product.php?edit_id=" . urlencode($product_id));
        exit();
    }

    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $clean_name = preg_replace('/[^a-zA-Z0-9_\.-]/', '', basename($file['name']));
    $new_filename = uniqid('prod_', true) . '_' . $clean_name;
    $upload_dir = __DIR__ . '/../images/products/';

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0775, true);
    }

    if (move_uploaded_file($file['tmp_name'], $upload_dir . $new_filename)) {
        // Delete old image if a previous image exists and is different
        if (!empty($existing_image) && file_exists($upload_dir . $existing_image)) {
            unlink($upload_dir . $existing_image);
        }
        $filename = $new_filename;
    } else {
        $_SESSION['error'] = "Failed to upload new image. Retaining existing image.";
    }
}

// 5. Update Database Record
$controller = new ProductController();
$updated = $controller->updateProduct($product_id, $cat_id, $brand_id, $title, $price, $desc, $filename, $keywords);

if ($updated) {
    $_SESSION['success'] = "Product updated successfully!";
} else {
    $_SESSION['error'] = "No changes made or update failed.";
}

header("Location: " . BASE_URL . "/views/admin/product.php");
exit();