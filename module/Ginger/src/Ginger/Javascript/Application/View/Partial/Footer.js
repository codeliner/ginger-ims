var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require('Cl.Backbone.View');

Partial.Footer = function() {};

Partial.Footer = $CL.extendClass(Partial.Footer, Cl.Backbone.View, {
    render : function() {
        //empty footer by default
        this.$el.html("");
    }
});