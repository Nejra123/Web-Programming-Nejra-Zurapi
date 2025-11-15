<?php

require 'vendor/autoload.php';
require_once __DIR__ .'/rest/services/UserService.php';
require_once __DIR__ .'/rest/services/MessageService.php';
require_once __DIR__ .'/rest/services/OrderService.php';
require_once __DIR__ .'/rest/services/CategorieService.php';
require_once __DIR__ .'/rest/services/ProductService.php';
require_once __DIR__ .'/rest/services/Product_OrderService.php';
require __DIR__ . '/rest/services/AuthService.php';
require __DIR__ ."/middleware/AuthMiddleware.php";
require __DIR__ ."/data/roles.php";


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



Flight::register('userService', 'UserService');
Flight::register('messageService', 'MessageService');
Flight::register('orderService', 'OrderService');
Flight::register('categorieService', 'CategorieService');
Flight::register('productService', 'ProductService');
Flight::register('product_orderService', 'Product_OrderService');
Flight::register('auth_service', "AuthService");
Flight::register('auth_middleware', "AuthMiddleware");

Flight::route('GET /', function(){
    echo 'Hello world! FlightPHP is working!';
});

Flight::before('start', function() {
    $url = Flight::request()->url;
    
    if (
        strpos($url, '/auth/login') === 0 ||
        strpos($url, '/auth/register') === 0 
    ) {
        return; 
    } 

    try {
        $token = Flight::request()->getHeader("Authentication"); 
        
        $user_data = Flight::auth_middleware()->verifyToken($token);
        Flight::set('jwt_token', $token);
        Flight::set('user', $user_data); //it doesnt want to read the user if this is in verifyToken

    } catch (\Exception $e) {
        Flight::halt(401, $e->getMessage());
    }
});
require_once __DIR__ .'/rest/routes/UserRoutes.php';
require_once __DIR__ .'/rest/routes/MessagesRoutes.php';
require_once __DIR__ .'/rest/routes/OrderRoutes.php';
require_once __DIR__ .'/rest/routes/CategorieRoutes.php';
require_once __DIR__ .'/rest/routes/ProductRoutes.php';
require_once __DIR__ .'/rest/routes/Product_OrderRoutes.php';
require_once __DIR__ .'/rest/routes/AuthRoutes.php';


Flight::start();