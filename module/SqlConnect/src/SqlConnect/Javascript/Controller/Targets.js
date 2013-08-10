var Controller = $CL.namespace("SqlConnect.Controller");

$CL.require("Cl.Application.Mvc.AbstractController");

Controller.Targets = function() {};

Controller.Targets = $CL.extendClass(Controller.Targets, Cl.Application.Mvc.AbstractController, {
    connectionsCollection : null,
    targetCollection : null,
    setTargetCollection : function(targetCollection) {
        this.targetCollection = targetCollection;
    },
    setConnectionsCollection : function(connectionsCollection) {
        this.connectionsCollection = connectionsCollection;
    },
    indexAction : function()
    {
        this.getMvcEvent().stopPropagation();
        this.addBreadcrumbs();
        var connection = this.getMvcEvent().getRouteMatch().getParam('connection');

        this.connectionsCollection.fetch({
            success : $CL.bind(function(col) {
                var viewData = {
                    connection : connection,
                    type : "target"
                },
                connections = col.toJSON();

                connections = _.where(connections, {isTarget : true});

                viewData.connections = connections;
                $CL.app().stopWait().continueDispatch(this.getMvcEvent().setResponse(viewData));
            }, this),
            error : function(col, jqX) {
                $CL.app().stopWait().alert("Failed fetching connections data.", jqX);
            }
        });
    },
    showAction : function() {
        this.getMvcEvent().stopPropagation();
        var targetId = this.getMvcEvent().getRouteMatch().getParam('id'),
        connection = this.getMvcEvent().getRouteMatch().getParam('connection');

        this.targetCollection.setConnection(connection);

        var target = this.targetCollection.get(targetId);
        $CL.get("application").wait();

        if (!target) {
            target = $CL.makeObj('SqlConnect.Entity.Target', {id : targetId});
            this.targetCollection.add(target);
        }

        target.fetch({
            success : $CL.bind(function(model) {
                this.addBreadcrumbs(model.get('name'), connection);
                this.getMvcEvent().setResponse(model.toJSON());
                $CL.get("application").stopWait().continueDispatch(this.getMvcEvent());
            }, this),
            error : function() {
                $CL.get("application").alert().stopWait();
            }
        });
    },
    addBreadcrumbs : function (targetName, connection) {

        var targetsParams = {};

        if ($CL.isDefined(connection)) {
            targetsParams.connection = connection;
        }

        var breadcrumbs = [
            {link : helpers.uri('sqlconnect_index'), label : 'SqlConnect'},
            {link : helpers.uri('sqlconnect_targets', targetsParams), label : $CL.translate('SQLCONNECT::BUTTON::MANAGE_TARGETS')}
        ];

        if ($CL.isDefined(targetName)) {
            breadcrumbs.push({
                link : '',
                label : targetName
            });
        }

        this.getMvcEvent().setParam(
            'breadcrumbs',
            breadcrumbs
        );
    }
});


