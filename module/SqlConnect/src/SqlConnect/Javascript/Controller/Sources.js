var Controller = $CL.namespace("SqlConnect.Controller");

$CL.require("Cl.Application.Mvc.AbstractController");

Controller.Sources = function() {};

Controller.Sources = $CL.extendClass(Controller.Sources, Cl.Application.Mvc.AbstractController, {
    connectionsCollection : null,
    sourceCollection : null,
    setSourceCollection : function(sourceCollection) {
        this.sourceCollection = sourceCollection;
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
                    type : "source"
                },
                connections = col.toJSON();

                connections = _.where(connections, {isSource : true});

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
        var sourceId = this.getMvcEvent().getRouteMatch().getParam('id'),
        connection = this.getMvcEvent().getRouteMatch().getParam('connection');

        this.sourceCollection.setConnection(connection);

        var source = this.sourceCollection.get(sourceId);
        $CL.get("application").wait();

        if (!source) {
            source = $CL.makeObj('SqlConnect.Entity.Source', {id : sourceId});
            this.sourceCollection.add(source);
        }

        source.fetch({
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
    addBreadcrumbs : function (sourceName, connection) {

        var sourcesParams = {};

        if ($CL.isDefined(connection)) {
            sourcesParams.connection = connection;
        }

        var breadcrumbs = [
            {link : helpers.uri('sqlconnect_index'), label : 'SqlConnect'},
            {link : helpers.uri('sqlconnect_sources', sourcesParams), label : $CL.translate('SQLCONNECT::BUTTON::MANAGE_SOURCES')}
        ];

        if ($CL.isDefined(sourceName)) {
            breadcrumbs.push({
                link : '',
                label : sourceName
            });
        }

        this.getMvcEvent().setParam(
            'breadcrumbs',
            breadcrumbs
        );
    }
});


