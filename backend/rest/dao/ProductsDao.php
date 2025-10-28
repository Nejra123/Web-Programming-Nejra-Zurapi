<?php
require_once "baseDAO.php";

class ProductsDao extends BaseDAO {
    public function __construct() {
        parent::__construct("products");
} 

public function getQuantity($id) {
    $product = $this->getById($id);
    return $product["quantity"];
}

public function getPrice($id) {
    $product = $this->getById($id);
    return $product["price"];
}

}
?>