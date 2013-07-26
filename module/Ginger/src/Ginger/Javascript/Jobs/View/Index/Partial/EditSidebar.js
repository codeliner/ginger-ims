var Partial = $CL.namespace('Ginger.Jobs.View.Index.Partial');

$CL.require('Cl.Backbone.View');

Partial.EditSidebar = function() {};

Partial.EditSidebar = $CL.extendClass(Partial.EditSidebar, Cl.Backbone.View, {
    events : {
        'click #job-starter' : 'onStartJobClick'
    },
    onStartJobClick : function(e) {
        e.preventDefault();
        $CL.app().router.forward('jobs_jobrun_start', {jobname: this.data.name});
    }
});