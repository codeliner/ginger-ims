var Entity = $CL.namespace('Ginger.Jobs.Entity');

$CL.require('Cl.Backbone.RelationalModel');

Entity.Mapper = function() {};

Entity.Mapper = $CL.extendClass(Entity.Mapper, Cl.Backbone.RelationalModel);