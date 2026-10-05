<?php

// Include core settings (which starts session) and the controller
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CustomerController.php";

// Ensure the session is running in case core.php didn't start it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set header so the browser/fetch knows to treat this as JSON
header('Content-Type: application/json; charset=utf-8');

// Helper function to return JSON error responses
function sendJsonError($message) {
    echo json_encode([
        'success' => false,
        'message' => $message
    ]);
    exit();
}

// 1. Only run on POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonError("Invalid request method.");
}

// 2. Collect and sanitize all inputs using trim() and strip_tags()
$name    = trim(strip_tags($_POST['customer_name'] ?? ''));
$email   = trim(strip_tags($_POST['customer_email'] ?? ''));
$pass    = $_POST['customer_pass'] ?? ''; // Do not trim or strip tags; passwords can have special chars/spaces
$country = trim(strip_tags($_POST['customer_country'] ?? ''));
$city    = trim(strip_tags($_POST['customer_city'] ?? ''));
$contact = trim(strip_tags($_POST['customer_contact'] ?? ''));
$image   = null; // Image upload is optional at sign-up (NULL default)

// 3. Validation: Check for empty required fields
if (empty($name) || empty($email) || empty(trim($pass)) || empty($country) || empty($city) || empty($contact)) {
    sendJsonError("All required fields must be filled.");
}

// 4. Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    sendJsonError("Invalid email address format.");
}

// 5. Validate Password with standard policy regex
// - At least 8 characters
// - At least 1 lowercase letter (?=.*[a-z])
// - At least 1 uppercase letter (?=.*[A-Z])
// - At least 1 number (?=.*\d)
// - At least 1 special character (?=.*[@$!%*?&#^()_\-+=[\]{}|;:,.<>])
$passPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#^()_\-+=[\]{}|;:,.<>])[A-Za-z\d@$!%*?&#^()_\-+=[\]{}|;:,.<>]{8,}$/';

if (!preg_match($passPattern, $pass)) {
    sendJsonError("Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one digit, and one special character.");
}

// 6. Validate field lengths against DB schema
if (strlen($email) > 100) {
    sendJsonError("Email cannot exceed 100 characters.");
}
if (strlen($name) > 100) {
    sendJsonError("Full name cannot exceed 100 characters.");
}
if (strlen($country) > 50) {
    sendJsonError("Country cannot exceed 50 characters.");
}
if (strlen($city) > 50) {
    sendJsonError("City cannot exceed 50 characters.");
}
if (strlen($contact) > 30) {
    sendJsonError("Contact number cannot exceed 30 characters.");
}

// 7. Bundle cleaned data and pass to CustomerController
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

// 8. Handle registration result
if (!empty($result['success'])) {
    // Populate session with authenticated customer details
    if (isset($result['customer'])) {
        $_SESSION['customer_id']   = $result['customer']['customer_id'];
        $_SESSION['customer_name'] = $result['customer']['customer_name'] ?? $name;
        $_SESSION['user_role']     = $result['customer']['user_role'];
    }

    echo json_encode([
        'success' => true,
        'message' => "Registration successful! You can now log in or view your account."
    ]);
    exit();
} else {
    sendJsonError($result['error'] ?? "Registration failed. Please try again.");
}