var Index = $CL.namespace('Ginger.Jobs.View.Index');

$CL.require('Cl.Backbone.View');

Index.Job = function() {};

Index.Job = $CL.extendClass(Index.Job, Cl.Backbone.View, {
    events : {
        'click .js-jobrun-row .js-show-details' : 'onShowDetailsClick',
        'click .js-jobrun-details .js-remove' : 'onRemoveJobrunClick'
    },
    onShowDetailsClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'a'),
        $details = $a.parents('.js-jobrun-row').next();

        if ($details.hasClass('hide')) {
            $a.find('i').removeClass('icon-arrow-down').addClass('icon-arrow-up');
            $details.slideDown().removeClass('hide');
        } else {
            $a.find('i').removeClass('icon-arrow-up').addClass('icon-arrow-down');
            $details.slideUp().addClass('hide');
        }
    },
    onRemoveJobrunClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'a');
        $CL.app().router.forward('jobs_jobrun_remove', {
            jobname : this.data.name,
            id : $a.data('id'),
            callback : $CL.bind(function(){
                $CL.app().router.forward('jobs_job', {name : this.data.name});
            }, this)
        });
    }
});