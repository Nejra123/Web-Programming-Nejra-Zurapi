let MessageService = {
    sendMessage: function(messageData, callback) {
        const user = UserService.getCurrentUser();
        
        if (!user) {
            toastr.error("Please login to send a message");
            window.location.hash = "#profile";
            return;
        }
    
        
        const customerId = user.ID || user.id;
        const username = user.username || user.email || user.name || 'Guest';
        
        if (!customerId) {
            toastr.error("Could not determine user ID");
            return;
        }
        
        const data = {
            username: username,
            content: messageData.content,
            customer_id: customerId
        };
        $.blockUI({ message: '<h3>Sending Message...</h3>' });

        RestClient.post('messages', data, function(response) {
            setTimeout(function() {
                $.unblockUI();
                location.reload();
            }, 1000);
            toastr.success("Message sent successfully!");
            if (callback) callback(response);
        }, function(jqXHR) {
            setTimeout(function() {
                $.unblockUI();
                location.reload();
            }, 1000);
            console.error("Error sending message:", jqXHR);
            const errorMsg = jqXHR.responseJSON?.error || 
                           jqXHR.responseJSON?.message || 
                           'Failed to send message';
            toastr.error(errorMsg);
        });
    }
};

let ContactService = {
    init: function() {
        this.setupContactForm();
    },

    setupContactForm: function() {
        const self = this;
        
        $('#contact-form').off('submit').on('submit', function(e) {
            e.preventDefault();
            
            if (!UserService.isLoggedIn()) {
                toastr.error("Please login to send a message");
                window.location.hash = "#profile";
                return;
            }
            
            const formData = Object.fromEntries(new FormData(this).entries());
            
            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();
            submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-2"></i> Sending...');
            
            MessageService.sendMessage(formData, function(response) {
                console.log("Message sent successfully");
                $('#contact-form')[0].reset();
                submitBtn.prop('disabled', false).html(originalText);
            });
        });
    },

    refresh: function() {
        this.setupContactForm();
    }
};