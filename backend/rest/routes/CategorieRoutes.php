<?php
/**
 * @OA\Get(
 *     path="/categories/",
 *     tags={"categories"},
 *     summary="Get categories",
 *    
 *     @OA\Response(
 *         response=200,
 *         description="Returns all categories"
 *     )
 * )
 */ 
Flight::route('GET /categories', function() {
    Flight::json(Flight::categorieService()->getAll());
});

/**
 * @OA\Get(
 *     path="/categories/{id}",
 *     tags={"categories"},
 *     summary="Get category by id",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         description="ID of the category",
 *         @OA\Schema(type="integer", example=1)
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns the category with the given id"
 *     )
 * )
 */
Flight::route('GET /categories/@id', function($id) {
    Flight::json(Flight::categorieService()->getById($id));
});

/**
 * @OA\Get(
 *     path="/categories/name/{name}",
 *     tags={"categories"},
 *     summary="Get number of products by category name",
 *     @OA\Parameter(
 *         name="name",
 *         in="path",
 *         required=true,
 *         description="Name of the category",
 *         @OA\Schema(type="string", example="Drinks")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Returns number of products for the given category name"
 *     )
 * )
 */
Flight::route('GET /categories/name/@name', function($name) {
    Flight::json(Flight::categorieService()->getNumOfProductsByName($name));
});
?>
