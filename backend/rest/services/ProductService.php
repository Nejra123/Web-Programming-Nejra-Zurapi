<?php
require_once "BaseService.php";
require_once __DIR__ . '../../dao/ProductsDao.php';

class ProductService extends BaseService{
    public function __construct(){
        $dao = new ProductsDao();
        parent::__construct($dao);
}

public function getQuantity($id){
    return $this->dao->getQuantity($id);
}
public function getPrice($id){
    return $this->dao->getPrice($id);
}

public function deleteProduct($id){
    return $this->dao->delete($id);
}

}
?>