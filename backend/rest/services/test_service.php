<?php
require_once 'OrderService.php';
require_once 'CategorieService.php';
require_once "MessageService.php";
require_once "Product_OrderService.php";
require_once "ProductService.php";
require_once "UserService.php";
/*
$Orderservice = new OrderService();
$order = $Orderservice->getByUserId(9);
print_r($order);


$CategorieService = new CategorieService();
$cat = $CategorieService->getNumOfProductsByName("fruits");
print_r($cat);

$MessageService = new MessageService();
$message = $MessageService->getMessagesByUsername("mjaumjau");
print_r($message);

$ProductOrderService = new Product_OrderService();
print_r($ProductOrderService->getByOrderId(1));

$ProductService = new ProductService(); 
print_r($ProductService->getPrice(2));
*/
$User = new UserService();
/*$User->changePassword(10, "changesPass");
print_r($User->getByEmail("nejrazurapi@gmail.com"));
$User->register(["name"=>"Hana", 
"surname"=>"Hanic",
 "email"=>"hanahanic@gmail.com", 
 "password"=>"hana123"]);
*/
print_r($User->login("lejlazurapi@gmail.com", "lejla123"));
//print_r($User->getByEmail("nejrazurapi@gmail.com"));


//$message = $MessageService->getMessagesByUsername("Nejra123");
//print_r("Message by username: ");
//print_r( $message);


//print_r("GET BY DATE: ");
/*
print_r($Orderservice->getByDate("2025-10-18"));

print_r("GET BY PRICE: ");
print_r($ProductService->getPrice(7));*/
?>
