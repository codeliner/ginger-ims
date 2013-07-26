var Entity = $CL.namespace('Ginger.Jobs.Entity');

$CL.require('Cl.Backbone.RelationalModel');
$CL.require("Ginger.Jobs.Entity.Configuration");
$CL.require("Ginger.Jobs.Entity.Jobrun");
$CL.require("Ginger.Jobs.Collection.Configurations");
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
            key : 'configurations',
            relatedModel : Ginger.Jobs.Entity.Configuration,
            collectionType : Ginger.Jobs.Collection.Configurations,
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

        if (key == "configurations" || key == "jobruns") {
            if (value) {
                value.setJobName(this.get('name'));
            }
        }

        return value;
    }
});
