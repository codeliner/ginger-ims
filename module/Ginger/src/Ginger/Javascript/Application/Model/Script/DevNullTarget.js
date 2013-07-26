var Script = $CL.namespace("Ginger.Application.Model.Script");

$CL.require("Ginger.Application.Service.ModuleElement.ElementInterface");

Script.DevNullTarget = function() {
    this.optionsView = null;
};

Script.DevNullTarget.prototype = {
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
            log_file_name : $('input[name=devnull-log-file-name]').val()
        };
    }
}

