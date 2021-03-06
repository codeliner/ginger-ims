var Script = $CL.namespace("Ginger.Application.Model.Script");

$CL.require("Ginger.Application.Service.ModuleElement.ElementInterface");

Script.SourceScript = function() {
    this.optionsView = null;
};

Script.SourceScript.prototype = {
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
        return {
            script_name : $('input[name=sourcescript-script-name]').val()
        };
    }
}

