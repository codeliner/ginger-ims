var Partial = $CL.namespace('Ginger.Users.View.Partial');

$CL.require('Cl.Backbone.View');

Partial.ActiveUser = function() {};

Partial.ActiveUser = $CL.extendClass(Partial.ActiveUser, Cl.Backbone.View, {
    'events' : {
        'click #logout-btn' : 'onLogoutClick'
    },
    onLogoutClick : function(e) {
        e.preventDefault();
        $CL.app().router.forward('users_auth_logout');
    }
});