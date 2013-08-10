var SqlConnect = $CL.namespace('SqlConnect');

$CL.require("Cl.Application.Module.AbstractModule");

//Controllers
$CL.require("SqlConnect.Controller.Index");
$CL.require("SqlConnect.Controller.Sources");
$CL.require("SqlConnect.Controller.Targets");
//Collections
$CL.require("SqlConnect.Collection.SourceTables");
$CL.require("SqlConnect.Collection.TargetTables");
$CL.require("SqlConnect.Collection.Connections");
//Models
$CL.require("SqlConnect.Model.Db.TableSource");
$CL.require("SqlConnect.Model.Db.TableTarget");
//Views
$CL.require("SqlConnect.View.Sources.Index");
$CL.require("SqlConnect.View.Sources.Show");
$CL.require("SqlConnect.View.Targets.Index");
$CL.require("SqlConnect.View.Targets.Show");


SqlConnect.Module = function() {
    this.__IMPLEMENTS__ = [Cl.Application.Module.ModuleInterface];
};

SqlConnect.Module = $CL.extendClass(SqlConnect.Module, Cl.Application.Module.AbstractModule, {
    getConfig : function() {
        return {
            router : {
                routes : {
                    'sqlconnect_index' : {
                        route : 'sqlconnect/',
                        callback : function() {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "sqlConnect",
                                    controller : "index",
                                    action : "index"
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route;
                        }
                    },
                    'sqlconnect_configuration' : {
                        route : 'sqlconnect/configuration/',
                        callback : function() {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "sqlConnect",
                                    controller : "index",
                                    action : "configuration"
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route;
                        }
                    },
                    'sqlconnect_configuration_add' : {
                        route : 'sqlconnect/configuration/add/',
                        callback : function() {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "sqlConnect",
                                    controller : "index",
                                    action : "configurationEdit",
                                    params : {
                                        connection : null
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route;
                        }
                    },
                    'sqlconnect_configuration_save' : {
                        route : 'sqlconnect/configuration/save/',
                        connectionConfig : {},
                        callback : function() {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "sqlConnect",
                                    controller : "index",
                                    action : "configurationSave",
                                    params : {
                                        connectionConfig : this.connectionConfig
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            this.connectionConfig = routeParams.connectionConfig;
                            return this.route;
                        }
                    },
                    'sqlconnect_configuration_remove' : {
                        route : 'sqlconnect/configuration/remove/:connection',
                        customCallback : function() {},
                        callback : function(connection) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "sqlConnect",
                                    controller : "index",
                                    action : "configurationRemove",
                                    params : {
                                        connection : connection,
                                        callback : this.customCallback
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            if ($CL.isDefined(routeParams.callback)) {
                                this.customCallback = routeParams.callback;
                            }

                            return this.route.replace(':connection', routeParams.connection);
                        }
                    },
                    'sqlconnect_configuration_edit' : {
                        route : 'sqlconnect/configuration/edit/:connection',
                        callback : function(connection) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "sqlConnect",
                                    controller : "index",
                                    action : "configurationEdit",
                                    params : {
                                        connection : connection
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route.replace(':connection', routeParams.connection);
                        }
                    },
                    'sqlconnect_sources' : {
                        route : 'sqlconnect/sources/:connection/',
                        callback : function(connection) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "sqlConnect",
                                    controller : "sources",
                                    action : "index",
                                    params : {
                                        connection : connection
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route.replace(':connection', routeParams.connection || 'none');
                        }
                    },
                    'sqlconnect_source' : {
                        route : 'sqlconnect/sources/:connection/:action/:id',
                        callback : function(connection, action, id) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "sqlConnect",
                                    controller : "sources",
                                    action : action,
                                    params : {
                                        id : id,
                                        connection : connection
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route
                            .replace(':connection', routeParams.connection)
                            .replace(':action', routeParams.action)
                            .replace(':id', routeParams.id);
                        }
                    },
                    'sqlconnect_targets' : {
                        route : 'sqlconnect/targets/:connection/',
                        callback : function(connection) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "sqlConnect",
                                    controller : "targets",
                                    action : "index",
                                    params : {
                                        connection : connection
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route.replace(':connection', routeParams.connection || 'none');
                        }
                    },
                    'sqlconnect_target' : {
                        route : 'sqlconnect/targets/:connection/:action/:id',
                        callback : function(connection, action, id) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "sqlConnect",
                                    controller : "targets",
                                    action : action,
                                    params : {
                                        id : id,
                                        connection : connection
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route
                            .replace(':connection', routeParams.connection)
                            .replace(':action', routeParams.action)
                            .replace(':id', routeParams.id);
                        }
                    }
                }
            },
            service_manager : {
                factories : {
                    //Controllers
                    'SqlConnect.Controller.Index' : function(sl) {
                        var c = $CL.makeObj('SqlConnect.Controller.Index');
                        c.setModuleCollection(sl.get('Ginger.Application.Collection.Modules'));
                        c.setConnectionsCollection(sl.get('SqlConnect.Collection.Connections'));
                        return c;
                    },
                    'SqlConnect.Controller.Sources' : function(sl) {
                        var c = $CL.makeObj('SqlConnect.Controller.Sources');
                        c.setSourceCollection(sl.get("SqlConnect.Collection.SourceTables"));
                        c.setConnectionsCollection(sl.get("SqlConnect.Collection.Connections"));
                        return c;
                    },
                    'SqlConnect.Controller.Targets' : function(sl) {
                        var c = $CL.makeObj('SqlConnect.Controller.Targets');
                        c.setTargetCollection(sl.get("SqlConnect.Collection.TargetTables"));
                        c.setConnectionsCollection(sl.get("SqlConnect.Collection.Connections"));
                        return c;
                    },
                    //Models
                    'SqlConnect.Model.Db.TableSource' : function(sl) {
                        var m = $CL.makeObj('SqlConnect.Model.Db.TableSource');
                        m.setSourceInfoCollection(sl.get('Ginger.Jobs.Collection.SourceInfos'));
                        return m;
                    },
                    //Views
                    'SqlConnect.View.Sources.Index' : function(sl) {
                        var v = $CL.makeObj('SqlConnect.View.Sources.Index');
                        v.setSourceCollection(sl.get('SqlConnect.Collection.SourceTables'));
                        return v;
                    },
                    'SqlConnect.View.Targets.Index': function(sl) {
                        var v = $CL.makeObj('SqlConnect.View.Targets.Index');
                        v.setTargetCollection(sl.get("SqlConnect.Collection.TargetTables"));
                        return v;
                    }
                }
            }
        };
    }
});