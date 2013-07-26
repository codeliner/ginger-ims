var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require('Cl.Backbone.View');

Partial.SourceScriptOptions = function() {};

Partial.SourceScriptOptions = $CL.extendClass(Partial.SourceScriptOptions, Cl.Backbone.View, {
    render : function() {
        Cl.Backbone.View.prototype.render.apply(this);

        if ($CL.isDefined(this.data.options)) {
            this.$el.find('input[name=sourcescript-script-name]').val(this.data.options.script_name);
        }
    }
});


