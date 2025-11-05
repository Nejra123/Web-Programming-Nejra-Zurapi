<?php
require_once __DIR__ . '../../dao/baseDAO.php';

class BaseService{
    protected $dao;
    function __construct($dao){
        $this->dao=$dao;
    }

    function getAll(){
        return $this->dao->getAll();
    }

    function getById($id){
        return $this->dao->getById($id);
    }

    function create($data){
        return $this->dao->insert($data);
    }
    
    function update($id,$data){
        return $this->dao->update($id,$data);
    }

    function delete($id){
        return $this->dao->delete($id);
    }

}

?>