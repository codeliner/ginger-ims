var Collection = $CL.namespace('Ginger.Jobs.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("Ginger.Jobs.Entity.Feature");

Collection.Features = function() {};

Collection.Features = $CL.extendClass(Collection.Features, Cl.Backbone.Collection, {
    url : "/rest/features",
    modelClass : "Ginger.Jobs.Entity.Feature",
    model : Ginger.Jobs.Entity.Feature,
    comparator : function(model) {
        return model.get('module') + '::' + model.get('name');
    }
});