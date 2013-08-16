var Controller = $CL.namespace("Ginger.Jobs.Controller");

$CL.require("Cl.Application.Mvc.AbstractController");
$CL.require("Cl.Ajax.Queue");
$CL.require("Ginger.Jobs.Collection.Jobs");
$CL.require("Ginger.Jobs.Entity.Job");


Controller.Task = function() {};

Controller.Task = $CL.extendClass(Controller.Task, Cl.Application.Mvc.AbstractController, {
    jobCollection : null,
    sourceCollection : null,
    targetCollection : null,
    mapperCollection : null,
    view : null,
    setJobCollection : function(jobCollection) {
        this.jobCollection = jobCollection;
    },
    setSourceCollection : function(sourceCollection) {
        this.sourceCollection = sourceCollection;
    },
    setTargetCollection : function(targetCollection) {
        this.targetCollection = targetCollection;
    },
    setMapperCollection : function(mapperCollection) {
        this.mapperCollection = mapperCollection;
    },
    setView : function(view) {
        this.view = view;
    },
    addAction : function() {
        this._processView('add');
    },
    editAction : function() {
        this._processView('edit');
    },
    saveAction : function() {
        this.getMvcEvent().stopPropagation();

        var jobname = this.getMvcEvent().getRouteMatch().getParam('jobname', '-'),
        task = this.getMvcEvent().getRouteMatch().getParam('task', {}),
        callback = this.getMvcEvent().getRouteMatch().getParam('callback', function() {}),
        taskCollection = this.jobCollection.get(jobname).get('tasks');
        $CL.app().wait();
        if (!$CL.isDefined(task['id'])) {

            taskCollection.create(task, {
                success : function(model) {
                    $CL.app().stopWait();
                    callback(model);
                },
                error : function() {
                    $CL.app().stopWait().alert("Creating task failed. See Browser Console for more details.");
                }
            });
        } else {
            var taskEntity = taskCollection.get(task.id);
            taskEntity.save(task, {
                success : function(model) {
                    $CL.app().stopWait();
                    callback(model);
                },
                error : function() {
                    $CL.app().stopWait().alert("Save task data failed.");
                }
            });
        }
    },
    removeAction : function() {
        this.getMvcEvent().stopPropagation();
        var jobname = this.getMvcEvent().getRouteMatch().getParam('jobname', '-'),
        taskCollection = this.jobCollection.get(jobname).get('tasks'),
        taskId = this.getMvcEvent().getRouteMatch().getParam('id', 0),
        taskEntity = taskCollection.get(taskId);

        if (taskEntity) {
            taskEntity.destroy().fail(function() {
                $CL.app().alert('Failed to remove task with id: ' + taskEntity.get('id'));
            });
        } else {
            $CL.app().alert('Can not remove task with id: ":id". No entity found.'.replace(':id', taskId));
        }
    },
    _processView : function(action) {
        var jobname = this.getMvcEvent().getRouteMatch().getParam('jobname');

        var queue = $CL.makeObj('Cl.Ajax.Queue');

        queue.events().attach("start", function(){
            $CL.app().wait();
        }, 0, true);

        queue.setFinishCallback(
            $CL.bind(function() {
                this.view.setJobName(jobname);

                //we define the finish steps in a function, so we can call them in different szenarios
                var _finish  = $CL.bind(function(viewData) {
                    this.view.setData(viewData);
                    $CL.get('Ginger.Jobs.View.Task.Sidebar').setData(viewData);
                    this.getMvcEvent().setResponse(this.view);
                    $CL.app().stopWait().continueDispatch(this.getMvcEvent());
                }, this);

                //we also store the logic to fetch task data in a function
                var _getTaskData = $CL.bind(function(job) {
                    var taskId = this.getMvcEvent().getRouteMatch().getParam('id');

                    var task = job.get('tasks').get(taskId);
                    //clone nested data too
                    return $CL.clone(task.toJSON());
                }, this);

                var viewData = {
                    sources : this.sourceCollection.toJSON(),
                    targets : this.targetCollection.toJSON(),
                    mappers : this.mapperCollection.toJSON()
                };

                //we need the task settings, so first we have to get the job and then fetch job
                //dependant task
                if (this.jobCollection.isEmpty()) {
                    this.jobCollection.fetch({
                        success : $CL.bind(function() {
                            var job = this.jobCollection.get(jobname);

                            //we need an extra call for the detailed job data, fetching the list
                            //only returns job data without dependencies (tasks and jobruns)
                            job.fetch({
                                success : $CL.bind(function() {
                                    if (action == "edit") {
                                        viewData['task'] = _getTaskData(job);
                                    }
                                    _finish(viewData);
                                }, this),
                                error : function() {
                                    $CL.app().alert("Failed to fetch job data.");
                                }
                            });
                        }, this),
                        error : function() {
                            $CL.app().alert("Failed to fetch job collection.");
                        }
                    });
                    return;
                } else {
                    var job = this.jobCollection.get(jobname);

                    if (action == "edit") {
                       if (action == "edit") {
                            viewData['task'] = _getTaskData(job);
                        }
                    }
                }

                _finish(viewData);
            }, this)
        );

        if (this.sourceCollection.isEmpty()) {
            queue.addJqXhr(
                this.sourceCollection.fetch().fail(function() {
                    $CL.app().alert('Failed fetching data for available sources');
                })
            );
        }

        if (this.targetCollection.isEmpty()) {
            queue.addJqXhr(
                this.targetCollection.fetch().fail(function() {
                    $CL.app().alert('Failed fetching data for available targets');
                })
            );
        }

        if (this.mapperCollection.isEmpty()) {
            queue.addJqXhr(
                this.mapperCollection.fetch().fail(function() {
                    $CL.app().alert('Failed fetching data for available mappers');
                })
            );
        }

        this._addBreadcrumbs(jobname, action);

        this.getMvcEvent().setParam('sidebar', $CL.get('Ginger.Jobs.View.Task.Sidebar'));
        this.getMvcEvent().setParam('footer', $CL.get('Ginger.Jobs.View.Task.Footer'));

        queue.close();

        this.getMvcEvent().stopPropagation();
        return;
    },
    _addBreadcrumbs : function(jobname, action) {

        var translations = {
            'add' : $CL.translate('HEADLINE::JOBS::TASK::ADD'),
            'edit' : $CL.translate('HEADLINE::JOBS::TASK::EDIT')
        };

        var breadcrumbs = [
            {link : helpers.uri('jobs_overview'), label : $CL.translate('HEADLINE::JOBS')},
            {link : helpers.uri('jobs_job_edit', {name : jobname}), label : jobname + ' ' + $CL.translate('HEADLINE::JOBS::EDIT')},
            {link : '', label : translations[action]}
        ];

        this.getMvcEvent().setParam('breadcrumbs', breadcrumbs);
    }
});