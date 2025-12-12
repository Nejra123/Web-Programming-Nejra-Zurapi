let Utils = {
    parseJwt: function(token) {
        if (!token) {
            console.error("No token provided to parseJwt");
            return null;
        }
        
        try {
            const parts = token.split('.');
            if (parts.length !== 3) {
                console.error("Invalid JWT format");
                return null;
            }
            
            const payload = parts[1];
            const decoded = atob(payload.replace(/-/g, '+').replace(/_/g, '/'));
            const parsed = JSON.parse(decoded);
            
            console.log("JWT parsed successfully:", parsed);
            return parsed;
        } catch (e) {
            console.error("Error parsing JWT token:", e);
            return null;
        }
    }
};