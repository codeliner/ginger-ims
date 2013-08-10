var Controller = $CL.namespace("SqlConnect.Controller");

$CL.require("Cl.Application.Mvc.AbstractController");
$CL.require("SqlConnect.View.Index.Index");
$CL.require("SqlConnect.View.Index.Configuration");
$CL.require("SqlConnect.View.Index.ConfigurationEdit");

Controller.Index = function() {};

Controller.Index = $CL.extendClass(Controller.Index, Cl.Application.Mvc.AbstractController, {
    moduleCollection : null,
    connectionsCollection : null,
    setModuleCollection : function(moduleCollection) {
        this.moduleCollection = moduleCollection;
    },
    setConnectionsCollection : function(connectionsCollection) {
        this.connectionsCollection = connectionsCollection;
    },
    indexAction : function()
    {
        this.getMvcEvent().setParam('breadcrumbs', [{link : helpers.uri('sqlconnect_index'), label : 'SqlConnect'}]);

        var sqlConnectModule = this.moduleCollection.get('SqlConnect'),
        _checkModuleConfiguration = $CL.bind(function(model) {
            if ($CL.isEmpty(model.get("configuration"))) {
                $CL.app().router.callRoute('sqlconnect_configuration');
            } else {
                return {};
            }
            return false;
        }, this);

        if (!sqlConnectModule.get('configuration') || $CL.isEmpty(sqlConnectModule.get('configuration'))) {
            $CL.app().wait();
            this.getMvcEvent().stopPropagation();
            sqlConnectModule.fetch({
                'success' : $CL.bind(function(model) {
                    $CL.app().stopWait();
                    var response = _checkModuleConfiguration(model);

                    if (response !== false) {
                        $CL.app().continueDispatch(this.getMvcEvent().setResponse(response));
                    }
                }, this),
                'error' : function(model, jqX) {
                    $CL.app().stopWait().alert('Failed fetching module config. Server Response: ' + jqX.responseText);
                }
            });
            return;
        }

        response = _checkModuleConfiguration(sqlConnectModule);

        if (response === false) {
            this.getMvcEvent().stopPropagation();
            return;
        } else {
            return response;
        }
    },
    configurationAction : function() {
        this.getMvcEvent().setParam('breadcrumbs', [
            {link : helpers.uri('sqlconnect_index'), label : 'SqlConnect'},
            {link : '', label : 'SqlConnect - ' + $CL.translate('GENERAL::CONFIGURATION')}
        ]);

        var sqlConnectModule = this.moduleCollection.get('SqlConnect'),
        _processConfiguration = $CL.bind(function(model) {
            return model.get('configuration');
        }, this);

        if (!sqlConnectModule.get('configuration') || $CL.isEmpty(sqlConnectModule.get('configuration'))) {
            $CL.app().wait();
            this.getMvcEvent().stopPropagation();
            sqlConnectModule.fetch({
                'success' : $CL.bind(function(model) {
                    $CL.app().stopWait().continueDispatch(
                        this.getMvcEvent().setResponse(_processConfiguration(model))
                    );
                }, this),
                'error' : function(model, jqX) {
                    $CL.app().stopWait().alert('Failed fetching module config. Server Response: ' + jqX.responseText);
                }
            });
            return;
        }

        return _processConfiguration(sqlConnectModule);

    },
    configurationEditAction : function() {
        this.getMvcEvent().setParam('breadcrumbs', [
            {link : helpers.uri('sqlconnect_index'), label : 'SqlConnect'},
            {link : helpers.uri('sqlconnect_configuration'), label : 'SqlConnect - ' + $CL.translate('GENERAL::CONFIGURATION')},
            {link : '', label : 'SqlConnect - ' + $CL.translate('SQLCONNECT::DATABASE_CONNECTION_EDIT')}
        ]);

        var connectionName = this.getMvcEvent().getRouteMatch().getParam('connection'),
        connection,
        viewData = {},
        _processConnectionEdit = function(connection) {
            var viewData = connection.toJSON();
            viewData.isNew = false;
            return viewData;
        };


        if (connectionName) {

            connection = this.connectionsCollection.get(connectionName);

            if (!connection) {
                var config = this.moduleCollection.get('SqlConnect');
                if (config && config.connections) {
                    _.each(config.connections, function(conData, conName){
                        if (conName == connectionName) {
                            connection = this.connectionsCollection.add({name : conName});
                            connection.set(conData);
                        }
                     });
                }

                if (!connection) {
                    $CL.app().wait();
                    this.getMvcEvent().stopPropagation();
                    this.connectionsCollection.fetch({
                        success : $CL.bind(function(collection) {
                            $CL.app().stopWait();
                            connection = collection.get(connectionName);

                            if (!connection) {
                                $CL.app().alert('Connection ":connection" can not be found.'.replace(':connection', connectionName));
                            }

                            $CL.app().continueDispatch(this.getMvcEvent().setResponse(_processConnectionEdit(connection)));
                        }, this),
                        error : function(col, jqX) {
                            $CL.app().stopWait().alert("Failed fetching connections. Server Response: " + jqX.responseText);
                        }
                    });

                    return;
                }
            }


            return _processConnectionEdit(connection);
        } else {
            viewData.isNew = true;
        }

        return viewData;
    },
    configurationSaveAction : function() {
        var config = this.getMvcEvent().getRouteMatch().getParam('connectionConfig', {});

        if (config.isNew) {
            var connection = $CL.makeObj('SqlConnect.Entity.Connection');
            this.connectionsCollection.add(connection);
            delete config.isNew;
            connection.set(config);

            $CL.app().wait();

            connection.sync("create",connection,
                {
                    success : $CL.bind(function(response){
                        var sqlConnectModule = this.moduleCollection.get('SqlConnect');

                        sqlConnectModule.fetch({
                            success : function() {
                                $CL.app().stopWait().router.callRoute('sqlconnect_configuration');
                            },
                            error : function(model, jqX) {
                                $CL.app().stopWait().alert("Failed refreshing SqlConnect configuration. Server Response: " + jqX.responseText);
                            }
                        });

                    }, this),
                    error : $CL.bind(function(jqXhr) {
                        $CL.app().stopWait().alert("Failed saving connection config. Server Response: " + jqXhr.responseText);
                    }, this)
                }
            );
        } else {
            connection = this.connectionsCollection.get(config.name);
            delete config.isNew;
            $CL.app().wait();
            connection.save(config).done($CL.bind(function() {
                var sqlConnectModule = this.moduleCollection.get('SqlConnect');

                sqlConnectModule.fetch({
                    success : function() {
                        $CL.app().stopWait().router.callRoute('sqlconnect_configuration');
                    },
                    error : function(model, jqX) {
                        $CL.app().stopWait().alert("Failed refreshing SqlConnect configuration. Server Response: " + jqX.responseText);
                    }
                });
            }, this)).fail(function(jqX) {
                $CL.app().stopWait().alert('Failed saving connection. Server Resposne: ' + jqX.responseText);
            });
        }

        this.getMvcEvent().stopPropagation();
        return;
    },
    configurationRemoveAction : function() {
        var connectionName = this.getMvcEvent().getRouteMatch().getParam('connection'),
        callback = this.getMvcEvent().getRouteMatch().getParam('callback'),
        connection = this.connectionsCollection.get(connectionName),
        _processConnection = function(model) {
            model.destroy().done(function(){
                callback(true);
            }).fail(function(jqX) {
                $CL.app().alert("Failed removing connection. Server Response: " + jqX.responseText);
                callback(false);
            });
        };

        this.getMvcEvent().stopPropagation();


        if (!connection) {
            this.connectionsCollection.fetch({
                'success' : function(col) {
                    connection = col.get(connectionName);

                    if (!connection) {
                        $CL.app().alert('Can not find connection ":connection"'.replace(':connection', connectionName));
                        callback(false);
                    }

                    _processConnection(connection);
                },
                'error' : function(col, jqX) {
                    $CL.app().alert("Failed fetching connections. Server Response: " + jqX.responseText);
                    callback(false);
                }
            })

            return;
        }

        _processConnection(connection);
    }
});


