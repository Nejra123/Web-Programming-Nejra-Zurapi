<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Enable CORS - MUST be before any other output
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authentication, Authorization, X-Requested-With");
header("Access-Control-Max-Age: 3600");

// Handle preflight OPTIONS requests FIRST
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require 'vendor/autoload.php';
require_once __DIR__ . '/rest/config.php';

// Load all service and DAO classes
require_once __DIR__ . '/rest/dao/baseDAO.php';
require_once __DIR__ . '/rest/dao/authDao.php';
require_once __DIR__ . '/rest/dao/UserDao.php';
require_once __DIR__ . '/rest/dao/ProductsDao.php';
require_once __DIR__ . '/rest/dao/OrderDao.php';
//require_once __DIR__ . '/rest/dao/Product_OrdersDao.php';
require_once __DIR__ . '/rest/dao/MessagesDao.php';
require_once __DIR__ . '/rest/dao/CategoriesDao.php';

require_once __DIR__ . '/rest/services/AuthService.php';
require_once __DIR__ . '/rest/services/UserService.php';
require_once __DIR__ . '/rest/services/ProductService.php';
require_once __DIR__ . '/rest/services/OrderService.php';
//require_once __DIR__ . '/rest/services/Product_OrderService.php';
require_once __DIR__ . '/rest/services/MessageService.php';
require_once __DIR__ . '/rest/services/CategorieService.php';

require_once __DIR__ . '/middleware/AuthMiddleware.php';

// Database connection
Flight::register('db', 'PDO', array(
    'mysql:host=' . Config::DB_HOST() . ';dbname=' . Config::DB_NAME(),
    Config::DB_USER(),
    Config::DB_PASSWORD()
), function($db) {
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
});

// Register services
Flight::register('auth_service', 'AuthService');
Flight::register('userService', 'UserService');
Flight::register('productService', 'ProductService');
Flight::register('orderService', 'OrderService');
//Flight::register('product_orderService', 'Product_OrderService');
Flight::register('messageService', 'MessageService');
Flight::register('categorieService', 'CategorieService');
Flight::register('auth_middleware', 'AuthMiddleware');

// Include roles
require_once __DIR__ . '/data/roles.php';
// Test endpoint - add this before Flight::start()
Flight::route('POST /test/product_order', function() {
    try {
        $data = [
            'order_id' => 1,
            'product_id' => 1,
            'quantity' => 1,
            'price' => 10.00
        ];
        
        error_log("TEST - Creating product order with data: " . json_encode($data));
        
        $result = Flight::product_orderService()->create($data);
        
        error_log("TEST - Result: " . json_encode($result));
        
        Flight::json(['success' => true, 'result' => $result]);
        
    } catch (Exception $e) {
        error_log("TEST - Exception: " . $e->getMessage());
        Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});
// Authentication middleware
Flight::before('start', function() {
    $url = Flight::request()->url;
    
    // Skip auth for these routes
    if (
        strpos($url, '/auth/login') === 0 ||
        strpos($url, '/auth/register') === 0 ||
        (strpos($url, '/products') === 0 && Flight::request()->method === 'GET') ||
        strpos($url, '/docs') === 0 ||
        strpos($url, '/swagger') === 0
    ) {
        return; 
    } 

    try {
        $token = Flight::request()->getHeader("Authentication");
        
        if (empty($token)) {
            Flight::halt(401, json_encode([
                'success' => false,
                'error' => 'No authentication token provided'
            ]));
            return;
        }
        
        $user_data = Flight::auth_middleware()->verifyToken($token);
        
        // Set both token and user data
        Flight::set('jwt_token', $token);
        Flight::set('user', $user_data);
        
        // Log for debugging (remove in production)
        error_log("User authenticated: " . json_encode($user_data));

    } catch (\Exception $e) {
        Flight::halt(401, json_encode([
            'success' => false,
            'error' => $e->getMessage()
        ]));
    }
}); 

// Include all route files
require_once __DIR__ . '/rest/routes/AuthRoutes.php';
require_once __DIR__ . '/rest/routes/ProductRoutes.php';
require_once __DIR__ . '/rest/routes/OrderRoutes.php';
//require_once __DIR__ . '/rest/routes/Product_OrderRoutes.php';
require_once __DIR__ . '/rest/routes/MessagesRoutes.php';
require_once __DIR__ . '/rest/routes/UserRoutes.php';
require_once __DIR__ . '/rest/routes/CategorieRoutes.php';

// Start Flight
Flight::start();
?>