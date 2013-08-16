var Collection = $CL.namespace('Ginger.Jobs.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("Ginger.Jobs.Entity.Job");

Collection.Jobs = function() {};

Collection.Jobs = $CL.extendClass(Collection.Jobs, Cl.Backbone.Collection, {
    url : "/rest/jobs",
    modelClass : "Ginger.Jobs.Entity.Job",
    model : Ginger.Jobs.Entity.Job,
    parse : function(models, options) {
        _.each(models, function(model) {
            var tasks = [].concat(model.tasks),
            jobruns = [].concat(model.jobruns);

            model.tasks = $CL.makeObj('Ginger.Jobs.Collection.Tasks');
            model.tasks.set(tasks);
            model.jobruns = $CL.makeObj('Ginger.Jobs.Collection.Jobruns');
            model.jobruns.set(jobruns);
        });

        return models;
    }
});