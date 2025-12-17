let RestClient = {
    get: function (url, callback, error_callback) {
        console.log("GET Request to:", Constants.get_api_base_url()+ url);
        
        $.ajax({
            url: Constants.get_api_base_url() + url,
            type: "GET",
            beforeSend: function (xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authentication", token);
                    console.log("Sending token:", token);
                }
            },
            success: function (response) {
                console.log("GET Response:", response);
                if (callback) callback(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error("GET Error:", {
                    status: jqXHR.status,
                    response: jqXHR.responseJSON,
                    text: jqXHR.responseText
                });
                if (error_callback) error_callback(jqXHR);
            },
        });
    },
    
    request: function (url, method, data, callback, error_callback) {
        console.log(method + " Request to:", Constants.get_api_base_url() + url);
        console.log("Data:", data);
        
        $.ajax({
            url: Constants.get_api_base_url() + url,
            type: method,
            data: JSON.stringify(data),
            contentType: "application/json",
            dataType: "json",
            beforeSend: function (xhr) {
                const token = localStorage.getItem("user_token");
                if (token) {
                    xhr.setRequestHeader("Authentication", token);
                    console.log("Sending token:", token);
                }
            },
            success: function (response, status, jqXHR) {
                console.log(method + " Response:", response);
                if (callback) callback(response);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                console.error(method + " Error:", {
                    status: jqXHR.status,
                    response: jqXHR.responseJSON,
                    text: jqXHR.responseText
                });
                if (error_callback) {
                    error_callback(jqXHR);
                } else {
                    const errorMsg = jqXHR.responseJSON?.error || 
                                   jqXHR.responseJSON?.message || 
                                   jqXHR.responseText || 
                                   'An error occurred';
                    toastr.error(errorMsg);
                }
            }
        });
    },
    
    post: function (url, data, callback, error_callback) {
        RestClient.request(url, "POST", data, callback, error_callback);
    },
    
    delete: function (url, data, callback, error_callback) {
        RestClient.request(url, "DELETE", data, callback, error_callback);
    },
    
    patch: function (url, data, callback, error_callback) {
        RestClient.request(url, "PATCH", data, callback, error_callback);
    },
    
    put: function (url, data, callback, error_callback) {
        RestClient.request(url, "PUT", data, callback, error_callback);
    },
};