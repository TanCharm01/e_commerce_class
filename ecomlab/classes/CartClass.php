<?php
require_once __DIR__ . '/../core/db_class.php';

class CartClass extends Database
{
    /**
     * isInCart($product_id,$ip):
     * SELECT from cart WHERE p_id = ? AND ip_add = ? — returns true/false
     */
    public function isInCart($product_id,$ip)
    {
        $sql = "SELECT * FROM `cart` WHERE `p_id` = ? AND `ip_add` = ?";
        $result = $this->fetchOne($sql, [$product_id,$ip]);
        return !empty($result);
    }

    /**
     * addToCart($product_id,$qty, $ip,$customer_id = null):
     * INSERT INTO cart (p_id, ip_add, qty) VALUES (?, ?, ?) — prepared statement
     */
    public function addToCart($product_id,$qty, $ip,$customer_id = null)
    {
        // If customer is logged in and c_id exists in your cart table
        if ($customer_id !== null) {$sql = "INSERT INTO `cart` (`p_id`, `ip_add`, `c_id`, `qty`) VALUES (?, ?, ?, ?)";
            return $this->execute($sql, [$product_id,$ip, $customer_id,$qty]);
        }

        $sql = "INSERT INTO `cart` (`p_id`, `ip_add`, `qty`) VALUES (?, ?, ?)";
        return $this->execute($sql, [$product_id, $ip,$qty]);
    }

    /**
     * getCartItems($ip):
     * SELECT cart.*, products.product_title, products.product_price, products.product_image 
     * FROM cart JOIN products ON cart.p_id = products.product_id WHERE cart.ip_add = ?
     */
    public function getCartItems($ip)
    {
        $sql = "SELECT cart.*, products.product_title, products.product_price, products.product_image 
                FROM `cart` 
                JOIN `products` ON cart.p_id = products.product_id 
                WHERE cart.ip_add = ?";
        return $this->fetchAll($sql, [$ip]);
    }

    /**
     * getCartCount($ip):
     * SELECT COUNT(*) FROM cart WHERE ip_add = ? — returns integer
     */
    public function getCartCount($ip)
    {
        $sql = "SELECT COUNT(*) AS total_count FROM `cart` WHERE `ip_add` = ?";
        $row =$this->fetchOne($sql, [$ip]);
        return $row ? (int)$row['total_count'] : 0;
    }

    /**
     * getCartTotal($ip):
     * Iterate cart items, SUM(qty * product_price). Returns float.
     */
    public function getCartTotal($ip)
    {
        $items =$this->getCartItems($ip);$total = 0.0;

        if (!empty($items)) {
            foreach ($items as $item) {$total += ((int)$item['qty'] * (float)$item['product_price']);
            }
        }

        return (float)$total;
    }
    /**
     * Remove an item from the cart by product ID and IP address
     */
    public function removeFromCart($product_id,$ip)
    {
        $sql = "DELETE FROM `cart` WHERE `p_id` = ? AND `ip_add` = ?";
        return $this->execute($sql, [$product_id,$ip]);
    }

    /**
     * Update quantity of an item in the cart
     */
    public function updateCartQuantity($product_id, $qty,$ip)
    {
        $sql = "UPDATE `cart` SET `qty` = ? WHERE `p_id` = ? AND `ip_add` = ?";
        return $this->execute($sql, [$qty, $product_id,$ip]);
    }
}