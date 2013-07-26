var Controller = $CL.namespace("Ginger.Users.Controller");

$CL.require("Cl.Application.Mvc.AbstractController");

Controller.Index = function() {};

Controller.Index = $CL.extendClass(Controller.Index, Cl.Application.Mvc.AbstractController, {
    indexAction : function() {
        var v = $CL.get("Ginger.Users.View.Form.User");
        v.setSubmitCallback(function(formData) {
            alert(formData);
        });

        v.setData({headline : $CL.translate('USERS::HEADLINE::ADD_USER')});

        return v;
    }
});