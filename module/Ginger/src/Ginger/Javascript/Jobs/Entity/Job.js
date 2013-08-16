var Entity = $CL.namespace('Ginger.Jobs.Entity');

$CL.require('Cl.Backbone.RelationalModel');
$CL.require("Ginger.Jobs.Entity.Task");
$CL.require("Ginger.Jobs.Entity.Jobrun");
$CL.require("Ginger.Jobs.Collection.Tasks");
$CL.require("Ginger.Jobs.Collection.Jobruns");

Entity.Job = function() {};

Entity.Job = $CL.extendClass(Entity.Job, Cl.Backbone.RelationalModel, {
    idAttribute : 'name',
    defaults : {
        description : ""
    },
    relations : [
        {
            type : 'HasMany',
            key : 'tasks',
            relatedModel : Ginger.Jobs.Entity.Task,
            collectionType : Ginger.Jobs.Collection.Tasks,
            reverseRelation : {
                key : 'job'
            }
        },
        {
            type : 'HasMany',
            key : 'jobruns',
            relatedModel : Ginger.Jobs.Entity.Jobrun,
            collectionType : Ginger.Jobs.Collection.Jobruns,
            reverseRelation : {
                key : 'job'
            }
        }
    ],
    get : function(key) {
        var value = this.parent.prototype.get.apply(this, arguments);

        if (key == "tasks" || key == "jobruns") {
            if (value) {
                value.setJobName(this.get('name'));
            }
        }

        return value;
    }
});
