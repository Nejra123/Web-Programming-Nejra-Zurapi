<?php
require_once "baseDAO.php";

class Product_OrdersDao extends baseDAO{
    public function __construct(){
        parent::__construct("product_orders");
}

public function getByOrderId($order_id){
   
return $this->getByField("order_id", $order_id);
}}
?>