<?php
require_once 'baseDAO.php';

class UserDao extends BaseDao {
   public function __construct() {
       parent::__construct("customers");
   }

   public function getByEmail($email) {
       return $this->getByField("email", $email);
   }

   public function changePassword($id, $password) {
    return $this->update($id, ['password' => $password]);

}}

?>
