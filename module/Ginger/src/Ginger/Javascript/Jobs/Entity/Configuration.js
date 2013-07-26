var Entity = $CL.namespace('Ginger.Jobs.Entity');

$CL.require('Cl.Backbone.RelationalModel');

Entity.Configuration = function() {};

Entity.Configuration = $CL.extendClass(Entity.Configuration, Cl.Backbone.RelationalModel);