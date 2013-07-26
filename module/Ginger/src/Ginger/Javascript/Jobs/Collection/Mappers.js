var Collection = $CL.namespace('Ginger.Jobs.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("Ginger.Jobs.Entity.Mapper");

Collection.Mappers = function() {};

Collection.Mappers = $CL.extendClass(Collection.Mappers, Cl.Backbone.Collection, {
    url : "/rest/mappers",
    modelClass : "Ginger.Jobs.Entity.Mapper",
    model : Ginger.Jobs.Entity.Mapper
});