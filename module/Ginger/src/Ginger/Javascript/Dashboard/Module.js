var Dashboard = $CL.namespace("Ginger.Dashboard");

$CL.require("Cl.Application.Module.ModuleInterface");
//controller
$CL.require('Ginger.Dashboard.Controller.Index');
//Service
$CL.require('Ginger.Dashboard.Service.LatestJobruns');
//View
$CL.require("Ginger.Dashboard.View.Index.Index");
$CL.require("Ginger.Dashboard.View.Index.Sidebar");
$CL.require("Ginger.Dashboard.View.Index.Partial.Modules");
$CL.require("Ginger.Dashboard.View.Index.Partial.Module");
$CL.require("Ginger.Dashboard.View.Index.Partial.LatestJobruns");

Dashboard.Module = function() {
    this.__IMPLEMENTS__ = [Cl.Application.Module.ModuleInterface];
};

Dashboard.Module.prototype = {
    getConfig : function() {
        return {
            router : {
                routes : {
                    'dashboard' : {
                        route : 'dashboard',
                        callback : function() {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Dashboard.Module",
                                    controller : "index",
                                    action : "index"
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route;
                        }
                    }
                }
            },
            service_manager : {
                'factories' : {
                    //Controllers
                    'Ginger.Dashboard.Controller.Index' : function(sl) {
                        var c = $CL.makeObj('Ginger.Dashboard.Controller.Index');
                        c.setModulesCollection(sl.get('Ginger.Application.Collection.Modules'));
                        c.setLatestJobrunsService(sl.get('Ginger.Dashboard.Service.LatestJobruns'));
                        return c;
                    },
                    //Views
                    'Ginger.Dashboard.View.Index.Index' : function(sl) {
                        var view = $CL.makeObj('Ginger.Dashboard.View.Index.Index');
                        view.setTemplate($CL._template('dashboard_main'));
                        view.setModulesView(sl.get('Ginger.Dashboard.View.Index.Partial.Modules'));
                        view.setJobrunsView(sl.get('Ginger.Dashboard.View.Index.Partial.LatestJobruns'));
                        return view;
                    },
                    'Ginger.Dashboard.View.Index.Partial.Modules' : function(sl) {
                        var v = $CL.makeObj('Ginger.Dashboard.View.Index.Partial.Modules');
                        v.setModuleView(sl.get('Ginger.Dashboard.View.Index.Partial.Module'));
                        return v;
                    },
                    'Ginger.Dashboard.View.Index.Partial.Module' : function(sl) {
                        var v = $CL.makeObj('Ginger.Dashboard.View.Index.Partial.Module');
                        v.setTemplate($CL._template('dashboard_module'));
                        return v;
                    },
                    'Ginger.Dashboard.View.Index.Partial.LatestJobruns' : function(sl) {
                        var v = $CL.makeObj('Ginger.Dashboard.View.Index.Partial.LatestJobruns');
                        v.setTemplate($CL._template('dashboard_latest_jobruns'));
                        return v;
                    }
                }
            }
        }
    },
    getController : function(controllerName) {
        controllerName = controllerName.ucfirst();

        if ($CL.classExists("Ginger.Dashboard.Controller." + controllerName)) {
            return $CL.get("Ginger.Dashboard.Controller." + controllerName);
        } else {
            $CL.exception("unknown controllername", "Ginger.Dashboard.Module", controllerName);
        }
    }
};

