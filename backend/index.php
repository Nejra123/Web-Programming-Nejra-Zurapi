<?php

require 'vendor/autoload.php';
require_once __DIR__ .'/rest/services/UserService.php';
require_once __DIR__ .'/rest/services/MessageService.php';
require_once __DIR__ .'/rest/services/OrderService.php';
require_once __DIR__ .'/rest/services/CategorieService.php';
require_once __DIR__ .'/rest/services/ProductService.php';
require_once __DIR__ .'/rest/services/Product_OrderService.php';


Flight::register('userService', 'UserService');
Flight::register('messageService', 'MessageService');
Flight::register('orderService', 'OrderService');
Flight::register('categorieService', 'CategorieService');
Flight::register('productService', 'ProductService');
Flight::register('product_orderService', 'Product_OrderService');


Flight::route('GET /', function(){
    echo 'Hello world! FlightPHP is working!';
});


require_once __DIR__ .'/rest/routes/UserRoutes.php';
require_once __DIR__ .'/rest/routes/MessagesRoutes.php';
require_once __DIR__ .'/rest/routes/OrderRoutes.php';
require_once __DIR__ .'/rest/routes/CategorieRoutes.php';
require_once __DIR__ .'/rest/routes/ProductRoutes.php';
require_once __DIR__ .'/rest/routes/Product_OrderRoutes.php';


Flight::start();