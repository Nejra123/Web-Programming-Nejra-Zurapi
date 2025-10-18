<?php
require_once 'baseDAO.php';

class UserDao extends BaseDao {
   public function __construct() {
       parent::__construct("customers");
   }

   public function getByEmail($email) {
       $stmt = $this->connection->prepare("SELECT * FROM users WHERE email = :email");
       $stmt->bindParam(':email', $email);
       $stmt->execute();
       return $stmt->fetch();
   }

   public function changePassword($id, $password) {
    $stmt = $this->connection->prepare('UPDATE customers SET password=:password WHERE id=:id');
$stmt->bindParam(':id', $id);
$stmt->bindParam(':password', $password);
$stmt->execute();

}}

?>
