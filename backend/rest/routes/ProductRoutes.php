<?php

/**
 * @OA\Post(
 * path="/products",
 * tags={"products"},
 * summary="Create a new product",
 *  security={
    *         {"ApiKey": {}}
    *     },
 * @OA\RequestBody(
 * required=true,
 * description="Product details",
 * @OA\JsonContent(
 * required={ "name", "category_id", "quantity", "price"},
 * @OA\Property(property="name", type="string", example="banana"),
 * @OA\Property(property="price", type="number", example=12.50),
 * @OA\Property(property="category_id", type="integer", example=1),
 * @OA\Property(property="description", type="string", example="Rich and robust flavor."),
 * @OA\Property(property="image", type="string", format="binary", description="Image file data (Base64).")
 * ),
 * ),
 * @OA\Response(
 * response=200,
 * description="Product successfully created",
 * )
 * )
 */
Flight::route('POST /products', function() {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    
    $data = Flight::request()->data->getData();
    
    // Logic to handle Base64 image decoding
    if (isset($data['image']) && !empty($data['image'])) {
        
        if (strpos($data['image'], 'base64,') !== false) {
            $base64 = explode('base64,', $data['image'])[1];
            $data['image'] = base64_decode($base64);
        }
        
    }
    
    $result = Flight::productService()->create($data);
    Flight::json($result);
});

/**
 * @OA\Get(
 * path="/products",
 * tags={"products"},
 * summary="Get a list of all products",
 * security={
 * {"ApiKey": {}}
 * },
* @OA\Response(
 * response=200,
 * description="A list of all products",
 * )
 * )
 */
Flight::route('GET /products', function() {

    Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    $products = Flight::productService()->getAll();
    

    $safe_products = array_map(function($product) {
        if (isset($product['image'])) {
            unset($product['image']);
        }
        return $product;
    }, $products);
    
    Flight::json($safe_products);
    
});

/**
 * @OA\Get(
 * path="/products/{id}",
 * tags={"products"},
 * summary="Get product details by ID",
 *  security={
    *         {"ApiKey": {}}
    *     },
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="ID of the product",
 * @OA\Schema(type="integer", example=501)
 * ),
 * @OA\Response(
 * response=200,
 * description="Returns product details",

 * ),
 * )
 */
Flight::route('GET /products/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    $product = Flight::productService()->getById($id);
    
    // Logic to unset image data before sending response
    if (isset($product['image'])) {
        unset($product['image']);
    }
    
    Flight::json($product);
});

/**
 * @OA\Get(
 * path="/products/quantity/{id}",
 * tags={"products"},
 * summary="Get quantity by product ID",
 *  security={
    *         {"ApiKey": {}}
    *     },
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="ID of the product",
 * @OA\Schema(type="integer", example=15)
 * ),
 * @OA\Response(
 * response=200,
 * description="Returns the current quantity",

 * )
 * )
 */
Flight::route('GET /products/quantity/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json( Flight::productService()->getQuantity($id));
    
});

/**
 * @OA\Delete(
 * path="/products/delete/{id}",
 * tags={"products"},
 * summary="Delete a product by given ID",
 *  security={
    *         {"ApiKey": {}}
    *     },
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="ID of the product",
 * @OA\Schema(type="integer", example=15)
 * ),
 *  @OA\Response(
 * response=200,
 * description="Returns the current quantity",
 * )
 * )
 */

Flight::route('DELETE /products/delete/@id', function($id) {
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::productService()->deleteProduct($id));
}
);
?>