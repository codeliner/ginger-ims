var Entity = $CL.namespace('Ginger.Jobs.Entity');

$CL.require('Cl.Backbone.RelationalModel');

Entity.Task = function() {};

Entity.Task = $CL.extendClass(Entity.Task, Cl.Backbone.RelationalModel);