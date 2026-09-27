<?php
// Start session to inspect error flash messages
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect straight to home
if (isset($_SESSION['customer_id'])) {
    header("Location: ../index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 2rem;
            display: flex;
            justify-content: center;
        }
        .login-container {
            background: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
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
        input {
            padding: 0.6rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1rem;
        }
        input:focus {
            outline: none;
            border-color: #007bff;
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
        .footer-link {
            margin-top: 1.5rem;
            text-align: center;
            font-size: 0.95rem;
        }
        .footer-link a {
            color: #007bff;
            text-decoration: none;
        }
        .footer-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Login to Your Account</h2>

    <!-- Display any $_SESSION['error'] and immediately clear it -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-danger">
            <?= htmlspecialchars($_SESSION['error']); ?>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <form action="../actions/login_action.php" method="POST">
        <div class="form-group">
            <label for="customer_email">Email Address</label>
            <input type="email" id="customer_email" name="customer_email" required>
        </div>

        <div class="form-group">
            <label for="customer_pass">Password</label>
            <input type="password" id="customer_pass" name="customer_pass" required>
        </div>

        <button type="submit" class="btn-submit">Login</button>
    </form>

    <!-- Link to Register page below the form -->
    <div class="footer-link">
        Don't have an account? <a href="register.php">Register here</a>
    </div>
</div>

</body>
</html>