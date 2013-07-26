var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require('Cl.Backbone.View');

Partial.DevNullOptions = function() {};

Partial.DevNullOptions = $CL.extendClass(Partial.DevNullOptions, Cl.Backbone.View, {
    render : function() {
        Cl.Backbone.View.prototype.render.apply(this);

        if ($CL.isDefined(this.data.options)) {
            this.$el.find('input[name=devnull-log-file-name]').val(this.data.options.log_file_name);
        }
    }
});


