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
    public function updateCartQuantity($product_id, $new_qty,$ip)
    {
        $new_qty = (int)$new_qty;
        if ($new_qty < 1) {
            return false;
        }

        $sql = "UPDATE `cart` SET `qty` = ? WHERE `p_id` = ? AND `ip_add` = ?";
        return $this->execute($sql, [$new_qty, (int)$product_id,$ip]);
    }
    //alias
    public function updateQty($product_id, $new_qty,$ip)
    {
        return $this->updateCartQuantity($product_id, $new_qty,$ip);
    }
    /**
     * Process payment inside a database transaction
     */
    public function processPayment($customer_id,$ip, $reference,$amount) {
        try {
            // 1. Fetch current cart items for this IP
            $cartSql = "SELECT p_id, qty FROM cart WHERE ip_add = ?";
            $cartItems =$this->fetchAll($cartSql, [$ip]);

            if (empty($cartItems)) {
                return false;
            }

            // Use underlying PDO connection if exposed by db_connection
            $pdo = $this->db ?? $this->conn ?? null;
            if ($pdo instanceof PDO) {$pdo->beginTransaction();
            }

            // 2. Generate 6-digit invoice number
            $invoice_no = mt_rand(100000, 999999);

            // 3. Insert into orders table
            $orderSql = "INSERT INTO orders (customer_id, invoice_no, order_date, order_status, created_at) 
                         VALUES (?, ?, CURDATE(), 'paid', NOW())";
            $this->execute($orderSql, [$customer_id,$invoice_no]);

            // Retrieve the newly generated order_id
            if ($pdo instanceof PDO) {
                $order_id =$pdo->lastInsertId();
            } else {
                $row =$this->fetchOne("SELECT order_id FROM orders WHERE invoice_no = ? ORDER BY order_id DESC LIMIT 1", [$invoice_no]);
                $order_id =$row['order_id'] ?? false;
            }

            if (!$order_id) {
                if ($pdo instanceof PDO && $pdo->inTransaction()) {$pdo->rollBack();
                }
                return false;
            }

            // 4. Copy cart rows into orderdetails table
            $detailSql = "INSERT INTO orderdetails (order_id, product_id, qty) VALUES (?, ?, ?)";
            foreach ($cartItems as $item) {$this->execute($detailSql, [$order_id, $item['p_id'],$item['qty']]);
            }

            // 5. Clear cart rows for this IP
            $deleteSql = "DELETE FROM cart WHERE ip_add = ?";
            $this->execute($deleteSql, [$ip]);

            if ($pdo instanceof PDO && $pdo->inTransaction()) {$pdo->commit();
            }

            return $order_id;

        } catch (Exception $e) {$pdo = $this->db ?? $this->conn ?? null;
            if ($pdo instanceof PDO && $pdo->inTransaction()) {$pdo->rollBack();
            }
            return false;
        }
    }

    /**
     * Fetch order header and joined line items with product details
     */
    public function getOrderById($order_id) {$orderSql = "SELECT o.*, c.customer_name, c.customer_email 
                     FROM orders o
                     LEFT JOIN customer c ON o.customer_id = c.customer_id
                     WHERE o.order_id = ? 
                     LIMIT 1";
        $order =$this->fetchOne($orderSql, [$order_id]);

        if (!$order) {
            return null;
        }

        $itemsSql = "SELECT od.product_id, od.qty, p.product_title, p.product_price, p.product_image
                     FROM orderdetails od
                     LEFT JOIN products p ON od.product_id = p.product_id
                     WHERE od.order_id = ?";
        $items =$this->fetchAll($itemsSql, [$order_id]);

        $order['items'] = $items ? $items : [];
        return $order;
    }
    
}