var Collection = $CL.namespace('Ginger.Jobs.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("Ginger.Jobs.Entity.SourceInfo");

Collection.SourceInfos = function() {};

Collection.SourceInfos = $CL.extendClass(Collection.SourceInfos, Cl.Backbone.Collection, {
    url : "/rest/sourceinfo",
    modelClass : "Ginger.Jobs.Entity.SourceInfo",
    model : Ginger.Jobs.Entity.SourceInfo
});