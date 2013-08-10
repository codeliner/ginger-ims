var Index = $CL.namespace('SqlConnect.View.Index');

$CL.require('Cl.Backbone.View');

Index.Configuration = function() {};

Index.Configuration = $CL.extendClass(Index.Configuration, Cl.Backbone.View, {
    events : {
        'click .js-add-connection' : 'onAddConnectionClick',
        'click .js-connections .js-edit' : 'onEditConnectionClick',
        'click .js-connections .js-remove' : 'onRemoveConnectionClick'
    },
    onAddConnectionClick : function() {
        $CL.app().router.callRoute('sqlconnect_configuration_add');
    },
    onEditConnectionClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'a');

        $CL.app().router.callRoute('sqlconnect_configuration_edit', {connection : $a.data('id')});
    },
    onRemoveConnectionClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'a');

        $CL.app().wait().router.forward('sqlconnect_configuration_remove', {
            connection : $a.data('id'),
            callback : function(success) {
                $CL.app().stopWait();
                if (success) {
                    $a.parents('.ts-tr').remove();
                }
            }
        });
    }
});