var Index = $CL.namespace('Ginger.Jobs.View.Index');

$CL.require('Cl.Backbone.View');

Index.Edit = function() {};

Index.Edit = $CL.extendClass(Index.Edit, Cl.Backbone.View, {
    events : {
        'click .js-add-task' : 'onTaskAddClick',
        'click .js-task .js-edit' : 'onTaskEditClick',
        'click .js-task .js-remove' : 'onTaskRemoveClick',
        'click label[for=break_on_failure]' : 'onBreakOnFailureClick',
        'click .js-save-btn' : 'onSaveClick',
        'click .js-remove-job-btn' : 'onRemoveJobClick'
    },
    onTaskAddClick : function(e) {
        //just trigger save job in the background, action is triggered by a.href, so do not prevent default behavior here
        $CL.app().router.forward('jobs_job_save', {
            jobname : this.data.name,
            jobData : this._getJobData()
        });
    },
    onTaskEditClick : function(e) {
        e.preventDefault();

        var $a = $CL.jTarget(e.target, 'a');

        $CL.app().router.forward('jobs_job_save', {
            jobname : this.data.name,
            jobData : this._getJobData()
        });

        $CL.app().router.callRoute('jobs_task_edit', {
            jobname : this.data.name,
            id : $a.data('id')
        });
    },
    onTaskRemoveClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'a');

        $CL.app().router.forward('jobs_task_remove', {jobname : this.data.name, id : $a.data('id')});

        $a.parents('.ts-tr').remove();
    },
    onBreakOnFailureClick : function(e) {
        var $label = $CL.jTarget(e.target, 'label');

        $label.find('input').attr('checked', 'checked');
    },
    onSaveClick : function(e) {
        e.preventDefault();

        $CL.app().router.forward('jobs_job_save', {
            jobname : this.data.name,
            jobData : this._getJobData(),
            callback : $CL.bind(function() {
                $CL.app().router.callRoute('jobs_job', {name : this.data.name});
            }, this)
        });
    },
    onRemoveJobClick : function(e) {
        $CL.app().router.forward('jobs_job_remove', {
            jobname : this.data.name,
            callback : $CL.bind(function() {
                $CL.app().router.callRoute('jobs_overview');
            }, this)
        });
    },
    _getJobData : function() {
        return {
            description : this.$el.find('textarea[name=description]').val(),
            break_on_failure : this.$el.find('input[name=break_on_failure]').filter(':checked').val()
        };
    }
});