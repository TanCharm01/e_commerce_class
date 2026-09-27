<?php

// Include core settings (which starts session) and the controller
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CustomerController.php";

// Ensure the session is running in case core.php didn't start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Only run on POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/register.php");
    exit();
}

// Helper function to redirect back with an error message
function redirectWithError($message) {
    $_SESSION['error'] = $message;
    header("Location: ../views/register.php");
    exit();
}

// 2. Collect and sanitize all inputs using trim() and strip_tags()
$name    = trim(strip_tags($_POST['customer_name'] ?? ''));
$email   = trim(strip_tags($_POST['customer_email'] ?? ''));
$pass    = trim($_POST['customer_pass'] ?? ''); // Passwords should keep special characters
$country = trim(strip_tags($_POST['customer_country'] ?? ''));
$city    = trim(strip_tags($_POST['customer_city'] ?? ''));
$contact = trim(strip_tags($_POST['customer_contact'] ?? ''));
$image   = null; // Image upload is optional at sign-up (NULL default)

// 3. Validation: Check for empty required fields
if (empty($name) || empty($email) || empty($pass) || empty($country) || empty($city) || empty($contact)) {
    redirectWithError("All required fields must be filled.");
}

// 4. Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectWithError("Invalid email address format.");
}

// 5. Validate field lengths against DB schema
if (strlen($email) > 100) {
    redirectWithError("Email cannot exceed 100 characters.");
}
if (strlen($name) > 100) {
    redirectWithError("Full name cannot exceed 100 characters.");
}
if (strlen($country) > 50) {
    redirectWithError("Country cannot exceed 50 characters.");
}
if (strlen($city) > 50) {
    redirectWithError("City cannot exceed 50 characters.");
}
if (strlen($contact) > 30) {
    redirectWithError("Contact number cannot exceed 30 characters.");
}

// 6. Bundle cleaned data and pass to CustomerController
$data = [
    'customer_name'    => $name,
    'customer_email'   => $email,
    'customer_pass'    => $pass,
    'customer_country' => $country,
    'customer_city'    => $city,
    'customer_contact' => $contact,
    'customer_image'   => $image,
    'user_role'        => 2
];

$controller = new CustomerController();
$result = $controller->register($data);

// 7. Handle registration result
if ($result['success']) {
    // Populate session with authenticated customer details
    $_SESSION['customer_id'] = $result['customer']['customer_id'];
    $_SESSION['user_role']   = $result['customer']['user_role'];

    // Redirect to the account dashboard
    header("Location: ../views/account/my_account.php");
    exit();
} else {
    // Store controller error in session and redirect back to register
    redirectWithError($result['error'] ?? "Registration failed. Please try again.");
}