<?php
require_once 'baseDAO.php';

class AuthDao extends BaseDao {
   protected $table_name;

   public function __construct() {
       parent::__construct("customers");
   }

  public function getByEmail($email) {
       return $this->getByField("email", $email);
   }

   public function getByUsername($username){
    return $this->getByField("username",$username);
   }
}
