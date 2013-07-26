var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require("Cl.Backbone.View");

Partial.AttributeMapFeatureHelp = function() {};

Partial.AttributeMapFeatureHelp = $CL.extendClass(Partial.AttributeMapFeatureHelp, Cl.Backbone.View, {
    render : function() {
        this.$el.html($CL.translate('ATTRIBUTEMAP::HELP'));
    }
});