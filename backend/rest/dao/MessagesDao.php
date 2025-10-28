<?php
require_once 'baseDAO.php';

class MessagesDao extends BaseDAO{

    public function __construct(){
        parent::__construct("messages");
    }
    public function getMessagesByUsername($username){
       return $this->getByField("username", $username);
}}
?>