<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure core settings (BASE_URL, session, auth helpers) are loaded
require_once __DIR__ . '/../core/core.php';

// Include the shared site navbar header
require_once __DIR__ . '/layout/header.php';
?>

<main class="register-container">
    <div class="register-card">
        <h2>Create an Account</h2>
        <p class="subtitle">Join TanShop today. Please fill in your details below.</p>

        <form id="registerForm" novalidate>
            <div class="form-group">
                <label for="customer_name">Full Name</label>
                <input type="text" name="customer_name" id="customer_name" placeholder="John Doe" required>
            </div>

            <div class="form-group">
                <label for="customer_email">Email Address</label>
                <input type="email" name="customer_email" id="customer_email" placeholder="john@example.com" required>
            </div>

            <div class="form-group">
                <label for="customer_pass">Password</label>
                <input type="password" name="customer_pass" id="customer_pass" placeholder="••••••••" required>
                
                <!-- Password Strength Indicator -->
                <div id="strengthContainer" class="strength-container">
                    <div class="strength-track">
                        <div id="strengthBar" class="strength-bar"></div>
                    </div>
                    <span id="strengthText" class="strength-text"></span>
                </div>

                <small class="help-text">
                    Must be at least 8 characters with 1 uppercase, 1 lowercase, 1 number, and 1 special symbol (@$!%*?&#^()_-+=).
                </small>
            </div>

            <div class="form-row">
                <div class="form-group half-width">
                    <label for="customer_country">Country</label>
                    <input type="text" name="customer_country" id="customer_country" placeholder="Ghana" required>
                </div>
                <div class="form-group half-width">
                    <label for="customer_city">City</label>
                    <input type="text" name="customer_city" id="customer_city" placeholder="Accra" required>
                </div>
            </div>

            <div class="form-group">
                <label for="customer_contact">Contact Number</label>
                <input type="text" name="customer_contact" id="customer_contact" placeholder="+233..." required>
            </div>

            <div class="form-group">
                <label for="customer_image">Profile Image URL <span class="optional-tag">(optional)</span></label>
                <input type="text" name="customer_image" id="customer_image" placeholder="https://...">
            </div>

            <button type="button" class="btn-submit" onclick="registerCustomer()">Register</button>
        </form>

        <!-- Feedback message area -->
        <div id="formMessage" class="form-message"></div>

        <div class="footer-links">
            <span>Already have an account? <a href="<?= BASE_URL; ?>/views/login.php">Login here</a></span>
        </div>
    </div>
</main>

<style>
    body {
        margin: 0;
        background-color: #f4f6f9;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    }

    .register-container {
        min-height: calc(100vh - 100px);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2.5rem 1rem;
    }

    .register-card {
        background: #ffffff;
        width: 100%;
        max-width: 480px;
        padding: 2.5rem;
        border-radius: 12px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
    }

    .register-card h2 {
        margin: 0 0 0.5rem 0;
        color: #212529;
        font-size: 1.6rem;
    }

    .subtitle {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1.8rem;
    }

    .form-group {
        margin-bottom: 1.25rem;
    }

    .form-row {
        display: flex;
        gap: 1rem;
    }

    .half-width {
        flex: 1;
    }

    label {
        display: block;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 0.4rem;
        color: #374151;
    }

    .optional-tag {
        font-weight: normal;
        color: #9ca3af;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 0.7rem 0.85rem;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 0.95rem;
        box-sizing: border-box;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    input:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
    }

    .strength-container {
        margin-top: 8px;
    }

    .strength-track {
        height: 6px;
        background-color: #e5e7eb;
        border-radius: 9999px;
        overflow: hidden;
    }

    .strength-bar {
        height: 100%;
        width: 0%;
        border-radius: 9999px;
        transition: width 0.3s ease, background-color 0.3s ease;
    }

    .strength-text {
        display: block;
        font-size: 0.78rem;
        font-weight: 600;
        margin-top: 4px;
        min-height: 1rem;
    }

    .help-text {
        display: block;
        font-size: 0.75rem;
        color: #6b7280;
        margin-top: 5px;
        line-height: 1.35;
    }

    .btn-submit {
        width: 100%;
        padding: 0.8rem;
        background-color: #2563eb;
        color: #ffffff;
        border: none;
        border-radius: 6px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: background-color 0.2s;
        margin-top: 0.5rem;
    }

    .btn-submit:hover {
        background-color: #1d4ed8;
    }

    .form-message {
        margin-top: 1rem;
        font-size: 0.9rem;
        font-weight: 500;
        text-align: center;
    }

    .footer-links {
        margin-top: 1.5rem;
        padding-top: 1.25rem;
        border-top: 1px solid #f3f4f6;
        text-align: center;
        font-size: 0.88rem;
        color: #4b5563;
    }

    .footer-links a {
        color: #2563eb;
        text-decoration: none;
        font-weight: 600;
    }

    .footer-links a:hover {
        text-decoration: underline;
    }
</style>

<!-- Load JS script at the bottom -->
<script src="../js/customer.js?v=<?= time(); ?>"></script>
</body>
</html>