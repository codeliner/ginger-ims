var Db = $CL.namespace('SqlConnect.Model.Db');

$CL.require("Ginger.Application.Service.ModuleElement.ElementInterface");
$CL.require('SqlConnect.View.Targets.Options');
$CL.require('SqlConnect.View.Targets.HelpDialog');


Db.TableTarget = function() {
    this.name = null;
    this.helpView = null;
    this.optionsView = null;
};

Db.TableTarget.prototype = {
    __IMPLEMENTS__ : [Ginger.Application.Service.ModuleElement.ElementInterface],
    setup : function(options) {
        this.name = options.name;
    },
    getOptionsView : function(elementData) {
        if (_.isNull(this.optionsView)) {
            this.optionsView = $CL.makeObj('SqlConnect.View.Targets.Options');
            this.optionsView.setTemplate($CL._template('sqlconnect/targets/options'));
        }

        this.optionsView.setData(elementData);
        return this.optionsView;
    },
    getHelpView : function(elementData) {
        if (_.isNull(this.helpView)) {
            this.helpView = $CL.makeObj('SqlConnect.View.Targets.HelpDialog');
        }

        this.helpView.setTargetName(elementData.name);
        return this.helpView;
    },
    collectOptions : function() {
        var options = {};

        if ($('#js-target-options').find('input[name=sqlconnect-target-empty-table]').is(':checked')) {
            options['emptyTable'] = true;
        }

        return options;
    }
}