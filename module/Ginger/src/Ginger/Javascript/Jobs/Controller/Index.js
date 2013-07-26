var Controller = $CL.namespace("Ginger.Jobs.Controller");

$CL.require("Cl.Application.Mvc.AbstractController");
$CL.require("Ginger.Jobs.Collection.Jobs");
$CL.require("Ginger.Jobs.Entity.Job");

Controller.Index = function() {};

Controller.Index = $CL.extendClass(Controller.Index, Cl.Application.Mvc.AbstractController, {
    jobCollection : null,
    setJobCollection : function(jobCollection) {
        this.jobCollection = jobCollection;
    },
    indexAction : function()
    {
        this._addBreadcrumbs();

        var _processJobs = $CL.bind(function(jobs) {
            var v = $CL.get("Ginger.Jobs.View.Index.Index");
            v.setData({jobs : jobs.toJSON()});
            return v;

        }, this);

        if (this.jobCollection.isEmpty()) {
            $CL.app().wait();
            this.jobCollection.fetch().done($CL.bind(function(response) {
                $CL.app().continueDispatch(this.getMvcEvent().setResponse(
                    _processJobs(this.jobCollection)
                ));
            }, this)).fail(function() {
                $CL.app().alert('Fetching jobs failed. See browser console for details.');
            }).always(function() {
                $CL.app().stopWait();
            });

            this.getMvcEvent().stopPropagation();
            return;
        }


        return _processJobs(this.jobCollection);
    },
    jobAction : function() {
        this._addBreadcrumbs('job');

        var jobname = this.getMvcEvent().getRouteMatch().getParam('name', ''),
        _processJob  = $CL.bind(function(job) {
            if (!job) {
                $CL.app().alert('The job ":jobname" is not present in the job collection'.replace(':jobname', jobname));
                return;
            }

            var _finish = $CL.bind(function(job) {
                var jobData = job.toJSON(),
                sidebar = $CL.get('Ginger.Jobs.View.Index.Partial.JobSidebar');
                sidebar.setData(jobData);
                this.getMvcEvent().setParam('sidebar', sidebar);
                return $CL.get('Ginger.Jobs.View.Index.Job').setData(jobData);
            }, this);

            if (job.get('jobruns').isEmpty()) {
                $CL.app().wait();
                job.fetch({
                    success : $CL.bind(function(job) {
                        $CL.app().stopWait()
                        .continueDispatch(this.getMvcEvent().setResponse(_finish(job)));
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
                    $CL.app().alert('Failed to fetch data for jobs.\nServer Response: ' + jqX.responseText);
                }
            });
            this.getMvcEvent().stopPropagation();
            return;
        }

        return _processJob(this.jobCollection.get(jobname));
    },
    editAction : function() {
        var jobName = this.getMvcEvent().getRouteMatch().getParam("name", "");

        if (jobName == "") {
            $CL.app().alert('Missing job name in JobsController::editAction');
            return;
        }

        this._addBreadcrumbs('edit');

        var _processJob = $CL.bind(function() {
            var job = this.jobCollection.get(jobName);

            if (!job) {
                $CL.app().alert('Job ":jobname" could not be found. Is the name correct?'.replace(':jobname', jobName));
            }

            var _finish = $CL.bind(function(job) {
                var view = $CL.get('Ginger.Jobs.View.Index.Edit'),
                jobData = job.toJSON(),
                sidebar = $CL.get('Ginger.Jobs.View.Index.Partial.EditSidebar');

                view.setData(jobData);
                sidebar.setData(jobData);

                this.getMvcEvent().setParam('sidebar', sidebar);

                return view;
            }, this);

            if (job.get('configurations').isEmpty()) {
                $CL.app().wait();
                job.fetch({
                    success : $CL.bind(function() {
                        $CL.app().stopWait()
                        .continueDispatch(this.getMvcEvent().setResponse(_finish(job)));
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
                success : $CL.bind(function() {
                    $CL.app().stopWait();
                    var view = _processJob();

                    if (view) {
                        $CL.app().continueDispatch(this.getMvcEvent().setResponse(view));
                        this.getMvcEvent().stopPropagation();
                    }
                }, this),
                error : function(col, jqX) {
                    $CL.app().alert("Fetching data for job collection failed. Server Response: " + jqX.responseText);
                }
            });

            this.getMvcEvent().stopPropagation();
            return;
        }

        return _processJob();
    },
    addJobFormAction : function() {
        this._addBreadcrumbs('add');
        var view = $CL.get('Ginger.Jobs.View.Index.AddJobForm');

        return view;
    },
    addJobAction : function() {
        var jobData = this.getMvcEvent().getRouteMatch().getParam('jobData', {name : "", description : ""});
        var view = $CL.get('Ginger.Jobs.View.Index.AddJobForm');


        $CL.app().wait();

        var job = this.jobCollection.get(jobData.name);

        if (!job) {
            job = $CL.makeObj('Ginger.Jobs.Entity.Job');
            this.jobCollection.add(job);
        }

        job.set(jobData);

        job.sync("create",job,
            {
                success : $CL.bind(function(response){
                    $CL.app().stopWait().router.callRoute('jobs_job_edit', {name : jobData.name});
                }, this),
                error : $CL.bind(function(jqXhr, type, thrown) {
                    if (thrown == "Conflict") {
                        var response = JSON.parse(jqXhr.responseText);
                        view.form.setErrors({'name' : [{name : "ServerError", msg : response.error}]});
                        this.getMvcEvent().setResponse(view);
                        this._addBreadcrumbs();
                        $CL.app().stopWait().continueDispatch(this.getMvcEvent());
                    } else {
                        $CL.get("application").alert().stopWait();
                    }
                }, this)
            }
        );

        this.getMvcEvent().stopPropagation();
    },
    saveAction : function() {
        var jobData = this.getMvcEvent().getRouteMatch().getParam('jobData');
        var jobname = this.getMvcEvent().getRouteMatch().getParam('jobname');
        var callback = this.getMvcEvent().getRouteMatch().getParam('callback', function() {});

        var job = this.jobCollection.get(jobname);

        if (!job) {
            $CL.app().alert('Failed to save job data. Can not find the job ":jobname"'.replace(':jobname', jobname));
            return;
        }

        $CL.app().wait();
        job.save(jobData).done(function() {
            callback();
        }).fail(function(m, jqX) {
            $CL.app().alert("Failed to save job data.", jqX);
        }).always(function(){
            $CL.app().stopWait();
        });

        this.getMvcEvent().stopPropagation();
    },
    removeAction : function() {
        var jobname = this.getMvcEvent().getRouteMatch().getParam('jobname'),
        callback = this.getMvcEvent().getRouteMatch().getParam('callback', function() {}),
        job = this.jobCollection.get(jobname);

        if (!job) {
            $CL.app().alert('Can not find the job ":jobname"'.replace(':jobname', jobname));
            return;
        }

        $CL.app().wait();

        job.destroy()
        .done(function() {
            callback();
        }).fail(function(jqX) {
            $CL.app().alert('Failed to delete job.', jqX);
        })
        .always(function() {
            $CL.app().stopWait();
        });

        this.getMvcEvent().stopPropagation();
        return;
    },
    _addBreadcrumbs : function(action) {

        var indexLink = ($CL.isDefined(action))? helpers.uri('jobs_overview') : '';

        var breadcrumbs = [{link : indexLink, label : $CL.translate('HEADLINE::JOBS')}];

        if ($CL.isDefined(action)) {

            var translations = {
                add : $CL.translate('HEADLINE::JOBS::ADD'),
                job : this.getMvcEvent().getRouteMatch().getParam('name', ''),
                edit : this.getMvcEvent().getRouteMatch().getParam('name', '') + ' ' + $CL.translate('HEADLINE::JOBS::EDIT')
            };

            breadcrumbs.push({
                link : '',
                label : translations[action]
            });
        }

        this.getMvcEvent().setParam(
            'breadcrumbs',
            breadcrumbs
        );
    }
});


