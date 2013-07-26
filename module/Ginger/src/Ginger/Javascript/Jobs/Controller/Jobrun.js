var Controller = $CL.namespace("Ginger.Jobs.Controller");

$CL.require("Cl.Application.Mvc.AbstractController");
$CL.require("Ginger.Jobs.Collection.Jobs");
$CL.require("Ginger.Jobs.Entity.Job");

Controller.Jobrun = function() {};

Controller.Jobrun = $CL.extendClass(Controller.Jobrun, Cl.Application.Mvc.AbstractController, {
    jobCollection : null,
    setJobCollection : function(jobCollection) {
        this.jobCollection = jobCollection;
    },
    showAction : function() {
        var jobname = this.getMvcEvent().getRouteMatch().getParam('jobname', ''),
        runId = this.getMvcEvent().getRouteMatch().getParam('id', -1),
        _processJob  = $CL.bind(function(job) {
            if (!job) {
                $CL.app().stopWait().alert('The job ":jobname" is not present in the job collection'.replace(':jobname', jobname));
                return;
            }

            var _finish = $CL.bind(function(job) {
                var jobrun = job.get('jobruns').get(runId);

                if (!jobrun) {
                    $CL.app().alert('Jobrun with id ":id" can not be found.'.replace(':id', runId));
                    return;
                }

                this._addBreadcrumbs(jobname, helpers.datetime(jobrun.get('startTime')));
                return $CL.get('Ginger.Jobs.View.Jobrun.Show').setData(
                    _.extend({
                        jobname : jobname,
                        configurations : job.get('configurations').toJSON()
                    }, jobrun.toJSON())
                );
            }, this);

            if (job.get('jobruns').isEmpty() || !job.get('jobruns').get(runId)) {
                job.fetch({
                    success : $CL.bind(function(job) {
                        //wait is only set when jobCollection is empty, the refreshing of the job (if it is running at the moment)
                        //is done in the background,
                        //but we call stopWait() here everytime, to unset the wait status
                        //as late as possible in the first round
                        $CL.app().stopWait();
                        $CL.app().continueDispatch(this.getMvcEvent().setResponse(_finish(job)));
                        this.getMvcEvent().stopPropagation();
                    }, this),
                    error : function() {
                        $CL.app().alert('Fetching data for job failed');
                    }
                });

                this.getMvcEvent().stopPropagation();
                return;
            }

            return _finish(job);
        }, this);

        if (this.jobCollection.isEmpty()) {
            $CL.app().wait();
            this.jobCollection.fetch({
                success : $CL.bind(function(coll) {
                    _processJob(coll.get(jobname));
                }, this),
                error : function(coll, jqX) {
                    $CL.app().stopWait().alert('Failed to fetch data for jobs.\nServer Response: ' + jqX.responseText);
                }
            });
            this.getMvcEvent().stopPropagation();
            return;
        }

        return _processJob(this.jobCollection.get(jobname));
    },
    startAction : function() {
        var jobName = this.getMvcEvent().getRouteMatch().getParam('jobname', '-'),
        _startJob = function(job) {
            var jobRun = job.get('jobruns').create({}, {
                success : function(jobRun) {
                    var runCount = job.get('jobrun_count');
                    job.set('jobrun_count', ++runCount);
                    $CL.app().router.forward('jobs_jobrun_run', {jobname : jobName, id : jobRun.get('id')});
                },
                error : function(entity, jqXhr) {
                    $CL.app().alert('Failed starting new jobrun for job ":jobname". Server says: '.replace(':jobname', jobName) + jqXhr.responseText);
                }
            });
        };

        if (this.jobCollection.isEmpty()) {
            this.jobCollection.fetch({
                success : function() {
                    _startJob(this.jobCollection.get(jobName));
                },
                error : function(collection, jqXhr) {
                    $CL.app().alert('Failed to fetch job collection data. Server says: ' + jqXhr.responseText);
                }
            });

            this.getMvcEvent().stopPropagation();
            return;
        }
        this.getMvcEvent().stopPropagation();
        _startJob(this.jobCollection.get(jobName));
    },
    runAction : function() {
        var jobName = this.getMvcEvent().getRouteMatch().getParam('jobname', '-'),
        id = this.getMvcEvent().getRouteMatch().getParam('id', -1),
        _showJobRun = $CL.bind(function(job) {
            var _finish = $CL.bind(function(job) {
                var jobRun = job.get('jobruns').get(id);

                if (!jobRun) {
                    $CL.app().alert('Can not find jobrun with id ":id" for job ":jobname"'.replace(':id', id).replace(':jobname', jobName));
                }

                jobRun.sync("update", jobRun, {
                    success : function(jobRunData) {
                        if (jobRunData.success) {
                            var successCount = job.get('jobrun_count_success');
                            job.set('jobrun_count_success', ++successCount);
                        } else {
                            var failedCount = job.get('jobrun_count_failed');
                            job.set('jobrun_count_failed', ++failedCount);
                        }
                    },
                    error : function(jqX) {
                        $CL.app().alert("Jobrun failed.", jqX);
                    }
                });

                //jobrun.update can take some time, but we need the startTime, so we call fetch directly
                //to get the jobrun status after start
                jobRun.fetch().done(function() {
                    $CL.app().router.callRoute('jobs_jobrun', {jobname: job.get('name'), id : id});
                }).fail(function(jqX) {
                    $CL.app().alert("Failed to refresh jobrun data after jobrun start.", jqX);
                });
            }, this);

            if (job.get('jobruns').isEmpty()) {
                $CL.app().wait();
                job.fetch({
                    success : $CL.bind(function(job) {
                        $CL.app().stopWait();
                        _finish(job);
                    }, this),
                    error : function() {
                        $CL.app().alert('Fetching data for job failed');
                    }
                });

                this.getMvcEvent().stopPropagation();
                return;
            }

            this.getMvcEvent().stopPropagation();
            _finish(job);
        }, this);

        if (this.jobCollection.isEmpty()) {
            this.jobCollection.fetch({
                success : $CL.bind(function() {
                    _showJobRun(this.jobCollection.get(jobName));
                }, this),
                error : function(collection, jqXhr) {
                    $CL.app().alert('Failed to fetch job collection data.', jqXhr);
                }
            });

            this.getMvcEvent().stopPropagation();
            return;
        }

        this.getMvcEvent().stopPropagation();
        _showJobRun(this.jobCollection.get(jobName));
    },
    refreshAction : function() {
        var jobName = this.getMvcEvent().getRouteMatch().getParam('jobname', '-'),
        id = this.getMvcEvent().getRouteMatch().getParam('id', -1),
        jobrun = this.jobCollection.get(jobName).get('jobruns').get(id);

        jobrun.fetch().done(function() {
            $CL.app().router.forward('jobs_jobrun', {jobname : jobName, id : id});
        }).fail(function(jqX) {
            $CL.app().alert("Failed to refresh jobrun.", jqX);
        });

        this.getMvcEvent().stopPropagation();
    },
    removeAction : function() {
        this.getMvcEvent().stopPropagation();
        var jobName = this.getMvcEvent().getRouteMatch().getParam('jobname', '-'),
        id = this.getMvcEvent().getRouteMatch().getParam('id', -1),
        callback = this.getMvcEvent().getRouteMatch().getParam('callback', function(){}),
        _removeJobRun = $CL.bind(function(job) {
            var _finish = $CL.bind(function(job) {
                var jobRun = job.get('jobruns').get(id);

                if (!jobRun) {
                    $CL.app().alert('Can not find jobrun with id ":id" for job ":jobname"'.replace(':id', id).replace(':jobname', jobName));
                }
                $CL.app().wait();

                jobRun.destroy()
                .done(function() {
                    job.fetch().done(function() {
                        callback();
                    }).fail(function(jqX) {
                        $CL.app().alert("Failed to fetch data for job with name :name".replace(':name', job.get('name')), jqX);
                    }).always(function() {
                        $CL.app().stopWait();
                    });

                }).fail(function(jqX) {
                    $CL.app().stopWait().alert("Jobrun remove failed.", jqX);
                });
            }, this);

            if (job.get('jobruns').isEmpty()) {
                $CL.app().wait();
                job.fetch({
                    success : $CL.bind(function(job) {
                        $CL.app().stopWait();
                        _finish(job);
                    }, this),
                    error : function() {
                        $CL.app().alert('Fetching data for job failed');
                    }
                });
                return;
            }

            _finish(job);
        }, this);

        if (this.jobCollection.isEmpty()) {
            this.jobCollection.fetch({
                success : $CL.bind(function() {
                    _removeJobRun(this.jobCollection.get(jobName));
                }, this),
                error : function(collection, jqXhr) {
                    $CL.app().alert('Failed to fetch job collection data. Server says: ' + jqXhr.responseText);
                }
            });
            return;
        }

        _removeJobRun(this.jobCollection.get(jobName));
        return;
    },
    _addBreadcrumbs : function(jobname, startDate) {

        var breadcrumbs = [
            {link : helpers.uri('jobs_overview'), label : $CL.translate('HEADLINE::JOBS')},
            {link : helpers.uri('jobs_job', {name : jobname}), label : jobname},
            {link : '', label : startDate}
        ];

        this.getMvcEvent().setParam(
            'breadcrumbs',
            breadcrumbs
        );
    }
});
