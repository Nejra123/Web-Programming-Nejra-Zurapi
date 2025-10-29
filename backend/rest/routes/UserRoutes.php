<?php

Flight::route('GET /user/@id', function($id){
    Flight::json(Flight::userService()->getById($id));
});

Flight::route("GET /user/email/@email", function ($email) {
    $decodedEmail = urldecode($email);
    Flight::json(Flight::userService()->getByEmail($decodedEmail));
});

Flight::route("PUT /user/@id", function ($id) {
    $data = Flight::request()->data; 
    $new_password = $data['password']; 
    
    Flight::json(Flight::userService()->changePassword($id, $new_password));
});


Flight::route('POST /user/register', function () { 
    $data = Flight::request()->data;
    Flight::json(Flight::userService()->register( $data));
 });

 Flight::route("POST /user/login", function(){
    $data = Flight::request()->data;
    $password = $data["password"];
    $email = $data["email"];
    Flight::json(Flight::userService()->login($email, $password));
 })
?>