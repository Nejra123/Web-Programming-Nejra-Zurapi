<?php
require_once 'UserDao.php';
require_once 'OrderDao.php';
require_once 'MessagesDao.php';
require_once 'CategoriesDao.php';
require_once 'ProductsDao.php';
require_once 'Product_OrdersDao.php';

$userDao = new UserDao();
$orderDao = new OrderDao();
$messagesDao = new MessagesDao();
$categoriesDao = new CategoriesDao();
$productsDao = new ProductsDao();
$product_ordersDao = new Product_OrdersDao();

// Insert a new user (Customer)
/*$userDao->insert([
   'name' => 'John Doe',
   'email' => 'john@example.com',
   'password' => password_hash('password123', PASSWORD_DEFAULT),
]);*/

/*$orderDao->insert([
   "date"=> "2025-10-18",
   "time" => "12:59:55",
   "address" => "address123",
   "amount"=> 12.44,

]);
*/
//print_r($orderDao->getByDate("2025-10-18"));
 
/*$orderDao->update(0, [
   "customer_id"=>9
]);*/

//print_r($orderDao->getByCustomer(9));

$userDao->changePassword(9,"nejraUpdate123");
/*$messagesDao->insert([
   "id"=>0,
   "username"=> "mjaumjau",
   "content"=> "Amazing service!",
   "customer_id"=> 9,
])*/
//print_r($messagesDao->getMessagesByUsername("mjaumjau"));
/*
$categoriesDao->insert([
   "name"=>"snacks",
   "number_of_products"=> 1,
])*/
//print_r($categoriesDao->getNumOfProductsByName("drinks"));


/*$image = fopen(  "../../../frontend/img/fruite-item-5.jpg", "rb");

$productsDao->insert([
   "name" => "Grapes",
   "quantity" => 41,
   "price"=>12,
   "image" => $image,
   "category_id" => 2
]
)*/

/*$product = $productsDao->getById(0); 
echo "<pre>";
print_r($product);
echo "</pre>";*/

/*
$product_ordersDao->insert([
   "price"=>1.35,
   "quantity"=>2,
   "order_id"=>0,
   "product_id"=>0
])
*/

print_r($product_ordersDao->getByOrderId(1))


?>
