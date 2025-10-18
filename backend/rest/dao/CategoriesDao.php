<?php
require_once "baseDAO.php";
class CategoriesDao extends BaseDAO{
    public function __construct(){
        parent::__construct("categories");
    }

    public function getNumOfProductsByName($name){
        $stmt=$this->connection->prepare("SELECT number_of_products FROM categories WHERE name = :name");
        $stmt->bindParam(":name",$name);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
?>