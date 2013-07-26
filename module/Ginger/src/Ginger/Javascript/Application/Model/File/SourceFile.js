var File = $CL.namespace("Ginger.Application.Model.File");

$CL.require("Ginger.Application.Service.ModuleElement.ElementInterface");

File.SourceFile = function() {
    this.optionsView = null;
};

File.SourceFile.prototype = {
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
            file_pattern : $('input[name=sourcefile-file-pattern]').val()
        };
    }
}

