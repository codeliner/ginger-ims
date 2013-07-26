var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require("Cl.Backbone.View");

Partial.ValidatorFeatureOptions = function() {};

Partial.ValidatorFeatureOptions = $CL.extendClass(Partial.ValidatorFeatureOptions, Cl.Backbone.View, {
    events : {
        'click label[for=validator-error-handling]' : 'onValidatorErrorHandlingClick'
    },
    onValidatorErrorHandlingClick : function(e) {
        $CL.jTarget(e.target, 'label').find('input[type=radio]').attr('checked', 'checked');
    },
    render : function() {
        this.parent.prototype.render.apply(this);

        if (this.data.options && this.data.options.error_handling) {
            this.$el.find('input[type=radio]').filter('input[value='+this.data.options.error_handling+']').click();
        }
    }
});