<?php
//anchor the path to classCustomerClass.php
require_once __DIR__ . "/../classes/CustomerClass.php";

class CustomerController
{
    //holds the  CustomerClass model instance
    private $customerModel;

    public function __construct()
    {
        $this->customerModel = new CustomerClass();
    }
        /**
     * Registers a new customer using the business rules:
     * - Verify email is not already taken
     * - Add customer to database
     * - Return structured status array
     *
     * @param string $email
     * @param array $data Associative array containing customer registration fields
     * @return array Structured result: ['success' => bool, 'error' => string|null, 'customer' => array|null]
     */
    public function register($data)
    {
        $name      = $data['customer_name'] ?? '';
        $email     = $data['customer_email'] ?? '';
        $pass      = $data['customer_pass'] ?? '';
        $country   = $data['customer_country'] ?? '';
        $city      = $data['customer_city'] ?? '';
        $contact   = $data['customer_contact'] ?? '';
        $image     = $data['customer_image'] ?? null; // optional field
        $role      = 2; // default role for new customers

        //check if email already exists
        if ($this->customerModel->emailExists($email)) {
            return [
                'success' => false,
                'error' => 'Email already exists.'
            ];
        }
        //email is free, proceed to add customer
        $inserted = $this->customerModel->addCustomer($name, $email, $pass, $country, $city, $contact, $image, $role);
        if ($inserted) {
            //fetch the newly created customer record to retrieve customer_id for session storage
            $user = $this->customerModel->getCustomerByEmail($email);
            return [
                'success' => true,
                'error' => null,
                'customer' => $user
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Failed to register customer.'
            ]; 

    }
    }
    //** Optional helper: fetch all customers(can be used for admin/view pages) */
    public function getAll()
    {
        return $this->customerModel->getAllCustomers();
    }
    public function login($email, $pass)
    {
        //basic validation to ensure neither field is empty
        if (empty($email) || empty($pass)){
            return [
                'success' => false,
                'error' => 'Email and password are required.'
            ];
        }
        //call login method
        $customer = $this->customerModel->login($email, $pass);
        if ($customer) {
            return [
                'success' => true,
                'customer' => $customer
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Invalid email or password.'
            ];  
    }
    }
}