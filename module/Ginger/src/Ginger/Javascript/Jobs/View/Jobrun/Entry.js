var Jobrun = $CL.namespace('Ginger.Jobs.View.Jobrun');

$CL.require('Cl.Backbone.View');

Jobrun.Entry = function() {};

Jobrun.Entry = $CL.extendClass(Jobrun.Entry, Cl.Backbone.View, {
    isTerminal : function() {
        return true;
    }
});