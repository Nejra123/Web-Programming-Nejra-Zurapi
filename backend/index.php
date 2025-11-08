<?php

require 'vendor/autoload.php';
require_once __DIR__ .'/rest/services/UserService.php';
require_once __DIR__ .'/rest/services/MessageService.php';
require_once __DIR__ .'/rest/services/OrderService.php';
require_once __DIR__ .'/rest/services/CategorieService.php';
require_once __DIR__ .'/rest/services/ProductService.php';
require_once __DIR__ .'/rest/services/Product_OrderService.php';
require __DIR__ . '/rest/services/AuthService.php';


use Firebase\JWT\JWT;
use Firebase\JWT\Key;



Flight::register('userService', 'UserService');
Flight::register('messageService', 'MessageService');
Flight::register('orderService', 'OrderService');
Flight::register('categorieService', 'CategorieService');
Flight::register('productService', 'ProductService');
Flight::register('product_orderService', 'Product_OrderService');
Flight::register('auth_service', "AuthService");

Flight::route('GET /', function(){
    echo 'Hello world! FlightPHP is working!';
});


Flight::route('/*', function() {
   if(
       strpos(Flight::request()->url, '/auth/login') === 0 ||
       strpos(Flight::request()->url, '/auth/register') === 0
   ) {
       return TRUE;
   } else {
       try {
           $token = Flight::request()->getHeader("Authentication");
           if(!$token)
               Flight::halt(401, "Missing authentication header");

           $decoded_token = JWT::decode($token, new Key(Config::JWT_SECRET(), 'HS256'));

           Flight::set('user', $decoded_token->user);
           Flight::set('jwt_token', $token);
           return TRUE;
       } catch (\Exception $e) {
           Flight::halt(401, $e->getMessage());
       }
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