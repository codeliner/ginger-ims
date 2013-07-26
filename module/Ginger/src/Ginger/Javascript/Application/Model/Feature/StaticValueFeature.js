var Feature = $CL.namespace('Ginger.Application.Model.Feature');

$CL.require('Ginger.Application.Model.Feature.AbstractFeature');

Feature.StaticValueFeature = function() {};

Feature.StaticValueFeature = $CL.extendClass(Feature.StaticValueFeature, Feature.AbstractFeature, {
    collectAdvancedOptions : function() {
        var staticValue = this.optionsView.advancedOptionsView.$el.find('input[name=staticvalue-value]').val();
        return {
            static_value : staticValue
        };
    }
});