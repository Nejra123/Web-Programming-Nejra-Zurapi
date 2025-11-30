<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware {
    
    
    public function verifyToken($token) {
        if (empty($token)) {
            throw new Exception("No token provided");
        }

        try {
            $decoded = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));
            
            $user_data = json_decode(json_encode($decoded), true);
            
            if (isset($user_data['user'])) {
                return $user_data['user'];
            }
            
            return $user_data;
            
        } catch (Exception $e) {
            throw new Exception("Invalid token: " . $e->getMessage());
        }
    }
    
   
    public function authorizeRole($required_role) {
        $user = Flight::get('user');
        
        if (!$user) {
            Flight::halt(401, json_encode([
                'success' => false,
                'error' => 'Unauthorized - No user found'
            ]));
            return;
        }
        
        $user_role = is_array($user) ? $user['role'] : $user->role;
        
        if ($user_role !== $required_role) {
            Flight::halt(403, json_encode([
                'success' => false,
                'error' => 'Forbidden - Insufficient permissions'
            ]));
            return;
        }
    }
    
  
    public function authorizeRoles($allowed_roles) {
        $user = Flight::get('user');
        
        if (!$user) {
            Flight::halt(401, json_encode([
                'success' => false,
                'error' => 'Unauthorized - No user found'
            ]));
            return;
        }
        
        $user_role = is_array($user) ? $user['role'] : $user->role;
        
        if (!in_array($user_role, $allowed_roles)) {
            Flight::halt(403, json_encode([
                'success' => false,
                'error' => 'Forbidden - Insufficient permissions'
            ]));
            return;
        }
    }
}

?>