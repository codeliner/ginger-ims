var Jobrun = $CL.namespace('Ginger.Jobs.View.Jobrun');

$CL.require('Cl.Backbone.View');

Jobrun.Show = function() {};

Jobrun.Show = $CL.extendClass(Jobrun.Show, Cl.Backbone.View, {
    log : null,
    activeFilter : 'all',
    runningRefresh : null,
    events : {
        'click .js-more-text' : 'onMoreTextClick',
        'click .js-filter' : 'onFilterClick'
    },
    setData : function(data) {
        this.parent.prototype.setData.call(this, data);
        var jobFinished = this._prepareLog();
        this.data.log = [].concat(this.log);

        //show last message first
        this.data.log.reverse();

        if (this.runningRefresh) {
            window.clearTimeout(this.runningRefresh);
        }

        if (!jobFinished) {
            this.runningRefresh = window.setTimeout($CL.bind(function(){
                $CL.app().router.forward('jobs_jobrun_refresh', {jobname : this.data.jobname, id : this.data.id});
            }, this), 3000);
        }

        this.data.jobFinished = jobFinished;

        return this;
    },
    render : function() {
        this.parent.prototype.render.apply(this);
        this._markActiveFilter();
    },
    onMoreTextClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'a'),
        $subMsg = $a.parents('.sub-msg'),
        $fullMsg = $subMsg.next();

        if ($fullMsg.is(':hidden')) {
            $a.find('i').removeClass('icon-arrow-down').addClass('icon-arrow-up');
            $fullMsg.slideDown();
        } else {
            $fullMsg.slideUp();
            $a.find('i').removeClass('icon-arrow-up').addClass('icon-arrow-down');
        }
    },
    onFilterClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'a');

        if ($a.hasClass('js-all')) {
            this.activeFilter = 'all';
        } else if ($a.hasClass('js-success')) {
            this.activeFilter = 'success';
        } else if ($a.hasClass('js-warning')) {
            this.activeFilter = 'warning';
        } else if ($a.hasClass('js-error')) {
            this.activeFilter = 'error';
        }

        this.data.log = this._filterLog(this.activeFilter);

        this.data.log.reverse();

        this.render();
    },
    _filterLog : function(filter) {
        if (filter == "all") {
            return [].concat(this.log);
        }

        return _.where(this.log, {status : filter});
    },
    _prepareLog : function() {
        var jobrun = this.data,
        nonSuccessfulConfigrun = false,
        statusWord,
        configEndMsg,
        totalItems = 0,
        insertedItems = 0;

        this.log = [];

        this._addLogMsg('success', helpers.time(jobrun.startTime), $CL.translate('JOBS::JOBRUN::STARTED').replace(':jobname', jobrun.jobname));

        $.each(jobrun.configurationRuns, $CL.bind(function(i, configRun) {

            var configuration = _.find(this.data.configurations, function(config) {
                return config.id == configRun.configurationId;
            });

            if (nonSuccessfulConfigrun) {
                this._addLogMsg('warning', helpers.time(configRun.startTime), $CL.translate('JOBS::JOBRUN::CONTINUE_AFTER_FAILURE'));
            }

            this._addLogMsg('success', helpers.time(configRun.startTime), $CL.translate('JOBS::JOBRUN::CONFIG::STARTED').replace(':number', i+1));

            _.each(configRun.messages, function(message) {
                this._addLogMsg(
                    (message.type == "info")? 'success' : message.type,
                    helpers.time(message.timestamp),
                    message.text
                );
            }, this);

            if (!configRun.endTime) {
                return;
            }

            statusWord = (configRun.success)?
                $CL.translate('GENERAL::SUCCESSFUL') :
                $CL.translate('GENERAL::ERRORFUL');

            configEndMsg = $CL.translate('JOBS::JOBRUN::CONFIG::END')
                .replace(':number', i+1)
                .replace(':status', statusWord);

            if (configRun.totalItemCount > 0) {
                configEndMsg += ' ' + $CL.translate('JOBS::JOBRUN::CONFIG::END_ITEMS')
                    .replace(':totalItems', configRun.totalItemCount)
                    .replace(':insertedItems', configRun.insertedItemCount)
                    .replace(':pluralItemName', $CL.translatePlural(
                        'CONFIGURATION::ITEM_NAME::' + configuration.source.itemName, 2))
                    .replace(':pastAction', $CL.translate(
                        'CONFIGURATION::TARGET_ACTION_PAST::' + configuration.target.action)
                    );
            }

            this._addLogMsg(
                (configRun.success)? 'success' : 'error',
                helpers.time(configRun.endTime),
                configEndMsg
            );

            totalItems += configRun.totalItemCount;
            insertedItems += configRun.insertedItemCount;

            if (!configRun.success) {
                nonSuccessfulConfigrun = true;
            }
        },this));

        if (!jobrun.endTime) {
            return false;
        }

        statusWord = (jobrun.success)?
            $CL.translate('GENERAL::SUCCESSFUL') :
            $CL.translate('GENERAL::ERRORFUL');

        this._addLogMsg(
            (jobrun.success)? 'success' : 'error',
            helpers.time(jobrun.endTime),
            $CL.translate('JOBS::JOBRUN::END')
                .replace(':jobname', jobrun.jobname)
                .replace(':status', statusWord)
                .replace(':totalItems', totalItems)
                .replace(':insertedItems', insertedItems)
        );

        return true;
    },
    _addLogMsg : function(status, time, msg) {
        if (_.isNull(this.log)) {
            this.log = [];
        }
        this.log.push({status : status, time : time, msg : msg});
    },
    _markActiveFilter : function() {
        $('.js-' + this.activeFilter).addClass('active');
    }
});