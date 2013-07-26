var Collection = $CL.namespace('Ginger.Jobs.Collection');

$CL.require("Cl.Backbone.Collection");
$CL.require("Ginger.Jobs.Entity.Jobrun");

Collection.LatestJobruns = function() {};

Collection.LatestJobruns = $CL.extendClass(Collection.LatestJobruns, Cl.Backbone.Collection, {
    url : "/rest/latest-jobruns",
    modelClass : "Ginger.Jobs.Entity.Jobrun",
    model : Ginger.Jobs.Entity.Jobrun,
    comparator : function(model1, model2) {
        var model1Date = model1.get('startTime'),
        model2Date = model2.get('startTime'),
        model1Ts = 0,
        model2Ts = 0;

        if (model1Date) {
            model1Ts = Date.parseDate(model1Date, Date.getDbStrFmt(true)).toTimestamp();
        }

        if (model2Date) {
            model2Ts = Date.parseDate(model2Date, Date.getDbStrFmt(true)).toTimestamp();
        }

        if (model1Ts == 0 && model2Ts == 0) {
            return 0;
        }

        if (model1Ts == 0) {
            return -1;
        }

        if (model2Ts == 0) {
            return 1;
        }

        if (model1Ts > model2Ts) {
            return -1;
        }

        if (model1Ts == model2Ts) {
            return 0;
        }

        if (model1Ts < model2Ts) {
            return 1;
        }
    }
});

