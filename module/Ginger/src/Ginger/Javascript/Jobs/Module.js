var Jobs = $CL.namespace("Ginger.Jobs");

$CL.require("Cl.Application.Module.ModuleInterface");
//controllers
$CL.require('Ginger.Jobs.Controller.Index');
$CL.require('Ginger.Jobs.Controller.Configuration');
$CL.require('Ginger.Jobs.Controller.Jobrun');
//views
$CL.require("Ginger.Jobs.View.Index.Index");
$CL.require("Ginger.Jobs.View.Index.Job");
$CL.require("Ginger.Jobs.View.Index.AddJobForm");
$CL.require("Ginger.Jobs.View.Index.Edit");
$CL.require('Ginger.Jobs.View.Index.Partial.EditSidebar');
$CL.require('Ginger.Jobs.View.Index.Partial.JobSidebar');
$CL.require("Ginger.Jobs.View.Configuration.Edit");
$CL.require("Ginger.Jobs.View.Configuration.Sidebar");
$CL.require("Ginger.Jobs.View.Configuration.Footer");
$CL.require("Ginger.Jobs.View.Jobrun.Show");
$CL.require('Ginger.Jobs.View.Jobrun.Entry');
//forms
$CL.require("Ginger.Jobs.Form.AddJob");
//collections
$CL.require("Ginger.Jobs.Collection.Jobs");
$CL.require("Ginger.Jobs.Collection.Sources");
$CL.require("Ginger.Jobs.Collection.SourceInfos");
$CL.require("Ginger.Jobs.Collection.Mappers");
$CL.require("Ginger.Jobs.Collection.Targets");
$CL.require("Ginger.Jobs.Collection.TargetInfos");
$CL.require("Ginger.Jobs.Collection.Features");
$CL.require('Ginger.Jobs.Collection.LatestJobruns');

Jobs.Module = function() {
    this.__IMPLEMENTS__ = [Cl.Application.Module.ModuleInterface];
};

Jobs.Module.prototype = {
    getConfig : function() {
        return {
            router : {
                routes : {
                    'jobs_overview' : {
                        route : 'jobs/overview/',
                        callback : function() {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "index",
                                    action : "index"
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route;
                        }
                    },
                    'jobs_add_form' : {
                        route : 'jobs/add/form/',
                        callback : function() {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "index",
                                    action : "addJobForm"
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route;
                        }
                    },
                    'jobs_add_job' : {
                        route : 'jobs/add/',
                        jobData : {},
                        callback : function() {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "index",
                                    action : "addJob",
                                    params : {
                                        jobData : this.jobData
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            this.jobData = routeParams.jobData;
                            return this.route;
                        }
                    },
                    'jobs_job' : {
                        route : 'jobs/:name',
                        callback : function(name) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "index",
                                    action : "job",
                                    params : {
                                        name : name
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route.replace(':name', routeParams.name);
                        }
                    },
                    'jobs_job_edit' : {
                        route : 'jobs/update/:name',
                        callback : function(name) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "index",
                                    action : "edit",
                                    params : {
                                        name : name
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route.replace(':name', routeParams.name);
                        }
                    },
                    'jobs_job_save' : {
                        route : 'jobs/save/:jobname',
                        jobData : {},
                        customCallback : function() {},
                        callback : function(jobname) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "index",
                                    action : "save",
                                    params : {
                                        jobname : jobname,
                                        jobData : this.jobData,
                                        callback : this.customCallback
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            this.jobData = routeParams.jobData;
                            if ($CL.isDefined(routeParams.callback)) {
                                this.customCallback = routeParams.callback;
                            } else {
                                this.customCallback = function() {};
                            }
                            return this.route.replace(':jobname', routeParams.jobname);
                        }
                    },
                    'jobs_job_remove' : {
                        route : 'jobs/remove/:jobname',
                        customCallback : function() {},
                        callback : function(jobname) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "index",
                                    action : "remove",
                                    params : {
                                        jobname : jobname,
                                        callback : this.customCallback
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            if ($CL.isDefined(routeParams.callback)) {
                                this.customCallback = routeParams.callback;
                            } else {
                                this.customCallback = function() {};
                            }
                            return this.route.replace(':jobname', routeParams.jobname);
                        }
                    },
                    'jobs_jobrun' : {
                        route : 'jobs/:jobname/jobrun/:id',
                        callback : function(jobname, id) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "jobrun",
                                    action : "show",
                                    params : {
                                        jobname : jobname,
                                        id : id
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route.replace(':jobname', routeParams.jobname).replace(':id', routeParams.id);
                        }
                    },
                    'jobs_jobrun_start' : {
                        route : 'jobs/:jobname/jobrun/start/',
                        callback : function(jobname) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "jobrun",
                                    action : "start",
                                    params : {
                                        jobname : jobname
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route.replace(':jobname', routeParams.jobname);
                        }
                    },
                    'jobs_jobrun_run' : {
                        route : 'jobs/:jobname/jobrun/:id/run/',
                        callback : function(jobname, id) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "jobrun",
                                    action : "run",
                                    params : {
                                        jobname : jobname,
                                        id : id
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route.replace(':jobname', routeParams.jobname).replace(':id', routeParams.id);
                        }
                    },
                    'jobs_jobrun_refresh' : {
                        route : 'jobs/:jobname/jobrun/:id/refresh/',
                        callback : function(jobname, id) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "jobrun",
                                    action : "refresh",
                                    params : {
                                        jobname : jobname,
                                        id : id
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route.replace(':jobname', routeParams.jobname).replace(':id', routeParams.id);
                        }
                    },
                    'jobs_jobrun_remove' : {
                        route : 'jobs/:jobname/jobrun/:id/remove/',
                        customCallback : function() {},
                        callback : function(jobname, id) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "jobrun",
                                    action : "remove",
                                    params : {
                                        jobname : jobname,
                                        id : id,
                                        callback : this.customCallback
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            this.customCallback = routeParams.callback;
                            return this.route.replace(':jobname', routeParams.jobname).replace(':id', routeParams.id);
                        }
                    },
                    'jobs_configuration_add' : {
                        route : 'jobs/:jobname/config/add/',
                        callback : function(jobname) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "configuration",
                                    action : "add",
                                    params : {
                                        jobname : jobname
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route.replace(':jobname', routeParams.jobname);
                        }
                    },
                    'jobs_configuration_edit' : {
                        route : 'jobs/:jobname/config/edit/:id',
                        callback : function(jobname, id) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "configuration",
                                    action : "edit",
                                    params : {
                                        jobname : jobname,
                                        id : id
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route.replace(':jobname', routeParams.jobname).replace(':id', routeParams.id);
                        }
                    },
                    'jobs_configuration_save' : {
                        route : 'jobs/:jobname/config/save/',
                        config : null,
                        customCallback : function() {},
                        callback : function(jobname) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "configuration",
                                    action : "save",
                                    params : {
                                        jobname : jobname,
                                        config : this.config,
                                        callback : this.customCallback
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            this.config = routeParams.config;
                            if ($CL.isDefined(routeParams['callback'])) {
                                this.customCallback = routeParams.callback;
                            }

                            return this.route.replace(':jobname', routeParams.jobname);
                        }
                    },
                    'jobs_configuration_remove' : {
                        route : 'jobs/:jobname/config/remove/:id',
                        config : null,
                        callback : function(jobname, id) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Jobs.Module",
                                    controller : "configuration",
                                    action : "remove",
                                    params : {
                                        jobname : jobname,
                                        id : id
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route.replace(':jobname', routeParams.jobname).replace(':id', routeParams.id);
                        }
                    }
                }
            },
            service_manager : {
                'factories' : {
                    //Controllers
                    'Ginger.Jobs.Controller.Index' : function(sl) {
                        var c = $CL.makeObj('Ginger.Jobs.Controller.Index');
                        c.setJobCollection(sl.get('Ginger.Jobs.Collection.Jobs'));
                        return c;
                    },
                    'Ginger.Jobs.Controller.Configuration' : function(sl) {
                        var c = $CL.makeObj('Ginger.Jobs.Controller.Configuration');
                        c.setJobCollection(sl.get('Ginger.Jobs.Collection.Jobs'));
                        c.setSourceCollection(sl.get('Ginger.Jobs.Collection.Sources'));
                        c.setTargetCollection(sl.get('Ginger.Jobs.Collection.Targets'));
                        c.setMapperCollection(sl.get('Ginger.Jobs.Collection.Mappers'));
                        c.setView(sl.get('Ginger.Jobs.View.Configuration.Add'));
                        return c;
                    },
                    'Ginger.Jobs.Controller.Jobrun' : function(sl) {
                        var c = $CL.makeObj('Ginger.Jobs.Controller.Jobrun');
                        c.setJobCollection(sl.get('Ginger.Jobs.Collection.Jobs'));
                        return c;
                    },
                    //Views
                    'Ginger.Jobs.View.Index.Index' : function(sl) {
                        var v = $CL.makeObj('Ginger.Jobs.View.Index.Index');
                        v.setTemplate($CL._template('jobs_index'));
                        return v;
                    },
                    'Ginger.Jobs.View.Index.Job' : function(sl) {
                        var v = $CL.makeObj('Ginger.Jobs.View.Index.Job');
                        v.setTemplate($CL._template('jobs_job'));
                        return v;
                    },
                    'Ginger.Jobs.View.Index.AddJobForm' : function(sl) {
                        var v = $CL.makeObj('Ginger.Jobs.View.Index.AddJobForm');
                        v.setForm($CL.makeObj('Ginger.Jobs.Form.AddJob'));
                        v.setSubmitCallback(function(formData) {
                            $CL.app().router.forward('jobs_add_job', {jobData : formData})
                        });
                        v.setTemplate($CL._template('jobs_add'));
                        return v;
                    },
                    'Ginger.Jobs.View.Index.Edit' : function(sl) {
                        var v = $CL.makeObj('Ginger.Jobs.View.Index.Edit');
                        v.setTemplate($CL._template('jobs_job_edit'));
                        return v;
                    },
                    'Ginger.Jobs.View.Index.Partial.EditSidebar' : function(sl) {
                        var v = $CL.makeObj('Ginger.Jobs.View.Index.Partial.EditSidebar');
                        v.setTemplate($CL._template('jobs_job_edit_sidebar'));
                        return v;
                    },
                    'Ginger.Jobs.View.Index.Partial.JobSidebar' : function(sl) {
                        var v = $CL.makeObj('Ginger.Jobs.View.Index.Partial.JobSidebar');
                        v.setTemplate($CL._template('jobs_job_sidebar'));
                        return v;
                    },
                    'Ginger.Jobs.View.Configuration.Edit' : function(sl) {
                        var v = $CL.makeObj('Ginger.Jobs.View.Configuration.Edit');
                        v.setTemplate($CL._template('jobs_config_edit'));
                        v.setElementLoader(sl.get('module_element_loader'));
                        var footer = sl.get('Ginger.Jobs.View.Configuration.Footer');
                        footer.on('save', v.onConfigSave, v);
                        footer.on('cancel', v.onConfigCancel, v);
                        footer.on('export', v.onConfigExport, v);
                        footer.on('import', v.onConfigImport, v);
                        v.setFooter(footer);
                        return v;
                    },
                    'Ginger.Jobs.View.Configuration.Sidebar' : function(sl) {
                        var v = $CL.makeObj('Ginger.Jobs.View.Configuration.Sidebar');
                        v.setTemplate($CL._template('jobs_config_sidebar'));
                        v.setElementLoader(sl.get('module_element_loader'));
                        v.setFeatureCollection(sl.get('Ginger.Jobs.Collection.Features'));
                        var editView = sl.get('Ginger.Jobs.View.Configuration.Edit');
                        editView.on('enableFeatures', v.onEnableFeatures, v);
                        editView.on('disableFeatures', v.onDisableFeatures, v);
                        editView.on('config-save', v.onConfigSave, v);
                        v.setMainEditView(editView);
                        return v;
                    },
                    'Ginger.Jobs.View.Configuration.Footer' : function(sl) {
                        var v = $CL.makeObj('Ginger.Jobs.View.Configuration.Footer');
                        v.setTemplate($CL._template('jobs_config_footer'));
                        return v;
                    },
                    'Ginger.Jobs.View.Configuration.Add' : function(sl) {
                        return sl.get('Ginger.Jobs.View.Configuration.Edit');
                    },
                    'Ginger.Jobs.View.Jobrun.Show' : function(sl) {
                        var v = $CL.makeObj('Ginger.Jobs.View.Jobrun.Show');
                        v.setTemplate($CL._template('jobs_jobrun_show'));
                        return v;
                    },
                    'Ginger.Jobs.View.Jobrun.Entry' : function(sl) {
                        var v = $CL.makeObj('Ginger.Jobs.View.Jobrun.Entry');
                        v.setTemplate($CL._template('jobs_jobrun_entry'));
                        return v;
                    }
                },
                'non_shared_services' : [
                    'Ginger.Jobs.View.Jobrun.Entry'
                ]
            }
        }
    },
    getController : function(controllerName) {
        controllerName = controllerName.ucfirst();

        if ($CL.classExists("Ginger.Jobs.Controller." + controllerName)) {
            return $CL.get("Ginger.Jobs.Controller." + controllerName);
        } else {
            $CL.exception("unknown controllername", "Ginger.Jobs.Module", controllerName);
        }
    }
};

