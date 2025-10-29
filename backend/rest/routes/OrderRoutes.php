<?php

Flight::route('GET /orders', function() {
    Flight::json(Flight::orderService()->getAll());
});

Flight::route('GET /orders/@customer_id', function($customer_id) {
    Flight::json(Flight::orderService()->getByUserId($customer_id));
});

Flight::route('GET /orders/date/@target_date', function($target_date) {
    $clean_date = trim($target_date);
    Flight::json(Flight::orderService()->getByDate($clean_date));
});


Flight::route('POST /orders', function() {
  
    $data = Flight::request()->data->getData();
    
    Flight::json(Flight::orderService()->create($data));
});
?>