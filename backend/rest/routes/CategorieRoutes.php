<?php

Flight::route('GET /categories', function() {
    Flight::json(Flight::categorieService()->getAll());
});


Flight::route('GET /categories/@id', function($id) {
    Flight::json(Flight::categorieService()->getById($id));
});


Flight::route('GET /categories/name/@name', function($name) {
    Flight::json(Flight::categorieService()->getNumOfProductsByName($name));
});
?>