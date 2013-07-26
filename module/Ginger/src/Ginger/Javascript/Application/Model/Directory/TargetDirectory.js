var Directory = $CL.namespace('Ginger.Application.Model.Directory');

$CL.require("Ginger.Application.Service.ModuleElement.ElementInterface");

Directory.TargetDirectory = function() {
    this.optionsView = null;
};

Directory.TargetDirectory.prototype = {
    __IMPLEMENTS__ : [Ginger.Application.Service.ModuleElement.ElementInterface],
    setOptionsView : function(optionsView) {
        this.optionsView = optionsView;
    },
    getOptionsView : function(elementData) {
        this.optionsView.setData(elementData);
        return this.optionsView;
    },
    getHelpView : function() {
        return null;
    },
    collectOptions : function() {
        return this.optionsView.collectOptions();
    }
}