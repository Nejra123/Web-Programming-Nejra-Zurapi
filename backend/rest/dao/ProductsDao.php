<?php
require_once "BaseDAO.php";

class ProductsDao extends BaseDAO {
    public function __construct() {
        parent::__construct("products");
} 

public function getQuantity($id) {
    $stmt = $this->connection->prepare("SELECT quantity FROM products WHERE id=:id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetchAll();
}

public function getPrice($id) {
    $stmt = $this->connection->prepare("SELECT price FROM products WHERE id=:id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetchAll();
}

}
?>