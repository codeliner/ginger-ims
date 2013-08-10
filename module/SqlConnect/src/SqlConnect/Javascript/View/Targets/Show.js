var Targets = $CL.namespace('SqlConnect.View.Targets');

$CL.require('Cl.Backbone.View');

Targets.Show = function() {};

Targets.Show = $CL.extendClass(Targets.Show, Cl.Backbone.View, {
    events : {
        'click .sqlconnect-set-target' : 'onSetTargetClick'
    },
    onSetTargetClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'a');

        if ($a.hasClass('btn-success')) {
            $a.removeClass('btn-success').find('i').removeClass('icon-ok').addClass('icon-stop');
            $CL.get("application").router.forward('sqlconnect_target', {action : 'remove', id : this.data.id});
        } else {
            $a.addClass('btn-success').find('i').removeClass('icon-stop').addClass('icon-ok');
            $CL.get("application").router.forward('sqlconnect_target', {action : 'add', id : this.data.id});
        }
    }
});