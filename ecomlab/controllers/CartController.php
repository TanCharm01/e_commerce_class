<?php
require_once __DIR__ . '/../classes/CartClass.php';

class CartController
{
    private $cartModel;

    public function __construct()
    {
        $this->cartModel = new CartClass();
    }

    public function isInCart($product_id, $ip)
    {
        return $this->cartModel->isInCart($product_id, $ip);
    }

    public function addToCart($product_id, $qty, $ip, $customer_id = null)
    {
        return $this->cartModel->addToCart($product_id, $qty, $ip, $customer_id);
    }

    public function getCartItems($ip)
    {
        return $this->cartModel->getCartItems($ip);
    }

    public function getCartCount($ip)
    {
        return $this->cartModel->getCartCount($ip);
    }

    public function getCartTotal($ip)
    {
        return $this->cartModel->getCartTotal($ip);
    }
    public function removeFromCart($product_id, $ip)
    {
        return $this->cartModel->removeFromCart($product_id, $ip);
    }

    public function updateCartQuantity($product_id, $qty, $ip)
    {
        return $this->cartModel->updateCartQuantity($product_id, $qty, $ip);
    }
}

// Procedural helper functions matching typical lab controller style
function is_in_cart_ctr($product_id, $ip)
{
    $cart = new CartClass();
    return $cart->isInCart($product_id, $ip);
}

function add_to_cart_ctr($product_id, $qty, $ip, $customer_id = null)
{
    $cart = new CartClass();
    return $cart->addToCart($product_id, $qty, $ip, $customer_id);
}

function get_cart_items_ctr($ip)
{
    $cart = new CartClass();
    return $cart->getCartItems($ip);
}

function get_cart_count_ctr($ip)
{
    $cart = new CartClass();
    return $cart->getCartCount($ip);
}

function get_cart_total_ctr($ip)
{
    $cart = new CartClass();
    return $cart->getCartTotal($ip);
}
function remove_from_cart_ctr($product_id, $ip)
{
    $cart = new CartClass();
    return $cart->removeFromCart($product_id, $ip);
}

function update_cart_quantity_ctr($product_id, $qty, $ip)
{
    $cart = new CartClass();
    return $cart->updateCartQuantity($product_id, $qty, $ip);
}
