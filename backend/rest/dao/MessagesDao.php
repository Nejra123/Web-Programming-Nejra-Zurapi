<?php
require_once 'baseDAO.php';

class MessagesDao extends BaseDAO{

    public function __construct(){
        parent::__construct("messages");
    }
    public function getMessagesByUsername($username){
        $stmt = $this->connection->prepare("SELECT content FROM messages WHERE username= :username");
        $stmt->bindParam(":username", $username);
        $stmt->execute();
       return $stmt->fetchAll();
}}
?>