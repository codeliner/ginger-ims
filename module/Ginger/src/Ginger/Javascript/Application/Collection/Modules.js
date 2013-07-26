var Collection = $CL.namespace('Ginger.Application.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("Ginger.Application.Entity.Module");

Collection.Modules = function() {};

Collection.Modules = $CL.extendClass(Collection.Modules, Cl.Backbone.Collection, {
    url : "/rest/modules",
    modelClass : "Ginger.Application.Entity.Module",
    model : Ginger.Application.Entity.Module
});