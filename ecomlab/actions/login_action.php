<?php

// Include core bootstrap (session start and utilities) and the controller
require_once __DIR__ . "/../core/core.php";
require_once __DIR__ . "/../controllers/CustomerController.php";

// Ensure the session is running
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Only run on POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../views/login.php");
    exit();
}

// Helper to set error flash message and redirect back to login
function redirectLoginError($message) {
    $_SESSION['error'] = $message;
    header("Location: ../views/login.php");
    exit();
}

// 2. Sanitize and retrieve form inputs
$email = trim(strip_tags($_POST['customer_email'] ?? ''));
$pass  = trim($_POST['customer_pass'] ?? '');

// Check for empty inputs
if (empty($email) || empty($pass)) {
    redirectLoginError("Please enter both email and password.");
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirectLoginError("Please enter a valid email address.");
}

// 3. Call CustomerController -> login()
$controller = new CustomerController();
$result = $controller->login($email, $pass);

// 4. Handle result
if ($result['success']) {
    $user = $result['customer'];

    // Store required session variables
    $_SESSION['customer_id']    = $user['customer_id'];
    $_SESSION['customer_name']  = $user['customer_name'];
    $_SESSION['customer_email'] = $user['customer_email'];
    $_SESSION['user_role']      = $user['user_role'];

    // Clear any previous error
    unset($_SESSION['error']);

    // Redirect to index.php
    header("Location: ../index.php");
    exit();
} else {
    // On failure: store error in $_SESSION['error'] and redirect back to login
    redirectLoginError($result['error'] ?? "Invalid email or password.");
}