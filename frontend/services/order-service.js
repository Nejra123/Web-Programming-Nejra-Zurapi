let OrderService = {
    createOrder: function(orderData, cartItems, callback) {
        const user = UserService.getCurrentUser();
        
        if (!user) {
            toastr.error("Please login to place an order");
            window.location.hash = "#profile";
            return;
        }
        
        console.log("=== STARTING ORDER CREATION ===");
        console.log("Order data:", orderData);
        console.log("Cart items:", cartItems);
        
        if (!cartItems || cartItems.length === 0) {
            toastr.error("Cart is empty");
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
        const date = now.toISOString().split('T')[0]; // YYYY-MM-DD
        const time = now.toTimeString().split(' ')[0]; // HH:MM:SS
        
        const data = {
            address: orderData.address,
            amount: parseFloat(total.toFixed(2)),
            items: JSON.stringify(items), 
            date: date,
            time: time
        };
        
        console.log("=== SENDING ORDER TO BACKEND ===");
        console.log("Order data:", data);
        
        RestClient.post('orders', data, function(response) {
            console.log("=== ORDER CREATED SUCCESSFULLY ===");
            console.log("Response:", response);
            
            toastr.success("Order placed successfully!");
            
            //clear cart
            cart = [];
            saveCart();
            
            if (callback) callback(response);
        }, function(jqXHR) {
            console.error("=== ORDER CREATION FAILED ===");
            console.error("Status:", jqXHR.status);
            console.error("Response:", jqXHR.responseJSON);
            console.error("Text:", jqXHR.responseText);
            
            const errorMsg = jqXHR.responseJSON?.error || 
                           jqXHR.responseJSON?.message || 
                           'Failed to create order';
            toastr.error("Order Placed");
        });
    },
    
    getUserOrders: function(userId, callback) {
        console.log("Fetching orders for user:", userId);
        
        RestClient.get('orders/user/' + userId, function(data) {
            console.log("Orders received:", data);
            if (callback) callback(data);
        }, function(jqXHR) {
            console.error("Error fetching orders:", jqXHR);
            toastr.error('Failed to load orders');
        });
    }
};