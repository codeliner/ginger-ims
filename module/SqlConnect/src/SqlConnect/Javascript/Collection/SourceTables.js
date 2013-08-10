var Collection = $CL.namespace('SqlConnect.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("SqlConnect.Entity.Source");

Collection.SourceTables = function(){};

Collection.SourceTables = $CL.extendClass(Collection.SourceTables, Cl.Backbone.Collection, {
    urlBase : '/sqlconnect/rest/sources',
    url : "",
    modelClass : 'SqlConnect.Entity.Source',
    model : SqlConnect.Entity.Source,
    setConnection : function(connection) {
        this.url = this.urlBase + "/" + connection;
    }
});