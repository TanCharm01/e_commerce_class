<?php
require_once __DIR__ . '/../core/db_class.php';

class ProductClass extends Database {

    /**
     * Fetch a single brand by its ID
     */
    public function getBrandById($id) {
        $sql = "SELECT * FROM brands WHERE brand_id = ?";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Update brand name using prepared statement
     */
    public function updateBrand($id, $name) {
        $sql = "UPDATE brands SET brand_name = ? WHERE brand_id = ?";
        return $this->execute($sql, [$name, $id]);
    }

    /**
     * Fetch all brands (used for the listing table)
     */
    public function getAllBrands() {
        $sql = "SELECT * FROM brands ORDER BY brand_id DESC";
        return $this->fetchAll($sql);
    }
    /**
     * Add a new brand
     *
     * @param string $name
     * @return bool
     */
    public function addBrand($name) {
        $sql = "INSERT INTO brands (brand_name) VALUES (?)";
        return $this->execute($sql, [$name]);
    }
    /**
     * Add a new category
     *
     * @param string $name
     * @return bool
     */
    public function addCategory($name) {
        $sql = "INSERT INTO categories (cat_name) VALUES (?)";
        return $this->execute($sql, [$name]);
    }

    /**
     * Fetch all categories ordered alphabetically by name
     *
     * @return array
     */
    public function getAllCategories() {
        $sql = "SELECT * FROM categories ORDER BY cat_name ASC";
        return $this->fetchAll($sql);
    }
    /**
     * Fetch a single category by its primary key
     *
     * @param int $id
     * @return array|false
     */
    public function getCategoryById($id) {
        $sql = "SELECT * FROM categories WHERE cat_id = ? LIMIT 1";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Update an existing category name
     *
     * @param int $id
     * @param string $name
     * @return bool
     */
    public function updateCategory($id, $name) {
        $sql = "UPDATE categories SET cat_name = ? WHERE cat_id = ?";
        return $this->execute($sql, [$name, $id]);
    }
    /**
     * Add a new product
     */
    public function addProduct($cat, $brand, $title, $price, $desc, $image_filename, $keywords) {
        $sql = "INSERT INTO products (product_cat, product_brand, product_title, product_price, product_desc, product_image, product_keywords) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        return $this->execute($sql, [$cat, $brand, $title, $price, $desc, $image_filename, $keywords]);
    }

    /**
     * Update an existing product
     */
    public function updateProduct($id, $cat, $brand, $title, $price, $desc, $image_filename, $keywords) {
        $sql = "UPDATE products 
                SET product_cat = ?, product_brand = ?, product_title = ?, product_price = ?, product_desc = ?, product_image = ?, product_keywords = ? 
                WHERE product_id = ?";
        return $this->execute($sql, [$cat, $brand, $title, $price, $desc, $image_filename, $keywords, $id]);
    }

    /**
     * Fetch a single product with Category and Brand names joined
     */
    public function getProductById($id) {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                WHERE p.product_id = ? 
                LIMIT 1";
        return $this->fetchOne($sql, [$id]);
    }

    /**
     * Fetch all products with Category and Brand names joined
     */
    public function getAllProducts() {
        $sql = "SELECT p.*, c.cat_name, b.brand_name 
                FROM products p
                LEFT JOIN categories c ON p.product_cat = c.cat_id
                LEFT JOIN brands b ON p.product_brand = b.brand_id
                ORDER BY p.product_id DESC";
        return $this->fetchAll($sql);
    }

}