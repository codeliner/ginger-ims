var Collection = $CL.namespace('Ginger.Jobs.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("Ginger.Jobs.Entity.TargetInfo");

Collection.TargetInfos = function() {};

Collection.TargetInfos = $CL.extendClass(Collection.TargetInfos, Cl.Backbone.Collection, {
    url : "/rest/targetinfo",
    modelClass : "Ginger.Jobs.Entity.TargetInfo",
    model : Ginger.Jobs.Entity.TargetInfo
});