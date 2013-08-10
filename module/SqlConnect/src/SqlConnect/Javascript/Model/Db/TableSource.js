var Db = $CL.namespace('SqlConnect.Model.Db');

$CL.require("Ginger.Application.Service.ModuleElement.ElementInterface");
$CL.require("SqlConnect.View.Sources.HelpDialog");
$CL.require("SqlConnect.View.Sources.Options");

Db.TableSource = function() {
    this.name = null;
    this.helpDialog = null;
    this.optionsView = null;
    this.sourceInfoCollection = null;
};

Db.TableSource.prototype = {
    __IMPLEMENTS__ : [Ginger.Application.Service.ModuleElement.ElementInterface],
    setup : function(options) {
        this.name = options.name;
    },
    setSourceInfoCollection : function(sourceInfoCollection) {
        this.sourceInfoCollection = sourceInfoCollection;
    },
    getOptionsView : function(elementData) {
        if (_.isNull(this.optionsView)) {
            this.optionsView = $CL.makeObj('SqlConnect.View.Sources.Options');
            this.optionsView.setTemplate($CL._template('sqlconnect/sources/options'));
        }

        var sourceInfo = this.sourceInfoCollection.get(elementData.id),
        sourceInfoData = {};


        if (!sourceInfo) {
            this.optionsView.blockRendering();
            this.sourceInfoCollection.add({id : elementData.id});
            sourceInfo = this.sourceInfoCollection.get(elementData.id);

            sourceInfo.fetch({
                success : $CL.bind(function(model) {
                    this.optionsView.setData(_.extend({sourceInfo: model.toJSON()}, elementData));
                    this.optionsView.stopBlocking();
                }, this),
                error : function(model, jqX) {
                    $CL.app().alert('Failed to fetch sourceInfo.', jqX);
                }
            });
        } else {
            sourceInfoData = sourceInfo.toJSON();
        }

        this.optionsView.setData(_.extend({sourceInfo : sourceInfoData}, elementData));

        return this.optionsView;
    },
    getHelpView : function(elementData) {
        if (_.isNull(this.helpDialog)) {
            this.helpDialog = $CL.makeObj('SqlConnect.View.Sources.HelpDialog');
        }

        this.helpDialog.setSourceName(elementData.name);

        return this.helpDialog;
    },
    collectOptions : function() {
        var options = {},
        countColumn = $('#js-source-options').find('select[name=sqlconnect-source-count-column]').val(),
        customSql = $('#js-source-options').find('textarea[name=sqlconnect-source-custom-sql]').val();

        if (!$CL.isEmpty(countColumn)) {
            options['countColumn'] = countColumn;
        } else {
            options['countColumn'] = 'id';
        }

        if (!$CL.isEmpty(customSql)) {
            options['customSql'] = customSql;
        }

        return options;
    }
}