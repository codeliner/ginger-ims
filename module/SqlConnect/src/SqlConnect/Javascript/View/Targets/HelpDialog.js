var Targets = $CL.namespace('SqlConnect.View.Targets');

$CL.require('Cl.Backbone.View');

Targets.HelpDialog = function() {};

Targets.HelpDialog = $CL.extendClass(Targets.HelpDialog, Cl.Backbone.View, {
    targetName : null,
    setTargetName : function(targetName) {
        this.targetName = targetName;
    },
    render : function() {
        this.$el.html($CL.translate('SqlConnect::TARGET::HELP').replace(':targetName', this.targetName));
    }
});
