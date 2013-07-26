var Entity = $CL.namespace('Ginger.Jobs.Entity');

$CL.require('Cl.Backbone.RelationalModel');

Entity.SourceInfo = function() {};

Entity.SourceInfo = $CL.extendClass(Entity.SourceInfo, Cl.Backbone.RelationalModel);