var Controller = $CL.namespace('Ginger.Users.Controller');

$CL.require("Cl.Application.Mvc.AbstractController");
$CL.require("Ginger.Users.Service.Auth.Adapter");

Controller.Auth = function() {};

Controller.Auth = $CL.extendClass(Controller.Auth, Cl.Application.Mvc.AbstractController, {
    authAdapter : null,
    userManager : null,
    setAuthAdapter : function(authAdapter) {
        this.authAdapter = authAdapter;
    },
    setUserManager : function(userManager) {
        this.userManager = userManager;
    },
    loginAction : function() {
        var v = $CL.get('Ginger.Users.View.Auth.Login');
        
        v.setSubmitCallback($CL.bind(function(data) {
            this.authAdapter.setUsername(data.email);
            this.authAdapter.setPassword(data.password);
            
            $CL.app().router.callRoute('dashboard');
        }, this));
        
        return v;
    },
    logoutAction : function() {
        this.getMvcEvent().stopPropagation();
        this.userManager.logoutUser();
        window.location.reload();
    }
});