var Collection = $CL.namespace('Ginger.Jobs.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("Ginger.Jobs.Entity.Target");

Collection.Targets = function() {};

Collection.Targets = $CL.extendClass(Collection.Targets, Cl.Backbone.Collection, {
    url : "/rest/targets",
    modelClass : "Ginger.Jobs.Entity.Target",
    model : Ginger.Jobs.Entity.Target,
    comparator : function(model) {
        return model.get('module') + '::' + model.get('name');
    }
});