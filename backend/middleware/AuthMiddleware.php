<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
class AuthMiddleware {
 

public function verifyToken($token){
    if(!$token)
        Flight::halt(401, "Missing authorization token"); 

    $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));

    return $decoded_token->user;
}
   
   public function authorizeRole($requiredRole) {
       $user = Flight::get('user');
       if ($user->role !== $requiredRole) {
           Flight::halt(403, 'Access denied: insufficient privileges');
       }
   }
   public function authorizeRoles($roles) {
       $user = Flight::get('user');
       if (!in_array($user->role, $roles)) {
           Flight::halt(403, 'Forbidden: role not allowed');
       }
   }
   function authorizePermission($permission) {
       $user = Flight::get('user');
       if (!in_array($permission, $user->permissions)) {
           Flight::halt(403, 'Access denied: permission missing');
       }
   }   
}
