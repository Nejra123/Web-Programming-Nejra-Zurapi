<?php
// 1. Require necessary files and instantiate your DAO
require_once 'baseDAO.php'; 
require_once 'ProductDao.php'; // Assuming you have a ProductDao class

$productDao = new ProductsDao();

// 2. Get the ID of the product from the URL (e.g., ?id=1)
// Default to 1 if no ID is provided, but use a robust method to get the ID.
$product_id = $_GET['id'] ?? 1; 

// 3. Fetch the product record from the database
// Assuming getProductById is a method in your ProductDao
$product = $productDao->getProductById($product_id); 

if (!$product || !isset($product['image'])) {
    // Check if the record exists or if the 'image' column is empty
    header("HTTP/1.0 404 Not Found");
    echo "Image not found.";
    exit;
}

$image_data = $product['image'];

// 4. CRITICAL STEP: Set the Content-Type header
// This tells the browser to interpret the data as an image (e.g., PNG).
// You must know the type of image you stored (png, jpeg, gif).
header("Content-Type: image/png"); 

// 5. Send the Content-Length header (optional, but good practice)
header("Content-Length: " . strlen($image_data));

// 6. Output the raw binary data
echo $image_data; 
exit;
?>