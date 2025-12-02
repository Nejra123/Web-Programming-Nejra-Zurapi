let ProfileService = {
    init: function() {
        console.log("Profile service initialized");
        
        const token = localStorage.getItem("user_token");
        
        if (!token) {
            this.showLogin();
        } else {
            const user = UserService.getCurrentUser();
            if (user) {
                this.handleLoggedInUser(user);
            } else {
                this.showLogin();
            }
        }
    },

    showLogin: function() {
        console.log("Showing login form");
        $('#login-section').show();
        $('#user-dashboard').hide();
        $('#admin-redirect').hide();
        UserService.initLogin();
    },

    handleLoggedInUser: function(user) {
        const userName = user.name || user.username || user.email || 'User';
        const userRole = user.role ? user.role.toUpperCase() : '';
        const adminRole = Constants.ADMIN_ROLE.toUpperCase();
        
        if (userRole === adminRole) {
            this.showAdminRedirect(userName);
        } else {
            this.showUserDashboard(userName);
        }
    },

    showAdminRedirect: function(userName) {
        console.log("Showing admin redirect");
        $('#admin-name').text(userName);
        $('#admin-redirect').show();
        $('#login-section').hide();
        $('#user-dashboard').hide();
        
        setTimeout(function() {
            console.log("Redirecting to admin panel...");
            window.location.hash = "#admin";
        }, 2000);
    },

    showUserDashboard: function(userName) {
        console.log("Showing user dashboard");
        $('#user-name').text(userName);
        $('#user-dashboard').show();
        $('#login-section').hide();
        $('#admin-redirect').hide();
    }
};