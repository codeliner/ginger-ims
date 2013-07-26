var Collection = $CL.namespace('Ginger.Jobs.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("Ginger.Jobs.Entity.Configuration");

Collection.Configurations = function() {};

Collection.Configurations = $CL.extendClass(Collection.Configurations, Cl.Backbone.Collection, {
    urlBase : "/rest/configurations",
    url : "",
    modelClass : "Ginger.Jobs.Entity.Configuration",
    model : Ginger.Jobs.Entity.Configuration,
    setJobName : function(jobName) {
        this.url = this.urlBase + "/" + jobName;
    }
});