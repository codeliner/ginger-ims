var Helper = $CL.namespace("Ginger.Application.View.Helper");

$CL.require("Cl.Backbone.View");

Helper.Breadcrumbs = function(){};

Helper.Breadcrumbs = $CL.extendClass(Helper.Breadcrumbs, Cl.Backbone.View, {
    initialize : function() {
        this.$el = $('#js_breadcrumbs');
    },
    data : {links : []},
    setData : function(data) {
        if (!$CL.isEmpty(data)) {
            if (data[0].label != "Dashboard") {
                data.unshift({link : helpers.uri('dashboard'), label : 'Ginger'});
            }

            this.data = {links : data};
        }
    }
});