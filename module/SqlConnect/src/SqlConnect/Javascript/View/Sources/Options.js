var Sources = $CL.namespace('SqlConnect.View.Sources');

$CL.require('Cl.Backbone.BlockingView');

Sources.Options = function() {};

Sources.Options = $CL.extendClass(Sources.Options, Cl.Backbone.BlockingView, {
    render : function() {
        this.parent.prototype.render.apply(this);

        if (!$CL.isEmpty(this.data.options)) {
            this.$el.find('select[name=sqlconnect-source-count-column]').val(this.data.options.countColumn);
            
            if (this.data.options.customSql) {
                this.$el.find('textarea[name=sqlconnect-source-custom-sql]').val(this.data.options.customSql);
            }
        }
    }
});
