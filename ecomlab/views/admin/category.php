<?php
require_once __DIR__ . '/../../core/core.php';
require_once __DIR__ . '/../../controllers/ProductController.php';

// Enforce admin privileges
if (function_exists('require_admin')) {
    require_admin();
}

require_once __DIR__ . '/../layout/header.php';

$controller = new ProductController();
$categories =$controller->getAllCategories();
?>

<main style="max-width: 900px; margin: 2rem auto; padding: 0 1rem; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto