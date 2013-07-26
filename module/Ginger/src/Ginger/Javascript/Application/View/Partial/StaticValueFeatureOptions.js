var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require("Cl.Backbone.View");

Partial.StaticValueFeatureOptions = function() {};

Partial.StaticValueFeatureOptions = $CL.extendClass(Partial.StaticValueFeatureOptions, Cl.Backbone.View, {
    render : function() {
        this.parent.prototype.render.apply(this);

        if (this.data.options && this.data.options.static_value) {
            this.$el.find('input[name=staticvalue-value]').val(this.data.options.static_value);
        }
    }
});