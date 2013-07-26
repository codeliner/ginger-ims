var Controller = $CL.namespace("Ginger.Jobs.Controller");

$CL.require("Cl.Application.Mvc.AbstractController");
$CL.require("Cl.Ajax.Queue");
$CL.require("Ginger.Jobs.Collection.Jobs");
$CL.require("Ginger.Jobs.Entity.Job");


Controller.Configuration = function() {};

Controller.Configuration = $CL.extendClass(Controller.Configuration, Cl.Application.Mvc.AbstractController, {
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
        config = this.getMvcEvent().getRouteMatch().getParam('config', {}),
        callback = this.getMvcEvent().getRouteMatch().getParam('callback', function() {}),
        configCollection = this.jobCollection.get(jobname).get('configurations');
        $CL.app().wait();
        if (!$CL.isDefined(config['id'])) {

            configCollection.create(config, {
                success : function(model) {
                    $CL.app().stopWait();
                    callback(model);
                },
                error : function() {
                    $CL.app().stopWait().alert("Creating config failed. See Browser Console for more details.");
                }
            });
        } else {
            var configEntity = configCollection.get(config.id);
            configEntity.save(config, {
                success : function(model) {
                    $CL.app().stopWait();
                    callback(model);
                },
                error : function() {
                    $CL.app().stopWait().alert("Save config data failed.");
                }
            });
        }
    },
    removeAction : function() {
        this.getMvcEvent().stopPropagation();
        var jobname = this.getMvcEvent().getRouteMatch().getParam('jobname', '-'),
        configCollection = this.jobCollection.get(jobname).get('configurations'),
        configId = this.getMvcEvent().getRouteMatch().getParam('id', 0),
        configEntity = configCollection.get(configId);

        if (configEntity) {
            configEntity.destroy().fail(function() {
                $CL.app().alert('Failed to remove config with id: ' + configEntity.get('id'));
            });
        } else {
            $CL.app().alert('Can not remove config with id: ":id". No entity found.'.replace(':id', configId));
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
                    $CL.get('Ginger.Jobs.View.Configuration.Sidebar').setData(viewData);
                    this.getMvcEvent().setResponse(this.view);
                    $CL.app().stopWait().continueDispatch(this.getMvcEvent());
                }, this);

                //we also store the logic to fetch config data in a function
                var _getConfigData = $CL.bind(function(job) {
                    var configId = this.getMvcEvent().getRouteMatch().getParam('id');

                    var config = job.get('configurations').get(configId);
                    //clone nested data too
                    return $CL.clone(config.toJSON());
                }, this);

                var viewData = {
                    sources : this.sourceCollection.toJSON(),
                    targets : this.targetCollection.toJSON(),
                    mappers : this.mapperCollection.toJSON()
                };

                //we need the config settings, so first we have to get the job and then fetch job
                //dependant config
                if (this.jobCollection.isEmpty()) {
                    this.jobCollection.fetch({
                        success : $CL.bind(function() {
                            var job = this.jobCollection.get(jobname);

                            //we need an extra call for the detailed job data, fetching the list
                            //only returns job data without dependencies (configs and jobruns)
                            job.fetch({
                                success : $CL.bind(function() {
                                    if (action == "edit") {
                                        viewData['config'] = _getConfigData(job);
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
                            viewData['config'] = _getConfigData(job);
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

        this.getMvcEvent().setParam('sidebar', $CL.get('Ginger.Jobs.View.Configuration.Sidebar'));
        this.getMvcEvent().setParam('footer', $CL.get('Ginger.Jobs.View.Configuration.Footer'));

        queue.close();

        this.getMvcEvent().stopPropagation();
        return;
    },
    _addBreadcrumbs : function(jobname, action) {

        var translations = {
            'add' : $CL.translate('HEADLINE::JOBS::CONFIGURATION::ADD'),
            'edit' : $CL.translate('HEADLINE::JOBS::CONFIGURATION::EDIT')
        };

        var breadcrumbs = [
            {link : helpers.uri('jobs_overview'), label : $CL.translate('HEADLINE::JOBS')},
            {link : helpers.uri('jobs_job_edit', {name : jobname}), label : jobname + ' ' + $CL.translate('HEADLINE::JOBS::EDIT')},
            {link : '', label : translations[action]}
        ];

        this.getMvcEvent().setParam('breadcrumbs', breadcrumbs);
    }
});