var Partial = $CL.namespace('Ginger.Jobs.View.Index.Partial');

$CL.require('Cl.Backbone.View');

Partial.JobSidebar = function() {};

Partial.JobSidebar = $CL.extendClass(Partial.JobSidebar, Cl.Backbone.View, {
    events : {
        'click label[for=job-edit]' : 'onJobEditClick'
    },
    onJobEditClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'label').find('.js-edit');
        $CL.app().router.callRoute('jobs_job_edit', {name : $a.data('id')});
    },
    render : function() {
        this.parent.prototype.render.apply(this);
        var editSidebar = $CL.get('Ginger.Jobs.View.Index.Partial.EditSidebar');
        editSidebar.setElement($('#js-edit-sidebar'));
        editSidebar.setData(_.extend({showAlways : true}, this.data));
        editSidebar.render();
    }
});