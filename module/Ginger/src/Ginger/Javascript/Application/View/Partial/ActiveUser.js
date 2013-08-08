var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require('Cl.Backbone.View');

Partial.ActiveUser = function() {};

Partial.ActiveUser = $CL.extendClass(Partial.ActiveUser, Cl.Backbone.View, {
    'events' : {
        'click #logout-btn' : 'onLogoutClick'
    },
    onLogoutClick : function(e) {
        e.preventDefault();
        $CL.app().router.forward('application_auth_logout');
    }
});