<?php
/*
require_once "BaseService.php";
require_once __DIR__ . '/../dao/Product_OrdersDao.php';

class Product_OrderService extends BaseService {
    public function __construct() {
        $dao = new Product_OrdersDao();
        parent::__construct($dao);
    }

    public function getByOrderId($order_id) {
        error_log("Product_OrderService->getByOrderId($order_id)");
        $result = $this->dao->getByOrderId($order_id);
        error_log("Found " . count($result) . " items");
        return $result;
    }
}
*/
?>