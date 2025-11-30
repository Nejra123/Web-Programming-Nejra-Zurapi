let MessageService = {
    sendMessage: function(messageData, callback) {
        const user = UserService.getCurrentUser();
        
        if (!user) {
            toastr.error("Please login to send a message");
            window.location.hash = "#profile";
            return;
        }
        
        console.log("Sending message, user data:", user);
        

        const customerId = user.ID || user.id;
        const username = user.username || user.email || user.name || 'Guest';
        
        if (!customerId) {
            console.error("No customer ID found in user data:", user);
            toastr.error("Could not determine user ID");
            return;
        }
        
        const data = {
            username: username,
            content: messageData.content,
            customer_id: customerId
        };
        
        console.log("Message data to send:", data);
        
        RestClient.post('messages', data, function(response) {
            console.log("Message sent successfully:", response);
            toastr.success("Message sent successfully!");
            if (callback) callback(response);
        }, function(jqXHR) {
            console.error("Error sending message:", jqXHR);
            const errorMsg = jqXHR.responseJSON?.error || 
                           jqXHR.responseJSON?.message || 
                           'Failed to send message';
            toastr.error(errorMsg);
        });
    }
};