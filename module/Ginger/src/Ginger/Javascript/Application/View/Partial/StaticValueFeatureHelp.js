var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require("Cl.Backbone.View");

Partial.StaticValueFeatureHelp = function() {};

Partial.StaticValueFeatureHelp = $CL.extendClass(Partial.StaticValueFeatureHelp, Cl.Backbone.View, {
    render : function() {
        this.$el.html($CL.translate('FEATURES::STATIC_VALUE::HELP::FEATURE'));
    }
});