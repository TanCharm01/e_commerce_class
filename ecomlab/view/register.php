<!--
    This is the "view" for registering a new customer.
    Flow: user fills this form -> clicks Register -> js/customer.js
    validates it and sends it to actions/customer_register_action.php
    -> which calls the controller -> which calls the model -> which
    inserts the row into the database.
-->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register Customer</title>
    <style>
        .form-group {
            margin-bottom: 12px;
        }
        .help-text {
            display: block;
            font-size: 0.82rem;
            color: #666;
            margin-top: 4px;
        }
        #formMessage {
            font-weight: bold;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <h1>Customer Registration</h1>

    <!-- Simple navigation -->
    <nav>
        <a href="../index.php">Home</a> |
        <a href="customers.php">View All Customers</a>
    </nav>

    <form id="registerForm">
        <div class="form-group">
            <label>Name</label><br>
            <input type="text" name="customer_name" id="customer_name">
        </div>
        <div class="form-group">
            <label>Email</label><br>
            <input type="text" name="customer_email" id="customer_email">
        </div>
        <div class="form-group">
            <label>Password</label><br>
            <input type="password" name="customer_pass" id="customer_pass">
            <small class="help-text">
                Must be at least 8 characters long and include at least 1 uppercase letter, 1 lowercase letter, 1 number, and 1 special symbol (@$!%*?&).
            </small>
        </div>
        <div class="form-group">
            <label>Country</label><br>
            <input type="text" name="customer_country" id="customer_country">
        </div>
        <div class="form-group">
            <label>City</label><br>
            <input type="text" name="customer_city" id="customer_city">
        </div>
        <div class="form-group">
            <label>Contact</label><br>
            <input type="text" name="customer_contact" id="customer_contact">
        </div>
        <div class="form-group">
            <label>Image (optional)</label><br>
            <input type="text" name="customer_image" id="customer_image">
        </div>
        <div class="form-group">
            <button type="button" onclick="registerCustomer()">Register</button>
        </div>
    </form>

    <!-- Validation/success/error messages get written into here by customer.js -->
    <p id="formMessage"></p>

    <!-- Loads the shared JavaScript file that contains registerCustomer() -->
    <script src="../js/customer.js"></script>
</body>
</html>