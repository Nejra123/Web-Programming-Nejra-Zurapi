<?php
require_once "BaseService.php";
require_once __DIR__ . '../../dao/Product_OrdersDao.php';

class Product_OrderService extends BaseService{
    public function __construct(){
        $dao = new Product_OrdersDao();
        parent::__construct($dao);
    }

    public function getByOrderId($ordedr_id){
        return $this->dao->getByOrderId($ordedr_id);
    }

}

?>