<?php

// Bring in the Database class so Customer can extend it
require_once "../core/db_class.php";

// This is the "model" layer for the customer table. It only knows about
// the `customer` table and the SQL needed to read/write it - it has no
// idea about forms, HTML, or JSON. That separation makes it reusable
// from anywhere (a controller, a script, a test, etc.).
//
// "extends Database" means Customer automatically inherits the connection
// logic and the fetchAll()/fetchOne()/execute() helper methods from the
// Database class, without having to rewrite any of that here.
class CustomerClass extends Database
{
    public function emailExists($email)
    {
        $sql = "SELECT Customer_email FROM customer WHERE customer_email = ?";
        $result = $this->fetchOne($sql, [$email]);
        return !empty($result);
    }

    // Insert a new customer row (this is what "registration" does).
    // Each parameter maps to one column in the `customer` table.
    public function addCustomer($name, $email, $pass, $country, $city, $contact, $image=null, $role=2)
    {
        $hashedPass = password_hash($pass, PASSWORD_BCRYPT); // hash the password before storing it
        // "?" are placeholders - PDO fills them in safely with the values
        // from the array below, which prevents SQL injection.
        $sql = "
            INSERT INTO customer (
                customer_name,
                customer_email,
                customer_pass,
                customer_country,
                customer_city,
                customer_contact,
                customer_image,
                user_role
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ";

        // execute() comes from the Database class (see core/db_class.php)
        return $this->execute(
            $sql,
            [$name, $email, $hashedPass, $country, $city, $contact, $image, $role]
        );
    }
    /**
     * helper method to fetch customer details by email
     * needed by the action file to populate $_SESSION['customer_id'] and $_SESSION['user_role'] 
     */
    public function getCustomerByEmail($email)
    {
        $sql = "SELECT * FROM customer WHERE customer_email=?";
        return $this->fetchOne($sql, [$email]);
    
    }
    public function login($email, $pass)
    {
        //fetch user by email
        $customer = $this->getCustomerByEmail($email);
        //fail if user doesn exist
        if (!$customer) {
            return false;
        }
        //verify password
        if (password_verify($pass, $customer['customer_pass'])) {
            return $customer;
        }
        return false;
    }
    // Get every customer in the table, newest first.
    // Note: customer_pass is deliberately left out of the SELECT so
    // password hashes are never sent to the views/pages that list customers.
    public function getAllCustomers()
    {
        $sql = "
            SELECT
                customer_id,
                customer_name,
                customer_email,
                customer_country,
                customer_city,
                customer_contact,
                customer_image,
                user_role
            FROM customer
            ORDER BY customer_id DESC
        ";

        // fetchAll() comes from the Database class (see core/db_class.php)
        return $this->fetchAll($sql);
    }

    // To keep building this app, add more methods here for anything else
    // the customer table needs, e.g. getCustomerById(), updateCustomer(),
    // deleteCustomer(), findByEmail() for login, etc.
}
