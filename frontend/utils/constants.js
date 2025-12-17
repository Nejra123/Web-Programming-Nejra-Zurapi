let Constants = {
    get_api_base_url: function (){
        if(location.hostname=="localhost"){
            return "http://localhost/Web-Programming-Nejra-Zurapi/backend/";
        }
        else{
            return "https://seal-app-nyueq.ondigitalocean.app/"
        }
    },
  //  PROJECT_BASE_URL: "http://localhost/Web-Programming-Nejra-Zurapi/backend/",
    USER_ROLE: "USER",
    ADMIN_ROLE: "ADMIN"
};

