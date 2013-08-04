var Partial = $CL.namespace('Ginger.Dashboard.View.Index.Partial');

$CL.require('Cl.Backbone.View');

Partial.LatestJobruns = function() {};

Partial.LatestJobruns = $CL.extendClass(Partial.LatestJobruns, Cl.Backbone.View, {
    data : null,
    events : {
        'click .js-jobrun-row .js-show-details' : 'onShowDetailsClick',
        'click .js-jobrun-details .js-remove' : 'onRemoveJobrunClick'
    },
    render : function() {
        //only start rendering, if we have data, otherwise a loading image is displayed
        if (!_.isNull(this.data)) {
            Cl.Backbone.View.prototype.render.apply(this);
            this.$el.removeClass('loading loading-left');
        }
    },
    updateData : function(data) {
        this.data = data;
        this.render();
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

        var jobrun = _.findWhere(this.data.jobruns, {id : $a.data('id')});

        $CL.app().router.forward('jobs_jobrun_remove', {
            jobname : jobrun.jobName,
            id : $a.data('id'),
            callback : $CL.bind(function(){
                $CL.app().router.forward('dashboard');
            }, this)
        });
    }
});