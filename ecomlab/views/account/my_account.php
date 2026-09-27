<?php
// Ensure session is started to read authentication variables
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Security guard: If the user is not logged in, redirect them back to login
if (!isset($_SESSION['customer_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 2rem;
            display: flex;
            justify-content: center;
        }
        .account-card {
            background: #ffffff;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
        }
        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            background-color: #28a745;
            color: white;
            font-size: 0.85rem;
        }
        .btn-group {
            margin-top: 1.5rem;
            display: flex;
            gap: 10px;
        }
        .btn {
            display: inline-block;
            padding: 0.6rem 1.2rem;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 0.9rem;
            text-align: center;
        }
        .btn-primary {
            background-color: #007bff;
            color: white;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-logout {
            background-color: #dc3545;
            color: white;
        }
        .btn-logout:hover {
            background-color: #bd2130;
        }
    </style>
</head>
<body>

<div class="account-card">
    <h2>Welcome to Your Account!</h2>
    <p>You are signed in as: <strong><?= htmlspecialchars($_SESSION['customer_name'] ?? 'Customer'); ?></strong></p>
    <hr>
    <p><strong>Customer ID:</strong> <?= htmlspecialchars($_SESSION['customer_id'] ?? ''); ?></p>
    <p><strong>Email:</strong> <?= htmlspecialchars($_SESSION['customer_email'] ?? 'Not set'); ?></p>
    <p><strong>Role:</strong> <span class="badge"><?= (isset($_SESSION['user_role']) && (int)$_SESSION['user_role'] === 1) ? 'Admin' : 'Customer'; ?></span></p>

    <div class="btn-group">
        <a href="../../index.php" class="btn btn-primary">Go to Home</a>
        <!-- Points to actions/logout_action.php from views/account/ -->
        <a href="../../actions/logout_action.php" class="btn btn-logout">Logout</a>
    </div>
</div>

</body>
</html>