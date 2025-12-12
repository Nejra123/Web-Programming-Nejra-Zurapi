let ProductService = {
    getAllProducts: function(callback) {
        console.log("Fetching products from:", Constants.PROJECT_BASE_URL + "products");
        
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "products",
            type: "GET",
            dataType: "json",
            success: function(data) {
                console.log("Products received:", data);
                if (callback) callback(data);
            },
            error: function(jqXHR, status, error) {
                console.error('Error fetching products:', {
                    status: jqXHR.status,
                    response: jqXHR.responseJSON,
                    text: jqXHR.responseText
                });
                toastr.error('Failed to load products');
            }
        });
    },
    
    getProductById: function(id, callback) {
        console.log("Fetching product:", id);
        
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "products/" + id,
            type: "GET",
            dataType: "json",
            success: function(data) {
                console.log("Product received:", data);
                if (callback) callback(data);
            },
            error: function(jqXHR, status, error) {
                console.error('Error fetching product:', error);
                toastr.error('Failed to load product');
            }
        });
    },
    
    addProduct: function(product, callback) {
        console.log("Adding product:", product);
        
        $.blockUI({ message: '<h3>Adding Product...</h3>' });
        RestClient.post('products', product, function(response) {
            setTimeout(function() {
                $.unblockUI();
                location.reload();
            }, 1000);
            console.log("Product added:", response);
            if (callback) callback(response);
        }, function(jqXHR) {
            setTimeout(function() {
                $.unblockUI();
                location.reload();
            }, 1000);
            console.error("Error adding product:", jqXHR);
            toastr.error(jqXHR.responseJSON?.message || jqXHR.responseJSON?.error || 'Failed to add product');
        });
    },
    
    deleteProduct: function(id, callback) {
        console.log("Product ID:", id);
        console.log("Full URL:", Constants.PROJECT_BASE_URL + "products/" + id);
        
        const token = localStorage.getItem("user_token");
        console.log("Token exists:", !!token);
        $.blockUI({ message: '<h3>Deleting Product...</h3>' });
       
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "products/" + id,
            type: "DELETE",
            dataType: "json",
            contentType: "application/json",
            beforeSend: function (xhr) {
                if (token) {
                    xhr.setRequestHeader("Authentication", token);
                    console.log("Authentication header set");
                }
            },
            success: function(response, textStatus, jqXHR) {
                setTimeout(function() {
                $.unblockUI();
                location.reload();
            }, 1000);
                console.log("Status:", jqXHR.status);
                console.log("Response:", response);
                console.log("Text Status:", textStatus);
                
               
                if (jqXHR.status === 200 && response) {
                    console.log("Real delete response received");
                    if (callback) callback(response);
                } else {
                    console.error("Fake or cached response!");
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                setTimeout(function() {
                $.unblockUI();
                location.reload();
            }, 1000);
                console.error("Status:", jqXHR.status);
                console.error("Status Text:", textStatus);
                console.error("Error:", errorThrown);
                console.error("Response:", jqXHR.responseJSON);
                console.error("Response Text:", jqXHR.responseText);
                
                toastr.error('Failed to delete product');
            },
            complete: function(jqXHR, textStatus) {
                console.log("Status:", textStatus);
                console.log("Headers:", jqXHR.getAllResponseHeaders());
            }
        });
    }
};