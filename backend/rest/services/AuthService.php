<?php
require_once 'BaseService.php';
require_once __DIR__ . '/../dao/AuthDao.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthService extends BaseService {
   private $auth_dao;
   public function __construct() {
       $this->auth_dao = new AuthDao();
       parent::__construct(new AuthDao);
   }

   public function getByEmail($email){
       return $this->auth_dao->getByEmail($email);
   }

public function register($data) {
    if (empty($data['email']) || empty($data['password']) || empty($data['name']) || empty($data['surname']) || empty($data['username'])) {
        return ['success' => false, 'error' => 'All fields (name, surname, email, password, username) are required.'];
    }
    if (!filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
        return ['success' => false, 'error' => 'Invalid email format.'];
    }
    $email_exists = $this->dao->getByEmail($data['email']);
    if ($email_exists) {
        return ['success' => false, 'error' => 'Account with this email already exists.'];
    }
    $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
    $userData = [
        "name"     => $data["name"],
        "surname"  => $data["surname"],
        "email"    => $data["email"],
        "password" => $data["password"],
        "username" => $data["username"]
    ];
    $user= $this->dao->insert($userData);
    
    if (is_array($user) && isset($user['password'])) {
        unset($user['password']);
    } else {
        unset($data['password']);
        $user = $data;
    }
    
    return ['success' => true, 'data' => $user];
}

   public function login($data) {
    if (empty($data['email']) || empty($data['password'])) {
        return ['success' => false, 'error' => 'Email and password are required.'];
    }
    $userData = $this->dao->getByEmail($data['email']);
    
    if (!$userData) {
        return ['success' => false, 'error' => 'Invalid email or password.'];
    }
      
    
    if (!password_verify($data['password'], $userData[0]["password"])) {
        return ['success' => false, 'error' => 'Invalid email or password.'];
    }
    
    unset($userData[0]['password']);


           $jwt_payload = [
           'user' => $userData[0],
           'iat' => time(),
           'exp' => time() + (60 * 60 * 24) 
       ];

       $token = JWT::encode(
           $jwt_payload,
           Config::JWT_SECRET(),
           'HS256'
       );


    return ['success' => true, 'data' => array_merge($userData[0], ['token' => $token])];             

}
}
