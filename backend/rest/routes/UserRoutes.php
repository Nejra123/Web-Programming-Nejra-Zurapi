<?php

/**
 * @OA\Get(
 * path="/user/{id}",
 * tags={"user"},
 * summary="Get user details by ID",
 *  security={
    *         {"ApiKey": {}}
    *     },
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="ID of the user",
 * @OA\Schema(type="integer", example=1)
 * ),
 * @OA\Response(
 * response=200,
 * description="Returns the user by id"
 * )
 * )
 */
Flight::route('GET /user/@id', function($id){
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);

    Flight::json(Flight::userService()->getById($id));
});

/**
 * @OA\Get(
 * path="/user/email/{email}",
 * tags={"user"},
 * summary="Get user details by email",
 *  security={
    *         {"ApiKey": {}}
    *     },
 * @OA\Parameter(
 * name="email",
 * in="path",
 * required=true,
 * description="Email address of the user",
 * @OA\Schema(type="string", example="nejrazurapi@gmail.com")
 * ),
 * @OA\Response(
 * response=200,
 * description="Returns the user by email"
 * )
 * )
 */
Flight::route("GET /user/email/@email", function ($email) {
      Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::userService()->getByEmail($email));
});

/**
 * @OA\Put(
 * path="/user/{id}",
 * tags={"user"},
 * summary="Change a users password",
 *  security={
    *         {"ApiKey": {}}
    *     },
 * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="ID of the user to update",
 * @OA\Schema(type="integer", example=1)
 * ),
 * @OA\RequestBody(
 * required=true,
 * @OA\JsonContent(
 * required={"password"},
 * @OA\Property(property="password", type="string",  example="nejra123")
 * )
 * ),
 * @OA\Response(
 * response=200,
 * description="Password successfully changed"
 * )
 * )
 */
Flight::route("PUT /user/@id", function ($id) {
      Flight::auth_middleware()->authorizeRoles([Roles::ADMIN, Roles::USER]);
    $data = Flight::request()->data; 
    $new_password = $data['password']; 
    
    Flight::json(Flight::userService()->changePassword($id, $new_password));
});



/**
 * @OA\Delete(
 * path="/user/delete/{id}",
 * tags ={"user"},
 * summary = "Deleting the user",
 *  security={
    *         {"ApiKey": {}}
    *     },
 * * @OA\Parameter(
 * name="id",
 * in="path",
 * required=true,
 * description="ID of the user",
 * @OA\Schema(type="integer", example=1)),
 * @OA\RequestBody(
 * required=true,
 * ),
 * @OA\Response(
 * response=200,
 * description="User deleted.")
 * )
 */
Flight::route("DELETE /user/delete/@id", function ($id)
 { 
    Flight::auth_middleware()->authorizeRole(Roles::ADMIN);
    Flight::json(Flight::userService()->removeUser($id)); });


?>