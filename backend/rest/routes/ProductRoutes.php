<?php

/**
 * @OA\Post(
 * path="/products",
 * tags={"products"},
 * summary="Create a new product",
 * security={{"ApiKey": {}}},
 * @OA\RequestBody(
 *     required=true,
 *     description="Product details",
 *     @OA\JsonContent(
 *         required={"name", "category_id", "quantity", "price"},
 *         @OA\Property(property="name", type="string", example="banana"),
 *         @OA\Property(property="price", type="number", example=12.50),
 *         @OA\Property(property="category_id", type="integer", example=2),
 *         @OA\Property(property="quantity", type="integer", example=100),
 *         @OA\Property(property="description", type="string", example="Rich and robust flavor."),
 *         @OA\Property(property="image", type="string", example="frontend/img/banana.jpg")
 *     )
 * ),
 * @OA\Response(response=200, description="Product successfully created")
 * )
 */
Flight::route('POST /products', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    
    $data = Flight::request()->data->getData();
    
    $result = Flight::productService()->create($data);
    Flight::json(['success' => true, 'data' => $result]);
});

/**
 * @OA\Get(
 * path="/products",
 * tags={"products"},
 * summary="Get a list of all products",
 * @OA\Response(response=200, description="A list of all products")
 * )
 */
Flight::route('GET /products', function() {
    $products = Flight::productService()->getAll();
    
    Flight::json($products);
});

/**
 * @OA\Get(
 * path="/products/{id}",
 * tags={"products"},
 * summary="Get product details by ID",
 * security={{"ApiKey": {}}},
 * @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     description="ID of the product",
 *     @OA\Schema(type="integer", example=1)
 * ),
 * @OA\Response(response=200, description="Returns product details")
 * )
 */
Flight::route('GET /products/@id', function($id) {
    // No auth requirement for viewing single product
    $product = Flight::productService()->getById($id);
    Flight::json($product);
});

/**
 * @OA\Patch(
 * path="/products/{id}",
 * tags={"products"},
 * summary="Update a product",
 * security={{"ApiKey": {}}},
 * @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     @OA\Schema(type="integer", example=1)
 * ),
 * @OA\RequestBody(
 *     required=true,
 *     @OA\JsonContent(
 *         @OA\Property(property="name", type="string", example="Updated Product"),
 *         @OA\Property(property="price", type="number", example=15.99),
 *         @OA\Property(property="quantity", type="integer", example=50),
 *         @OA\Property(property="category_id", type="integer", example=2),
 *         @OA\Property(property="image", type="string", example="frontend/img/updated.jpg")
 *     )
 * ),
 * @OA\Response(response=200, description="Product updated successfully")
 * )
 */
Flight::route('PATCH /products/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    
    $data = Flight::request()->data->getData();
    $result = Flight::productService()->update($id, $data);
    Flight::json(['success' => true, 'data' => $result]);
});

/**
 * @OA\Delete(
 * path="/products/{id}",
 * tags={"products"},
 * summary="Delete a product by ID",
 * security={{"ApiKey": {}}},
 * @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     @OA\Schema(type="integer", example=15)
 * ),
 * @OA\Response(response=200, description="Product deleted successfully")
 * )
 */
Flight::route('DELETE /products/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $result = Flight::productService()->deleteProduct($id);
    Flight::json(['success' => true, 'data' => $result]);
});

/**
 * @OA\Get(
 * path="/products/quantity/{id}",
 * tags={"products"},
 * summary="Get quantity by product ID",
 * security={{"ApiKey": {}}},
 * @OA\Parameter(
 *     name="id",
 *     in="path",
 *     required=true,
 *     @OA\Schema(type="integer", example=15)
 * ),
 * @OA\Response(response=200, description="Returns the current quantity")
 * )
 */
Flight::route('GET /products/quantity/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::productService()->getQuantity($id));
});
?>
