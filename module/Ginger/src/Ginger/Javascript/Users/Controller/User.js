var Controller = $CL.namespace("Ginger.Users.Controller");

$CL.require("Cl.Application.Mvc.AbstractController");

Controller.User = function() {};

Controller.User = $CL.extendClass(Controller.User, Cl.Application.Mvc.AbstractController, {
    usersCollection : null,
    setUsersCollection : function(usersCollection) {
        this.usersCollection = usersCollection;
    },
    createFirstUserAction : function() {
        var userData = this.getMvcEvent().getRouteMatch().getParam('userData');
        
        if (userData) {
            var password = userData.password;
            this.addCredentialsData(userData);
            
            delete userData.password;
            
            var newUser = this.usersCollection.create(userData);
            
            $CL.get('auth_adapter').setUsername(userData.email);
            $CL.get('auth_adapter').setPassword(password);
            $CL.get('auth_adapter').validCredentials = true;
            
            $CL.log("new User: ", newUser);
        } else {
            $CL.exception('No userData found for first user', 'Ginger.Users.Controller.User');
        }
    },
    addCredentialsData : function(userData) {
        userData['apiKey'] = $CL.get('auth_adapter').generateApiKey(userData.email);
        userData['secretKey'] = $CL.get('auth_adapter').generateSecretKey(userData.password);
    }
});