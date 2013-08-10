var Collection = $CL.namespace('Ginger.Users.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("Ginger.Users.Entity.User");

Collection.Users = function() {};

Collection.Users = $CL.extendClass(Collection.Users, Cl.Backbone.Collection, {
    url : "/rest/users",
    modelClass : "Ginger.Users.Entity.User",
    model : Ginger.Users.Entity.User
});

