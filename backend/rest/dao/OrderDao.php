<?php
require_once 'baseDAO.php';

class OrderDao extends BaseDao {
   public function __construct() {
       parent::__construct("orders");
   }

   public function getByUserId($customer_id) {
       return $this->getByField("customer_id", $customer_id);
   }

public function getByDate($Date) {
             return $this->getByField("Date", $Date);
}


}
?>
