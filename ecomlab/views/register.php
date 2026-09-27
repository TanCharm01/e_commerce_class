<?php
// Start session to inspect error flash messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 2rem;
            display: flex;
            justify-content: center;
        }
        .form-container {
            background: #fff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 480px;
        }
        .form-group {
            margin-bottom: 1.2rem;
            display: flex;
            flex-direction: column;
        }
        label {
            font-weight: bold;
            margin-bottom: 0.4rem;
        }
        input, select {
            padding: 0.6rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }
        input:focus, select:focus {
            outline: none;
            border-color: #007bff;
        }
        .error-msg {
            color: #dc3545;
            font-size: 0.85rem;
            margin-top: 0.3rem;
            display: block;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            padding: 0.8rem;
            border-radius: 4px;
            margin-bottom: 1.2rem;
            border: 1px solid #f5c6cb;
        }
        .btn-submit {
            background-color: #007bff;
            color: white;
            padding: 0.75rem;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            width: 100%;
        }
        .btn-submit:hover {
            background-color: #0056b3;
        }
        .btn-submit:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Register Account</h2>

    <!-- Display $_SESSION['error'] at top of form if set, then unset it -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-danger">
            <?= htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form id="registerForm" action="../actions/register_action.php" method="POST" novalidate>
        
        <div class="form-group">
            <label for="customer_name">Full Name *</label>
            <input type="text" id="customer_name" name="customer_name">
            <span class="error-msg" id="name-error"></span>
        </div>

        <div class="form-group">
            <label for="customer_email">Email Address *</label>
            <input type="email" id="customer_email" name="customer_email">
            <span class="error-msg" id="email-error"></span>
        </div>

        <div class="form-group">
            <label for="customer_pass">Password *</label>
            <input type="password" id="customer_pass" name="customer_pass">
            <span class="error-msg" id="pass-error"></span>
        </div>

        <div class="form-group">
            <label for="customer_country">Country *</label>
            <select id="customer_country" name="customer_country">
                <option value="">Select a country</option>
                <option value="Ghana">Ghana</option>
                <option value="Nigeria">Nigeria</option>
                <option value="Kenya">Kenya</option>
                <option value="Zimbabwe">Zimbabwe</option>
                <option value="South Africa">South Africa</option>
                <option value="United Kingdom">United Kingdom</option>
                <option value="United States">United States</option>
            </select>
            <span class="error-msg" id="country-error"></span>
        </div>

        <div class="form-group">
            <label for="customer_city">City *</label>
            <input type="text" id="customer_city" name="customer_city">
            <span class="error-msg" id="city-error"></span>
        </div>

        <div class="form-group">
            <label for="customer_contact">Contact Number *</label>
            <input type="text" id="customer_contact" name="customer_contact" placeholder="+233XXXXXXXXX">
            <span class="error-msg" id="contact-error"></span>
        </div>

        <div class="form-group">
            <label for="customer_image">Profile Picture (Optional)</label>
            <input type="file" id="customer_image" name="customer_image" accept="image/*">
            <span class="error-msg" id="image-error"></span>
        </div>

        <button type="submit" id="submitBtn" class="btn-submit">Register</button>
    </form>
</div>

<!-- Step 5 client-side validation script -->
<script src="../js/validate.js"></script>
</body>
</html>