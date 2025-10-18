<?php
require_once 'baseDAO.php';

class OrderDao extends BaseDao {
   public function __construct() {
       parent::__construct("orders");
   }

   public function getByUserId($customer_id) {
       $stmt = $this->connection->prepare("SELECT * FROM orders WHERE customer_id = :customer_id");
       $stmt->bindParam(':customer_id', $customer_id);
       $stmt->execute();
       return $stmt->fetchAll();
   }

public function getByDate($Date) {
        $stmt = $this->connection->prepare("SELECT * FROM orders WHERE Date=:Date" );
        $stmt->bindParam(":Date",$Date);
        $stmt->execute();
        return $stmt->fetchAll();
}

public function getByCustomer($customer_id) {
    $stmt=$this->connection->prepare("SELECT * FROM orders WHERE customer_id = :customer_id");
    $stmt->bindParam(":customer_id",$customer_id);
    $stmt->execute();
    return $stmt->fetchAll();
}

}
?>
