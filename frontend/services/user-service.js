var UserService = {
    initLogin: function () {
        console.log("Initializing login form");
        
        $("#login-form").validate({
            rules: {
                email: { required: true, email: true },
                password: { required: true, minlength: 3 }
            },
            submitHandler: function (form) {
                var entity = Object.fromEntries(new FormData(form).entries());
                console.log("Submitting login:", entity);
                UserService.login(entity);
            },
        });
    },
    
    initRegister: function () {
        console.log("Initializing register form");
        
        $("#register-form").validate({
            rules: {
                username: { required: true, minlength: 3 },
                name: { required: true, minlength: 2 },
                surname: { required: true, minlength: 2 },
                email: { required: true, email: true },
                password: { required: true, minlength: 6 }
            },
            submitHandler: function (form) {
                var entity = Object.fromEntries(new FormData(form).entries());
                console.log("Submitting registration:", entity);
                UserService.register(entity);
            },
        });
    },
    
    login: function (entity) {
        console.log("Login attempt:", entity);
        
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/login",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
           success: function (result) {
    console.log("Login response:", result);
    
    if (result.data && result.data.token) {
        localStorage.setItem("user_token", result.data.token);
        
        const decoded = Utils.parseJwt(result.data.token);
        console.log("Decoded token after login:", decoded);
        
        toastr.success("Login successful!");
        
        setTimeout(function() {
            UserService.updateNavigation();
            window.location.hash = "#profile";
            location.reload();
        }, 1000);
    } else {
        toastr.error(result.error || result.message || 'Login failed');
    

                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.error("Login error:", XMLHttpRequest);
                const errorMsg = XMLHttpRequest.responseJSON?.error || 
                               XMLHttpRequest.responseJSON?.message || 
                               XMLHttpRequest.responseText || 
                               'Login failed';
                toastr.error(errorMsg);
            },
        });
    },
    
    register: function (entity) {
        console.log("Register attempt:", entity);
        
        $.ajax({
            url: Constants.PROJECT_BASE_URL + "auth/register",
            type: "POST",
            data: JSON.stringify(entity),
            contentType: "application/json",
            dataType: "json",
            success: function (result) {
                console.log("Registration success:", result);
                
                if (result.success || result.message) {
                    toastr.success("Registration successful! Please login.");
                    setTimeout(function() {
                        window.location.hash = "#profile";
                    }, 1500);
                } else {
                    toastr.error(result.error || 'Registration failed');
                }
            },
            error: function (XMLHttpRequest, textStatus, errorThrown) {
                console.error("Registration error:", XMLHttpRequest);
                const errorMsg = XMLHttpRequest.responseJSON?.error || 
                               XMLHttpRequest.responseJSON?.message || 
                               XMLHttpRequest.responseText || 
                               'Registration failed';
                toastr.error(errorMsg);
            },
        });
    },
    
    logout: function () {
        localStorage.clear();
        toastr.success("Logged out successfully");
        setTimeout(function() {
            window.location.hash = "#home";
            location.reload();
        }, 1000);
    },
    
    updateNavigation: function() {
        const token = localStorage.getItem("user_token");
        const navContainer = $(".navbar-nav");
        
        $(".logout-btn").remove();
        $(".admin-panel-link").remove();
        
      
        console.log("Token exists:", !!token);
        
        if (token) {
            const decoded = Utils.parseJwt(token);
            console.log("Full decoded token:", decoded);
            
            let user = null;
            
            if (decoded && decoded.user) {
                user = decoded.user;
                console.log("User found in decoded.user:", user);
            } 
            
            if (user) {
                console.log("User role:", user.role);
                console.log("Expected ADMIN role:", Constants.ADMIN_ROLE);
                console.log("Role match:", user.role === Constants.ADMIN_ROLE);
                
               
                navContainer.append(`
                    <a href="#" class="nav-item nav-link logout-btn" onclick="UserService.logout(); return false;">Logout</a>
                `);
                
                
                if (user.role && user.role.toUpperCase() === Constants.ADMIN_ROLE.toUpperCase()) {
                    console.log("User is ADMIN - adding admin panel link");
                    navContainer.append(`
                        <a href="#admin" class="nav-item nav-link admin-panel-link">Admin Panel</a>
                    `);
                } else {
                    console.log("User is NOT admin. Role:", user.role);
                }
                
               
                $('a[href="#profile"]').hide();
            } else {
                console.error("Could not extract user from token");
            }
        } else {
            console.log("No token - showing login link");
            $('a[href="#profile"]').show();
        }
        
       
    },
    
    isLoggedIn: function() {
        return !!localStorage.getItem("user_token");
    },
    
    getCurrentUser: function() {
        const token = localStorage.getItem("user_token");
        if (!token) {
            console.log("getCurrentUser: No token found");
            return null;
        }
        
        const decoded = Utils.parseJwt(token);
        if (!decoded) {
            console.log("getCurrentUser: Could not decode token");
            return null;
        }
        

        let user = null;
        
        if (decoded.user) {
            user = decoded.user;
        } else {
            user = decoded;
        }
        
        console.log("getCurrentUser returning:", user);
        return user;
    }
};