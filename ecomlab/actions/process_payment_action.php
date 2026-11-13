<?php
require_once __DIR__ . '/../core/core.php';
require_once __DIR__ . '/../controllers/CartController.php';
require_once __DIR__ . '/../core/paystack_cred.php';

// 1. Ensure user is logged in
if (!isset($_SESSION['customer_id'])) {
    $_SESSION['error'] = "Please log in to complete your payment.";
    header("Location: " . BASE_URL . "/views/login.php");
    exit();
}

// 2. Sanitize Paystack transaction reference
$reference = preg_replace('/[^a-zA-Z0-9_\-]/', '', $_GET['ref'] ?? '');

if (empty($reference)) {
    $_SESSION['payment_error'] = "Missing transaction reference.";
    header("Location: " . BASE_URL . "/views/payment_failed.php");
    exit();
}

// 3. Server-side Paystack API Verification
$secret_key = PAYSTACK_SECRET_KEY; // Use the defined constant
$url = 'https://api.paystack.co/transaction/verify/' . rawurlencode($reference);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $secret_key,
    'Cache-Control: no-cache'
]);

$response = curl_exec($ch);
$curl_err = curl_error($ch);
curl_close($ch);

if ($curl_err) {
    $_SESSION['payment_error'] = "Payment verification connection error: " . $curl_err;
    header("Location: " . BASE_URL . "/views/payment_failed.php");
    exit();
}

$result = json_decode($response, true);

// 4. Confirm Paystack API returned success
if (
    !isset($result['status']) ||
    $result['status'] !== true ||
    !isset($result['data']['status']) ||
    $result['data']['status'] !== 'success'
) {
    $gateway_msg = $result['message'] ?? 'Transaction could not be verified.';
    $_SESSION['payment_error'] = "Payment failed: " . $gateway_msg;
    header("Location: " . BASE_URL . "/views/payment_failed.php");
    exit();
}

// 5. Record order in DB
$customer_id = (int)$_SESSION['customer_id'];
$ip          = $_SERVER['REMOTE_ADDR'];
$amount_paid = ($result['data']['amount'] ?? 0) / 100;

$cartController = new CartController();
$order_id = $cartController->processPayment($customer_id, $ip, $reference, $amount_paid);

if ($order_id) {
    header("Location: " . BASE_URL . "/views/payment_success.php?order_id=" . urlencode($order_id));
    exit();
} else {
    $_SESSION['payment_error'] = "Payment verified, but order recording failed or cart was empty.";
    header("Location: " . BASE_URL . "/views/payment_failed.php");
    exit();
}