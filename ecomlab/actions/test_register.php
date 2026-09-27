<?php
// Enable full PHP error reporting so errors are visible on screen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h2>Step 1: Checking Controller file path...</h2>";
require_once "../controller/CustomerController.php";
echo "<p style='color:green;'>Controller file loaded successfully!</p>";

echo "<h2>Step 2: Connecting and testing CustomerController...</h2>";
$controller = new CustomerController();
echo "<p style='color:green;'>CustomerController instantiated successfully!</p>";

echo "<h2>Step 3: Attempting test insert...</h2>";
$name       = "Test User";
$email      = "test_" . time() . "@example.com"; // unique email every time
$hashedPass = password_hash("1234", PASSWORD_DEFAULT);
$country    = "Zimbabwe";
$city       = "Harare";
$contact    = "1111";
$image      = null;
$role       = 2;

$result = $controller->insert($name, $email, $hashedPass, $country, $city, $contact, $image, $role);

if ($result) {
    echo "<h3 style='color:green;'>Success! Database connection and INSERT query worked properly.</h3>";
} else {
    echo "<h3 style='color:red;'>Failed! The insert method returned false.</h3>";
}