<?php

Flight::route('GET /product_orders/order/@order_id', function($order_id) {
    Flight::json(Flight::product_orderService()->getByOrderId($order_id));
});


Flight::route('GET /product_orders', function() {
    Flight::json(Flight::product_orderService()->getAll());
});

Flight::route('GET /product_orders/@id', function($id) {
    Flight::json(Flight::product_orderService()->getById($id));
});
?>