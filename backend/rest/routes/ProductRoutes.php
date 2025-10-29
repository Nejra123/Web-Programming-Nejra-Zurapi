<?php
Flight::route('POST /products', function() {
    
    $data = Flight::request()->data->getData();
    

    if (isset($data['image']) && !empty($data['image'])) {
       
        if (strpos($data['image'], 'base64,') !== false) {
            $base64 = explode('base64,', $data['image'])[1];
            $data['image'] = base64_decode($base64);
        }
        
    }
    
    $result = Flight::productService()->create($data);
    Flight::json($result, 201);
});


Flight::route('GET /products/@id', function($id) {
    $product = Flight::productService()->getById($id);
    
    if (isset($product['image'])) {
        unset($product['image']);
    }
    
    Flight::json($product);
});

Flight::route('GET /products/quantity/@id', function($id) {
    Flight::json( Flight::productService()->getQuantity($id));
   
});
?>