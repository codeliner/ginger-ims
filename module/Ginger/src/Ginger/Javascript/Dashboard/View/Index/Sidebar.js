var Index = $CL.namespace('Ginger.Dashboard.View.Index');

$CL.require('Cl.Backbone.View');

Index.Sidebar = function() {};

Index.Sidebar = $CL.extendClass(Index.Sidebar, Cl.Backbone.View, {
    render : function() {
        this.$el.html('');
    }
});