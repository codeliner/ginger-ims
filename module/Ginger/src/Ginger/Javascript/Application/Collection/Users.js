var Collection = $CL.namespace('Ginger.Application.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("Ginger.Application.Entity.User");

Collection.Users = function() {};

Collection.Users = $CL.extendClass(Collection.Users, Cl.Backbone.Collection, {
    url : "/rest/users",
    modelClass : "Ginger.Application.Entity.User",
    model : Ginger.Application.Entity.User
});

