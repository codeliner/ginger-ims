var Sources = $CL.namespace('SqlConnect.View.Sources');

$CL.require('Cl.Backbone.View');

Sources.Show = function() {};

Sources.Show = $CL.extendClass(Sources.Show, Cl.Backbone.View, {
    events : {
        'click .sqlconnect-set-source' : 'onSetSourceClick'
    },
    onSetSourceClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'a');

        if ($a.hasClass('btn-success')) {
            $a.removeClass('btn-success').find('i').removeClass('icon-ok').addClass('icon-stop');
            $CL.get("application").router.forward('sqlconnect_source', {action : 'remove', id : this.data.id});
        } else {
            $a.addClass('btn-success').find('i').removeClass('icon-stop').addClass('icon-ok');
            $CL.get("application").router.forward('sqlconnect_source', {action : 'add', id : this.data.id});
        }
    }
});