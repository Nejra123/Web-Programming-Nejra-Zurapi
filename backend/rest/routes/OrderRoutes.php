<?php
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 * path="/orders",
 * tags={"orders"},
 * summary="Get all orders",
 * security={{"ApiKey": {}}},
 * @OA\Response(response=200, description="Returns a list of all orders")
 * )
 */
Flight::route('GET /orders', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $orders = Flight::orderService()->getAll();
    
    foreach ($orders as &$order) {
        if (isset($order['items']) && is_string($order['items'])) {
            $order['items'] = json_decode($order['items'], true);
        }
    }
    
    Flight::json($orders);
});

/**
 * @OA\Get(
 * path="/orders/{customer_id}",
 * tags={"orders"},
 * summary="Get orders by Customer ID",
 * security={{"ApiKey": {}}},
 * @OA\Parameter(
 *     name="customer_id",
 *     in="path",
 *     required=true,
 *     description="ID of the customer",
 *     @OA\Schema(type="integer", example=5)
 * ),
 * @OA\Response(response=200, description="Returns orders belonging to the specified customer ID")
 * )
 */
Flight::route('GET /orders/@customer_id', function($customer_id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $orders = Flight::orderService()->getByUserId($customer_id);
    
    foreach ($orders as &$order) {
        if (isset($order['items']) && is_string($order['items'])) {
            $order['items'] = json_decode($order['items'], true);
        }
    }
    
    Flight::json($orders);
});

/**
 * @OA\Get(
 * path="/orders/date/{target_date}",
 * tags={"orders"},
 * summary="Get orders by date",
 * security={{"ApiKey": {}}},
 * @OA\Parameter(
 *     name="target_date",
 *     in="path",
 *     required=true,
 *     description="Date in YYYY-MM-DD format",
 *     @OA\Schema(type="string", format="date", example="2025-10-27")
 * ),
 * @OA\Response(response=200, description="Returns orders placed on the specified date")
 * )
 */
Flight::route('GET /orders/date/@target_date', function($target_date) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $clean_date = trim($target_date);
    $orders = Flight::orderService()->getByDate($clean_date);
    
    foreach ($orders as &$order) {
        if (isset($order['items']) && is_string($order['items'])) {
            $order['items'] = json_decode($order['items'], true);
        }
    }
    
    Flight::json($orders);
});

/**
 * @OA\Post(
 * path="/orders",
 * tags={"orders"},
 * summary="Create a new order",
 * security={{"ApiKey": {}}},
 * @OA\RequestBody(
 *     required=true,
 *     description="Order details",
 *     @OA\JsonContent(
 *         required={"address", "amount", "items"},
 *         @OA\Property(property="address", type="string", example="123 Main St, Sarajevo"),
 *         @OA\Property(property="amount", type="number", format="float", example=45.99),
 *         @OA\Property(
 *             property="items",
 *             type="array",
 *             description="Array of order items",
 *             @OA\Items(
 *                 type="object",
 *                 @OA\Property(property="product_id", type="integer", example=1),
 *                 @OA\Property(property="name", type="string", example="Apple"),
 *                 @OA\Property(property="quantity", type="integer", example=2),
 *                 @OA\Property(property="price", type="number", format="float", example=5.00)
 *             )
 *         ),
 *         @OA\Property(property="date", type="string", format="date", example="2025-01-15"),
 *         @OA\Property(property="time", type="string", format="time", example="14:30:00")
 *     )
 * ),
 * @OA\Response(response=200, description="Order successfully created")
 * )
 */
Flight::route('POST /orders', function() {
    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    
    try {
        $data = Flight::request()->data->getData();
        
        error_log("=== POST /orders START ===");
        error_log("POST /orders - Received data: " . json_encode($data));
        
        $user = Flight::get('user');
        error_log("POST /orders - User from token: " . json_encode($user));
        
        if (!$user) {
            error_log("POST /orders - ERROR: User not authenticated");
            Flight::json(['success' => false, 'error' => 'User not authenticated'], 401);
            return;
        }
        
        $customerId = $user['ID'] ?? $user['id'] ?? null;
        
        if (!$customerId) {
            error_log("POST /orders - ERROR: Could not extract customer_id from user");
            Flight::json(['success' => false, 'error' => 'Could not determine customer ID'], 400);
            return;
        }
        
        if (!isset($data['items']) || empty($data['items'])) {
            error_log("POST /orders - ERROR: No items provided");
            Flight::json(['success' => false, 'error' => 'Order must contain items'], 400);
            return;
        }
        
        $data['customer_id'] = intval($customerId);
        $data['amount'] = floatval($data['amount']);
        
        if (is_array($data['items'])) {
            $data['items'] = json_encode($data['items']);
        }
        
        error_log("POST /orders - Final data: " . json_encode($data));
        
        $result = Flight::orderService()->create($data);
        
        error_log("POST /orders - Order created result: " . json_encode($result));
        
        $orderId = $result['ID'] ?? $result['id'] ?? null;
        
        if (!$orderId) {
            error_log("POST /orders - ERROR: Could not get order ID from result");
            return;
        }
        
        error_log("POST /orders - Order ID: $orderId");
        error_log("=== POST /orders SUCCESS ===");
        
        if (isset($result['items']) && is_string($result['items'])) {
            $result['items'] = json_decode($result['items'], true);
        }
        
        Flight::json([
            'success' => true,
            'data' => $result,
            'id' => $orderId,
            'ID' => $orderId,
            'order_id' => $orderId
        ]);
        
    } catch (Exception $e) {
        error_log("POST /orders - EXCEPTION: " . $e->getMessage());
        error_log("POST /orders - Stack trace: " . $e->getTraceAsString());
        Flight::json(['success' => false, 'error' => $e->getMessage()], 500);
    }
});
?>