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
        
        v.setData({
            invalidCredentials : this.getMvcEvent().getRouteMatch().getParam('invalidCredentials')
        });
        
        v.setSubmitCallback($CL.bind(function(data) {
            this.authAdapter.setUsername(data.email);
            this.authAdapter.setPassword(data.password);
            
            if (this.authAdapter.checkCredentials().isValid()) {
                $CL.app().router.callRoute('dashboard');
            } else {
                $CL.app().router.forward('users_auth_login', {invalidCredentials : true});
            }
            
        }, this));
        
        return v;
    },
    logoutAction : function() {
        this.getMvcEvent().stopPropagation();
        this.userManager.logoutUser();
        window.location.reload();
    }
});