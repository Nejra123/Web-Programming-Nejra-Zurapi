<?php
require_once "baseDAO.php";

class Product_OrdersDao extends baseDAO{
    public function __construct(){
        parent::__construct("product_orders");
}

public function getByOrderId($order_id){
    $stmt = $this->connection->prepare("SELECT * FROM product_orders WHERE order_id  = :order_id");
    $stmt->bindParam(":order_id", $order_id);
    $stmt -> execute();
    return $stmt->fetchAll();
}

}
?>