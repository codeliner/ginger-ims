var Controller = $CL.namespace('Ginger.Application.Controller');

$CL.require("Cl.Application.Mvc.AbstractController");

Controller.ModuleLoader = function() {};

Controller.ModuleLoader = $CL.extendClass(Controller.ModuleLoader, Cl.Application.Mvc.AbstractController, {
    loadModuleAction : function()
    {
        this.getMvcEvent().stopPropagation();

        var moduleName = this.getMvcEvent().getRouteMatch().getParam('moduleName');
        var gotoRoute = this.getMvcEvent().getRouteMatch().getParam('gotoRoute');

        $CL.log('load module: ', moduleName, ' and go to route: ', gotoRoute);

        $CL.get('application').lazyLoadModule(moduleName, function() {
            if (gotoRoute != "-") {
                $CL.get('application').router.callRoute(gotoRoute);
            }
        });
    }
});