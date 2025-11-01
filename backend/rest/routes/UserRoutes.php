<?php

/**
 * @OA\Get(
 * path="/user/{id}",
 * tags={"user"},
 * summary="Get user details by ID",
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
    Flight::json(Flight::userService()->getById($id));
});

/**
 * @OA\Get(
 * path="/user/email/{email}",
 * tags={"user"},
 * summary="Get user details by email",
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
    $decodedEmail = urldecode($email);
    Flight::json(Flight::userService()->getByEmail($decodedEmail));
});

/**
 * @OA\Put(
 * path="/user/{id}",
 * tags={"user"},
 * summary="Change a users password",
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
    $data = Flight::request()->data; 
    $new_password = $data['password']; 
    
    Flight::json(Flight::userService()->changePassword($id, $new_password));
});


/**
 * @OA\Post(
 * path="/user/register",
 * tags={"user"},
 * summary="Register a new user",
 * @OA\RequestBody(
 * required=true,
 * @OA\JsonContent(
 * required={"name", "surname", "email" ,"password", "username"},
 * @OA\Property(property="name", type="string", example="Nejra"),
 * @OA\Property(property="surname", type="string", example="Zurapi"),
 * @OA\Property(property="email", type="string",  example="nejrazurapi@gmail.com"),
 * @OA\Property(property="password", type="string", example="nerja123"),
 * @OA\Property(property="username", type="string", example="nejra123"),

 * )
 * ),
 * @OA\Response(
 * response=200,
 * description="User successfully registered",
 * )
 * )
 */
Flight::route('POST /user/register', function () { 
    $data = Flight::request()->data;
    Flight::json(Flight::userService()->register( $data));
});

/**
 * @OA\Post(
 * path="/user/login",
 * tags={"user"},
 * summary="Log in user and retrieve authentication token",
 * @OA\RequestBody(
 * required=true,
 * @OA\JsonContent(
 * required={"email", "password"},
 * @OA\Property(property="email", type="string", example="nejrazurapi@gmail.com"),
 * @OA\Property(property="password", type="string",  example="nejra123")
 * )
 * ),
 * @OA\Response(
 * response=200,
 * description="Login successful",
 *
 * ) )
 */
Flight::route("POST /user/login", function(){
    $data = Flight::request()->data;
    $password = $data["password"];
    $email = $data["email"];
    Flight::json(Flight::userService()->login($email, $password));
});

/**
 * @OA\Delete(
 * path="/user/delete/{id}",
 * tags ={"user"},
 * summary = "Deleting the user",
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
 { Flight::json(Flight::userService()->removeUser($id)); });


?>