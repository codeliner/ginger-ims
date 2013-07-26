var Collection = $CL.namespace('Ginger.Jobs.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("Ginger.Jobs.Entity.Source");

Collection.Sources = function() {};

Collection.Sources = $CL.extendClass(Collection.Sources, Cl.Backbone.Collection, {
    url : "/rest/sources",
    modelClass : "Ginger.Jobs.Entity.Source",
    model : Ginger.Jobs.Entity.Source,
    comparator : function(model) {
        return model.get('module') + '::' + model.get('name');
    }
});