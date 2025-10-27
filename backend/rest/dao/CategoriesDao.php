<?php
require_once "baseDAO.php";
class CategoriesDao extends BaseDAO{
    public function __construct(){
        parent::__construct("categories");
    }

    public function getNumOfProductsByName($name){
        return $this->getByField("name", $name);
}}
?>