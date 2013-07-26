var Entity = $CL.namespace('Ginger.Application.Entity');

$CL.require('Cl.Backbone.Model');

Entity.Module = function() {};

Entity.Module = $CL.extendClass(Entity.Module, Cl.Backbone.Model, {
    idAttribute : 'module'
});