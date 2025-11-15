<?php
use OpenApi\Annotations as OA;

/**
 * @OA\Get(
 * path="/product_orders/order/{order_id}",
 * tags={"product orders"},
 * summary="Get product orders by Order ID",
 *  security={
    *         {"ApiKey": {}}
    *     },
 * @OA\Parameter(
 * name="order_id",
 * in="path",
 * required=true,
 * @OA\Schema(type="integer", example=101)
 * ),
 * @OA\Response(
 * response=200,
 * description="Returns product by ID"
 * ),
 * )
 */
Flight::route('GET /product_orders/order/@order_id', function($order_id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::product_orderService()->getByOrderId($order_id));
});

/**
 * @OA\Get(
 * path="/product_orders",
 * tags={"product orders"},
 * summary="Get all product orders (items)",
 *  security={
    *         {"ApiKey": {}}
    *     },
 * @OA\Response(
 * response=200,
 * description="Returns a list of all product order items",
 * )
 * )
 */
Flight::route('GET /product_orders', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::product_orderService()->getAll());
});

/**
 * @OA\Get(
 * path="/product_orders/{id}",
 * tags={"product orders"},
 * summary="Get product order by ID",
 *  security={
    *         {"ApiKey": {}}
    *     },
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="ID of the product order item",
 * @OA\Schema(type="integer", example=201)
 * ),
 * @OA\Response(
 * response=200,
 * description="Returns the product order item with the given ID"
 * ),
 * )
 */
Flight::route('GET /product_orders/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::product_orderService()->getById($id));
});
?>