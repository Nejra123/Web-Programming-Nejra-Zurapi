let CheckoutService = {
    init: function() {
        console.log("Checkout service initialized");
        
        if (!UserService.isLoggedIn()) {
            toastr.error("Please login to checkout");
            window.location.hash = "#profile";
            return;
        }
        
        if (!cart || cart.length === 0) {
            toastr.warning("Your cart is empty");
            window.location.hash = "#shop";
            return;
        }
        
        this.displayCartSummary();
        this.setupCheckoutForm();
    },

    displayCartSummary: function() {
        const itemsContainer = $('#checkout-items');
        const totalElement = $('#checkout-total');
        let total = 0;
        
        itemsContainer.empty();
        
        cart.forEach(item => {
            const itemTotal = parseFloat(item.product.price) * item.quantity;
            total += itemTotal;
            
            itemsContainer.append(`
                <div class="d-flex justify-content-between mb-2">
                    <span><strong>${item.product.name}</strong> x ${item.quantity}</span>
                    <span class="text-primary">KM ${itemTotal.toFixed(2)}</span>
                </div>
            `);
        });
        
        totalElement.text(`KM ${total.toFixed(2)}`);
    },

    setupCheckoutForm: function() {
        const self = this;
        
        $('#checkout-form').off('submit').on('submit', function(e) {
            e.preventDefault();
            
            if (!cart || cart.length === 0) {
                toastr.error("Your cart is empty");
                window.location.hash = "#shop";
                return;
            }
            
            const formData = Object.fromEntries(new FormData(this).entries());
            
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Processing...');
            
            OrderService.createOrder(formData, cart, function(response) {
                console.log("Order completed successfully");
                toastr.success("Order placed successfully!");
                
                cart = [];
                saveCart();
                
                setTimeout(function() {
                    window.location.hash = "#home";
                }, 2000);
            }, function(error) {
                submitBtn.prop('disabled', false).html(originalText);
                toastr.success("Order placed successfully!");
            });
        });
    },
 
    refresh: function() {
        if (!cart || cart.length === 0) {
            toastr.warning("Your cart is empty");
            window.location.hash = "#shop";
            return;
        }
        this.displayCartSummary();
    }
};