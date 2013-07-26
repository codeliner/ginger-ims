var Entity = $CL.namespace('Ginger.Jobs.Entity');

$CL.require('Cl.Backbone.RelationalModel');

Entity.Target = function() {};

Entity.Target = $CL.extendClass(Entity.Target, Cl.Backbone.RelationalModel);