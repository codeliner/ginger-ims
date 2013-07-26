var Feature = $CL.namespace('Ginger.Application.Model.Feature');

$CL.require("Ginger.Application.Service.ModuleElement.ElementInterface");

Feature.AbstractFeature = function() {
    this.optionsView = null;
    this.helpView = null;
};

Feature.AbstractFeature.prototype = {
    __IMPLEMENTS__ : [Ginger.Application.Service.ModuleElement.ElementInterface],
    setOptionsView : function(optionsView) {
        this.optionsView = optionsView;
        this.optionsView.setModel(this);
    },
    getOptionsView : function(elementData) {
        this.optionsView.setData($CL.clone(elementData));

        return this.optionsView;
    },
    setHelpView : function(helpView) {
        this.helpView = helpView;
    },
    getHelpView : function() {
        return this.helpView;
    },
    collectOptions : function() {
        var advancedOptions = this.collectAdvancedOptions();
        if (!advancedOptions) {
            return null;
        }

        var siteToAlter = this.optionsView.$el.find('input[name=feature-site-to-alter]').filter('input[checked=checked]').val();

        if (!siteToAlter) {
            this.optionsView.setSiteToAlterError();
            return null;
        }

        var attributesToAlter = [];

        this.optionsView.$el.find('.js-feature-attributes .js-attribute-tr').each(function(i, tr) {
            attributesToAlter.push($(tr).children().first().html());
        });

        if (attributesToAlter.length == 0) {
            this.optionsView.setAttributesToAlterError();
            return null;
        }

        return _.extend({
            site_to_alter : siteToAlter,
            attributes_to_alter : attributesToAlter
        }, advancedOptions);
    },
    collectAdvancedOptions : function() {
        return {};
    }
};

