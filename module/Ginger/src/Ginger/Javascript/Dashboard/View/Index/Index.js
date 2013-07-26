var Index = $CL.namespace('Ginger.Dashboard.View.Index');

$CL.require('Cl.Backbone.View');

Index.Index = function() {};

Index.Index = $CL.extendClass(Index.Index, Cl.Backbone.View, {
    modulesView : null,
    jobrunsView : null,
    setModulesView : function(modulesView) {
        this.modulesView = modulesView;
    },
    setJobrunsView : function(jobrunsView) {
        this.jobrunsView = jobrunsView;
    },
    getJobrunsView : function() {
        return this.jobrunsView;
    },
    render : function() {
        this.$el.html(this.tpl());
        this.renderModules();
        this.renderJobrunsView();
    },
    renderModules : function() {
        this.modulesView.setElement(this.$el.find('#js_modules')[0]);
        this.modulesView.setData({modules : this.data.modules});
        this.modulesView.render();
    },
    renderJobrunsView : function() {
        this.jobrunsView.setElement(this.$el.find('#js_latest_jobruns'));
        this.jobrunsView.render();
    }
});