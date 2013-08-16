var Collection = $CL.namespace('Ginger.Jobs.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("Ginger.Jobs.Entity.Task");

Collection.Tasks = function() {};

Collection.Tasks = $CL.extendClass(Collection.Tasks, Cl.Backbone.Collection, {
    urlBase : "/rest/tasks",
    url : "",
    modelClass : "Ginger.Jobs.Entity.Task",
    model : Ginger.Jobs.Entity.Task,
    setJobName : function(jobName) {
        this.url = this.urlBase + "/" + jobName;
    }
});