var Partial = $CL.namespace('Ginger.Dashboard.View.Index.Partial');

$CL.require('Cl.Backbone.View');

Partial.Modules = function() {};

Partial.Modules = $CL.extendClass(Partial.Modules, Cl.Backbone.View, {
    moduleView : null,
    id : 'js_modules',
    events : {
        'click .connect-module-link' : 'onModuleLinkClick'
    },
    setModuleView : function(moduleView) {
        this.moduleView = moduleView;
    },
    render : function() {
        _.each(this.data.modules, function(moduleData) {
            this.moduleView.setData(moduleData);
            this.moduleView.render();
            this.$el.append(this.moduleView.$el.clone());
        }, this);
    },
    onModuleLinkClick : function (e) {
        e.preventDefault();

        var $a = $CL.jTarget(e.target, 'a');

        $CL.get('application')
        .router
        .forward(
            'application_load_module',
            {moduleName : $a.data('name'), gotoRoute : $a.data('route')}
        );
    }
});