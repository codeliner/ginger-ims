var Controller = $CL.namespace("Ginger.Dashboard.Controller");

$CL.require("Cl.Application.Mvc.AbstractController");

Controller.Index = function() {};

Controller.Index = $CL.extendClass(Controller.Index, Cl.Application.Mvc.AbstractController, {
    modulesCollection : null,
    latestJobrunsService : null,
    setModulesCollection : function(modulesCollection) {
        this.modulesCollection = modulesCollection;
    },
    setLatestJobrunsService : function(latestJobrunsService) {
        this.latestJobrunsService = latestJobrunsService;
    },
    indexAction : function()
    {
        this.getMvcEvent().setParam('breadcrumbs', [{link : '', label : 'Dashboard'}]);
        var view = $CL.get('Ginger.Dashboard.View.Index.Index');
        var jobrunsView = view.getJobrunsView();

        //load latest jobruns in an async call, and
        this.latestJobrunsService.fetch({
            success : function(data) {
                jobrunsView.updateData({jobruns : data});
            },
            error : function(jqX) {
                $CL.app().alert('Failed to load latest Jobruns.', jqX);
            }
        });

        view.setData({modules : this.modulesCollection.toJSON()});
        return view;
    }
});
