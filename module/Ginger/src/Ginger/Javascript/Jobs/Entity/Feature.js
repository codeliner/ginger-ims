var Entity = $CL.namespace('Ginger.Jobs.Entity');

$CL.require('Cl.Backbone.RelationalModel');

Entity.Feature = function() {};

Entity.Feature = $CL.extendClass(Entity.Feature, Cl.Backbone.RelationalModel);