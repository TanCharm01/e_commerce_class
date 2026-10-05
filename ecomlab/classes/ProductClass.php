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
}