<?php
require_once "BaseService.php";
require_once __DIR__ . '../../dao/MessagesDao.php';

class MessageService extends BaseService {

    public function __construct() {
        $dao = new MessagesDao();
        parent::__construct($dao);
}


public function getMessagesByUsername($username) {
 
    $username_lower = strtolower($username); 

    return $this->dao->getMessagesByUsername($username_lower); 
}

}
?>