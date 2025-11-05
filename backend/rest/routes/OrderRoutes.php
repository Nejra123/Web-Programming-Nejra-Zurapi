<?php
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 * path="/orders",
 * tags={"orders"},
 * summary="Get all orders",
 * @OA\Response(
 * response=200,
 * description="Returns a list of all orders",
 * )
 * )
 */
Flight::route('GET /orders', function() {
    Flight::json(Flight::orderService()->getAll());
});

/**
 * @OA\Get(
 * path="/orders/{customer_id}",
 * tags={"orders"},
 * summary="Get orders by Customer ID",
 * @OA\Parameter(
 * name="customer_id",
 * in="path",
 * required=true,
 * description="ID of the customer",
 * @OA\Schema(type="integer", example=5)
 * ),
 * @OA\Response(
 * response=200,
 * description="Returns orders belonging to the specified customer ID"
 * ),
 * )
 */
Flight::route('GET /orders/@customer_id', function($customer_id) {
    Flight::json(Flight::orderService()->getByUserId($customer_id));
});

/**
 * @OA\Get(
 * path="/orders/date/{target_date}",
 * tags={"orders"},
 * summary="Get orders by date",
 * @OA\Parameter(
 * name="target_date",
 * in="path",
 * required=true,
 * description="Date in YYYY-MM-DD format",
 * @OA\Schema(type="string", format="date", example="2025-10-27")
 * ),
 * @OA\Response(
 * response=200,
 * description="Returns orders placed on the specified date"
 * )
 * )
 */
Flight::route('GET /orders/date/@target_date', function($target_date) {
    $clean_date = trim($target_date);
    Flight::json(Flight::orderService()->getByDate($clean_date));
});


/**
 * @OA\Post(
 * path="/orders",
 * tags={"orders"},
 * summary="Create a new order",
 * @OA\RequestBody(
 * required=true,
 * description="Order details",
 * @OA\JsonContent(
 * required={"date", "time", "address", "amount","customer_id"},
 * @OA\Property(property="date", type="date", description="Date in YYYY-MM-DD format"),
 * @OA\Property(property="time", type="time", description="Time in hh-mm-ss format"),
 * @OA\Property(property="address", type="string", description="Customers address"),
 * @OA\Property(property="amount", type="decimal", example=45.99),
 * @OA\Property(property="customer_id", type="integer", example=5)
 * )
 * ),
 * @OA\Response(
 * response=200,
 * description="Order successfully created",
 * )
 * )
 */
Flight::route('POST /orders', function() {
    $data = Flight::request()->data->getData();
    Flight::json(Flight::orderService()->create($data));
});
?>