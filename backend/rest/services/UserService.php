<?php
require_once "BaseService.php";
require_once __DIR__ . '../../dao/UserDao.php';

class UserService extends BaseService{
    public function __construct(){
        $dao = new UserDao();
        parent::__construct($dao);
} 

public function getByEmail($email){
    return $this->dao->getByEmail($email);
}

public function changePassword($id, $password){
     $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    return $this->dao->changePassword($id, $hashedPassword);
}

public function getPassword ( $id ){
    return $this->dao->getPassword($id);
}

public function register($data){
     $email = "test@example.com";
    if(filter_var($data["email"], FILTER_VALIDATE_EMAIL)){
        if($this->dao->getByEmail($data["email"])){
        throw new Exception("Account with this email already exists.");
}else{
    $hashedPassword = password_hash($data['password'], PASSWORD_BCRYPT);
    $userData = [
        "name"=> $data["name"],
        "surname"=>$data["surname"],
        "email"=> $data["email"],
        "password"=> $hashedPassword,
        "username"=> $data["username"]
    ];
    return $this->dao->insert($userData);
}

    }
}

public function login($email, $password){
    $user = $this->dao->getByEmail($email);
   // print_r($user);  
   // print_r($user[0]["password"]);  
    if(!$user){
        throw new Exception("This email is not registered.");
}
else{
    if(password_verify($password, $user[0]["password"])){
        unset($user["password"]);
        return $user;
}
else {
            
            throw new Exception("User with these credidentials does not exist.");
        }
}}

public function removeUser( $id ){
    $this->dao->delete($id);
}
}
?> 