var Partial = $CL.namespace('Ginger.Users.View.Partial');

$CL.require('Cl.Backbone.View');

Partial.ActiveUser = function() {};

Partial.ActiveUser = $CL.extendClass(Partial.ActiveUser, Cl.Backbone.View, {
    'events' : {
        'click #logout-btn' : 'onLogoutClick',
        'click #nav-active-user-link' : 'onActiveUserLinkClick'
    },
    onLogoutClick : function(e) {
        e.preventDefault();
        $CL.app().router.forward('users_auth_logout');
    },
    onActiveUserLinkClick : function(e) {
        e.preventDefault();        
        $CL.app().router.callRoute('users_user_show', {
            id : $CL.get('user_manager').getActiveUser().get('id')
        });
    }
});