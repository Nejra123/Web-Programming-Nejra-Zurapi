<?php
require_once "BaseService.php";
require_once __DIR__ . '../../dao/CategoriesDao.php';

class CategorieService extends BaseService{
     public function __construct(){
        $dao = new CategoriesDao();
        parent::__construct($dao);

}

public function getNumOfProductsByName($name){
    return $this->dao->getNumOfProductsByName($name);
}
}

?>