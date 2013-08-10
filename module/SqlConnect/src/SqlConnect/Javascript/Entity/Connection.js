var Entity = $CL.namespace('SqlConnect.Entity');

$CL.require('Cl.Backbone.Model');

Entity.Connection = function() {};

Entity.Connection = $CL.extendClass(Entity.Connection, Cl.Backbone.Model, {
    idAttribute : "name"
});