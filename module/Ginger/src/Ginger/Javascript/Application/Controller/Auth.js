var Controller = $CL.namespace('Ginger.Application.Controller');

$CL.require("Cl.Application.Mvc.AbstractController");
$CL.require("Ginger.Application.Service.Auth.Adapter");

Controller.Auth = function() {};

Controller.Auth = $CL.extendClass(Controller.Auth, Cl.Application.Mvc.AbstractController, {
    authAdapter : null,
    setAuthAdapter : function(authAdapter) {
        this.authAdapter = authAdapter;
    },
    loginAction : function() {
        var v = $CL.get('Ginger.Application.View.Auth.Login');
        
        v.setSubmitCallback($CL.bind(function(data) {
            this.authAdapter.setUsername(data.email);
            this.authAdapter.setPassword(data.password);
            
            $CL.app().router.callRoute('dashboard');
        }, this));
        
        return v;
    }
});