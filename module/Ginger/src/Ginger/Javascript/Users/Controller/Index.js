var Controller = $CL.namespace("Ginger.Users.Controller");

$CL.require("Cl.Application.Mvc.AbstractController");

Controller.Index = function() {};

Controller.Index = $CL.extendClass(Controller.Index, Cl.Application.Mvc.AbstractController, {
    indexAction : function() {
        var isDummy = -1;
        if ($CL.get('user_manager').getActiveUser().get('id') === isDummy) {
            var v = $CL.get("Ginger.Users.View.Form.User");
            v.setSubmitCallback(function(formData) {
                formData['isAdmin'] = true;
                $CL.app().router.forward('users_user_create_first', {userData : formData});
            });

            v.setData({headline : $CL.translate('USERS::HEADLINE::ADD_USER'), isFirstUser : true});

            return v;
        }
        
    }
});