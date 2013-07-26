var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require('Cl.Backbone.View');

Partial.SourcefileOptions = function() {};

Partial.SourcefileOptions = $CL.extendClass(Partial.SourcefileOptions, Cl.Backbone.View, {
    render : function() {
        Cl.Backbone.View.prototype.render.apply(this);

        if ($CL.isDefined(this.data.options)) {
            this.$el.find('input[name=sourcefile-file-pattern]').val(this.data.options.file_pattern);
        }
    }
});


