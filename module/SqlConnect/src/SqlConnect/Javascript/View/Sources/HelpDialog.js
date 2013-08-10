var Sources = $CL.namespace('SqlConnect.View.Sources');

$CL.require('Cl.Backbone.View');

Sources.HelpDialog = function() {};

Sources.HelpDialog = $CL.extendClass(Sources.HelpDialog, Cl.Backbone.View, {
    sourceName : null,
    setSourceName : function(sourceName) {
        this.sourceName = sourceName;
    },
    render : function() {
        this.$el.html($CL.translate('SqlConnect::SOURCE::HELP').replace(':sourceName', this.sourceName));
    }
});
