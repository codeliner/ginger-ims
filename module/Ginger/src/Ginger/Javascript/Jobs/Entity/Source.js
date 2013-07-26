var Entity = $CL.namespace('Ginger.Jobs.Entity');

$CL.require('Cl.Backbone.RelationalModel');

Entity.Source = function() {};

Entity.Source = $CL.extendClass(Entity.Source, Cl.Backbone.RelationalModel);