let OrderService = {
    createOrder: function(orderData, cartItems, callback, errorCallback) {
        const user = UserService.getCurrentUser();
        
        if (!user) {
            toastr.error("Please login to place an order");
            window.location.hash = "#profile";
            if (errorCallback) errorCallback();
            return;
        }

        console.log("Order data:", orderData);
        console.log("Cart items:", cartItems);
        
        if (!cartItems || cartItems.length === 0) {
            toastr.error("Cart is empty");
            if (errorCallback) errorCallback();
            return;
        }
        

        const total = cartItems.reduce((sum, item) => {
            return sum + (parseFloat(item.product.price) * item.quantity);
        }, 0);
        
    
        const items = cartItems.map(item => {
            return {
                product_id: item.product.ID || item.product.id,
                name: item.product.name,
                quantity: item.quantity,
                price: parseFloat(item.product.price)
            };
        });
        
        const now = new Date();
        const date = now.toISOString().split('T')[0];
        const time = now.toTimeString().split(' ')[0];
        
        const data = {
            address: orderData.address,
            amount: parseFloat(total.toFixed(2)),
            items: JSON.stringify(items),
            date: date,
            time: time
        };
        

        console.log("Order data:", data);
        $.blockUI({ message: '<h3>Processing Order...</h3>' });

        RestClient.post('orders', data, function(response) {
            setTimeout(function() {
                $.unblockUI();
                location.reload();
            }, 1000);
            console.log("Response:", response);
            
            //clear cart
            cart = [];
            saveCart();
            
            if (callback) callback(response);
        }, function(jqXHR) {
            setTimeout(function() {
                $.unblockUI();
                location.reload();
            }, 1000);
            console.error("Status:", jqXHR.status);
            console.error("Response:", jqXHR.responseJSON);
            console.error("Text:", jqXHR.responseText);
            
            const errorMsg = jqXHR.responseJSON?.error || 
                           jqXHR.responseJSON?.message || 
                           'Order Created';
            console.error(errorMsg);
            
            if (errorCallback) errorCallback(jqXHR);
        });
    },
    
    getUserOrders: function(userId, callback) {
        console.log("Fetching orders for user:", userId);
        
        RestClient.get('orders/user/' + userId, function(data) {
            console.log("Orders received:", data);
            if (callback) callback(data);
        }, function(jqXHR) {
            console.error("Error fetching orders:", jqXHR);
        });
    }
};