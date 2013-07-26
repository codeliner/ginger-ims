var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require("Cl.Backbone.View");

Partial.AbstractFeatureOptions = function() {};

Partial.AbstractFeatureOptions = $CL.extendClass(Partial.AbstractFeatureOptions, Cl.Backbone.View, {
    advancedOptionsView : null,
    siteToAlter : null,
    attributesToAlter : [],
    mainEditView : null,
    id : null,
    events : {
        'click label[for=feature-site-to-alter]' : 'onFeatureSiteToAlterClick',
        'click .js-feature-attribute-ok' : 'onFeatureAttributeOkClick',
        'click .js-attribute-tr .js-remove' : 'onRemoveAttributeClick'
    },
    setMainEditView : function(editView) {
        this.mainEditView = editView;
    },
    setAdvancedOptionsView : function(advancedOptionsView) {
        this.advancedOptionsView = advancedOptionsView;
    },
    render : function() {
        this.parent.prototype.render.apply(this);

        if (!_.isNull(this.advancedOptionsView)) {
            this.advancedOptionsView.setElement(this.$el.find('.js-feature-advanced-options'));
            this.advancedOptionsView.setData(this.data);
            this.advancedOptionsView.render();
        }

        if (this.data.options) {
            if (this.data.options.site_to_alter) {
                this.$el.find('label[for=feature-site-to-alter]')
                .find('input[value='+this.data.options.site_to_alter+']').click();
            }

            if (this.data.options.attributes_to_alter) {
                this.attributesToAlter = this.data.options.attributes_to_alter;

                if ($CL.isEmpty(this.attributesToAlter)) {
                    this.attributesToAlter = [];
                }
                this.renderAttributesOption();
            }
        }
    },
    renderAttributesOption : function() {
        var possibleAttributes = [],
        sourceId = $('select[name=source]').val(),
        targetId = $('select[name=target]').val(),
        $sel = this.$el.find('select[name=feature-attribute-chooser]');

        if (this.siteToAlter == "source") {
            possibleAttributes = this._getAttributesFromDataStructure(
                this.mainEditView.activeMapper.sourceInfoData.data_structure
            );
        } else if (this.siteToAlter == "target") {
            possibleAttributes = this._getAttributesFromDataStructure(
                this.mainEditView.activeMapper.targetInfoData.data_structure
            );
        }

        possibleAttributes = _.difference(possibleAttributes, this.attributesToAlter);

        $sel.find(':not(option[value=all])').remove();

        if (possibleAttributes.length > 0) {
            $sel.parents('.ts-tr').removeClass('hide');
            _.each(possibleAttributes, function(possibleAttribute) {
               $sel.append($('<option />').html(possibleAttribute));
            });
        } else {
            $sel.parents('.ts-tr').addClass('hide');
        }

        this.$el.find('.js-feature-attributes').find('.js-attribute-tr').remove();

        _.each(this.attributesToAlter, $CL.bind(function(attributeToAlter) {
            this.$el.find('.js-feature-attributes').find('.ts-tr').last().after(
                $('<div />').addClass('ts-tr js-attribute-tr').html(
                    $('<div />').addClass('ts-td11').html(attributeToAlter)
                ).append(
                    $('<div />').addClass('ts-td1').html($CL._template('application_edit_remove')({id : attributeToAlter, hideEdit : true}))
                )
            );
        }, this));

        this.$el.find('.js-feature-attributes-option').removeClass('hide');
    },
    setSiteToAlterError : function() {
        var $cg = this.$el.find('input[name=feature-site-to-alter]').parents('.control-group');
        $cg.find('.well').removeClass('well').addClass('alert alert-block alert-error');
        $cg.find('.label').addClass('label-important');
    },
    removeSiteToAlterError : function() {
        var $cg = this.$el.find('input[name=feature-site-to-alter]').parents('.control-group');
        $cg.find('.alert').removeClass('alert alert-block alert-error').addClass('well');
        $cg.find('.label').removeClass('label-important');
    },
    setAttributesToAlterError : function() {
        var $cg = this.$el.find('select[name=feature-attribute-chooser]').parents('.control-group');
        $cg.find('.well').removeClass('well').addClass('alert alert-error');
    },
    removeAttributesToAlterError : function() {
        var $cg = this.$el.find('select[name=feature-attribute-chooser]').parents('.control-group');
        $cg.find('.alert').removeClass('alert alert-error').addClass('well');
    },
    onFeatureSiteToAlterClick : function(e) {
        var $label = $CL.jTarget(e.target, 'label'),
        siteToAlter = $label.find('input').attr('checked', 'checked').val();

        this.removeSiteToAlterError();

        this.siteToAlter = siteToAlter;
        this.attributesToAlter = [];
        this.renderAttributesOption();

    },
    onFeatureAttributeOkClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'a'),
        $sel = this.$el.find('select[name=feature-attribute-chooser]');

        this.removeAttributesToAlterError();

        if ($sel.val() == "all") {
            $sel.find(':not(option[value=all])').each($CL.bind(function(i, opt) {
                this.attributesToAlter.push($(opt).html());
            },this));
        } else {            
            this.attributesToAlter.push($sel.val());
        }
        
        this.renderAttributesOption();
    },
    onRemoveAttributeClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'a'),
        attribute = $a.data('id');

        this.attributesToAlter = _.without(this.attributesToAlter, attribute);

        this.renderAttributesOption();
    },
    _getAttributesFromDataStructure : function(dataStructure) {
        var attributes = [];
        if (dataStructure) {
            _.each(dataStructure, function(columnDesc) {
                attributes.push(columnDesc.name);
            });
        }

        return attributes;
    }
});