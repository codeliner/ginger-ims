var Collection = $CL.namespace('SqlConnect.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("SqlConnect.Entity.Target");

Collection.TargetTables = function(){};

Collection.TargetTables = $CL.extendClass(Collection.TargetTables, Cl.Backbone.Collection, {
    urlBase : '/sqlconnect/rest/targets',
    url : "",
    modelClass : 'SqlConnect.Entity.Target',
    model : SqlConnect.Entity.Target,
    setConnection : function(connection) {
        this.url = this.urlBase + "/" + connection;
    }
});