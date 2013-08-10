var Collection = $CL.namespace('SqlConnect.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("SqlConnect.Entity.Connection");

Collection.Connections = function(){};

Collection.Connections = $CL.extendClass(Collection.Connections, Cl.Backbone.Collection, {
    url : '/sqlconnect/rest/connections',
    modelClass : 'SqlConnect.Entity.Connection',
    model : SqlConnect.Entity.Connection
});