var Feature = $CL.namespace('Ginger.Application.Model.Feature');

$CL.require("Ginger.Application.Service.ModuleElement.ElementInterface");

Feature.AttributeMapFeature = function() {
    this.optionsView = null;
    this.helpView = null;
};

Feature.AttributeMapFeature.prototype = {
    __IMPLEMENTS__ : [Ginger.Application.Service.ModuleElement.ElementInterface],
    setOptionsView : function(optionsView) {
        this.optionsView = optionsView;
    },
    setHelpView : function(helpView) {
        this.helpView = helpView;
    },
    getOptionsView : function(elementData) {
        this.optionsView.setData($CL.clone(elementData));
        return this.optionsView;
    },
    getHelpView : function() {
        return this.helpView;
    },
    collectOptions : function() {
        var $table = $('#js-attributemap');
        var mapping = {};
        $table.find('.ts-tr').each(function(i, tr) {
            var $tr = $(tr);

            if ($tr.attr('id') != "js-attributemap-column-chooser" && !$tr.children().first().hasClass('ts-th')) {
                mapping[$tr.find('.js-source-td').html()] = $tr.find('.js-target-td').html();
            }
        });
        return {'attribute_map' : mapping};
    }
}