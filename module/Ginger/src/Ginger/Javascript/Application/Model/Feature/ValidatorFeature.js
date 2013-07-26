var Feature = $CL.namespace('Ginger.Application.Model.Feature');

$CL.require('Ginger.Application.Model.Feature.AbstractFeature');

Feature.ValidatorFeature = function() {};

Feature.ValidatorFeature = $CL.extendClass(Feature.ValidatorFeature, Feature.AbstractFeature, {
    collectAdvancedOptions : function() {
        var errorHandling = this.optionsView.advancedOptionsView.$el.find('input[name=validator-error-handling]').filter('input[checked=checked]').val();
        return {
            error_handling : errorHandling
        };
    }
});