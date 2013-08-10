var Targets = $CL.namespace('SqlConnect.View.Targets');

$CL.require('Cl.Backbone.View');

Targets.Options = function() {};

Targets.Options = $CL.extendClass(Targets.Options, Cl.Backbone.View, {
    events : {
        'click label[for=sqlconnect-target-empty-table]' : 'onEmptyTableClick'
    },
    onEmptyTableClick : function(e) {
        if (e.target.nodeName.toLowerCase() != "input") {
            var $label = $CL.jTarget(e.target, 'label');

            if ($label.find('input').is(':checked')) {
                $label.find('input').removeAttr('checked');
            } else {
                $label.find('input').attr('checked', 'checked');
            }
        }
    },
    render : function() {
        this.parent.prototype.render.apply(this);

        if (!$CL.isEmpty(this.data.options)) {
            if (this.data.options.emptyTable) {
                this.$el.find('input[name=sqlconnect-target-empty-table]').attr('checked', 'checked');
            }
        }
    }
});
