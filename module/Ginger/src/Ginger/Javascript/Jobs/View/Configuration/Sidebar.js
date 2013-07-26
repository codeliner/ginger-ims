var Configuration = $CL.namespace('Ginger.Jobs.View.Configuration');

$CL.require('Cl.Backbone.View');
$CL.require('Cl.Jquery.Plugin.ClickableSort');

Configuration.Sidebar = function() {};

Configuration.Sidebar = $CL.extendClass(Configuration.Sidebar, Cl.Backbone.View, {
    featureCollection : null,
    elementLoader : null,
    activatedFeatures : null,
    activeFeature : null,
    activeFeatureData : null,
    activeFeatureView : null,
    activeFeatureHelpView : null,
    isEnabled : false,
    editFeatureId : -1,
    mainEditView : null,
    setElementLoader : function(elementLoader) {
        this.elementLoader = elementLoader;
    },
    setMainEditView : function(editView) {
        this.mainEditView = editView;
    },
    events : {
        'click .js-add-feature' : 'onAddFeatureClick',
        'change select[name=js-feature-filter]' : 'onFeatureFilterChange',
        'change select[name=feature-edit]' : 'onFeatureEditChange',
        'click .feature-tab .js-btn-next' : 'onNextClick',
        'click .feature-tab .js-btn-cancel' : 'onCancelClick',
        'click .feature-tab .js-btn-save' : 'onSaveClick',
        'click .activated-feature .js-edit' : 'onEditFeatureClick',
        'click .activated-feature .js-remove' : 'onRemoveFeatureClick'
    },
    render : function() {
        this.data.isEnabled = this.isEnabled;

        this.parent.prototype.render.apply(this);

        this.activatedFeatures = [];
        this.editFeatureId = -1;

        if (this.data.config && this.data.config.features) {
            _.each(this.data.config.features, function(featureData) {
                this.activatedFeatures.push({
                    elementData : featureData,
                    options : featureData.options
                });
            }, this);

            this._renderActivatedFeatures();
        }
    },
    setElement : function() {
        this.parent.prototype.setElement.apply(this, arguments);
        //reset isEnabled, to default to false
        this.isEnabled = false;
    },
    setFeatureCollection : function(featureCollection) {
        this.featureCollection = featureCollection;
    },
    onConfigSave : function(configData) {
        configData['features'] = [];
        if (this.activatedFeatures && this.activatedFeatures.length > 0) {
            $('#activated-features .ui-sort-item').each($CL.bind(function(i, row) {
                var featureId = $(row).find('.js-edit').data('id');
                $CL.log(featureId);
                var featureData = this._getFeatureData(featureId);

                configData['features'].push({
                    id : featureData.elementData.id,
                    options : featureData.elementData.options
                });
            }, this));
        }
    },
    onEnableFeatures : function() {
        var $area = this.$el.find('#js-feature-area');
        this.isEnabled = true;
        if ($area.hasClass('disabled')) {
            $area.removeClass('disabled');
            $area.find('legend').removeClass('muted');
            $area.find('.js-add-feature').removeClass('disabled');
        }
    },
    onDisableFeatures : function() {
        var $area = this.$el.find('#js-feature-area');
        this.isEnabled = false;
        $area.addClass('disabled').find('legend').addClass('muted');
        $area.find('.js-add-feature').addClass('disabled');
    },
    onNextClick : function() {
        var $sel = this.$el.find('select[name=feature-edit]');
        if ($sel.val() == "none") {
            $sel.parent().addClass('error');
        } else {
            this.$el.find('.feature-tab').addClass('hide').filter('.js-feature-options').removeClass('hide');
        }
    },
    onCancelClick : function() {
        this.editFeatureId = -1;
        this.$el.find('.feature-tab').addClass('hide').filter('.activated-features').removeClass('hide');
    },
    onSaveClick : function() {
        if (this.activeFeature) {
            var options = this.activeFeature.collectOptions(),
            featureId = this.$el.find('select[name=feature-edit]').val();

            if (!options) {
                return;
            }

            var feature = this.featureCollection.findWhere({id : parseInt(featureId)});
            if (this.editFeatureId > -1) {
                this._replaceEditedFeature(feature, options);
            } else {
                this._activateFeature(feature, options);
            }

            this.$el.find('.feature-tab .js-btn-cancel').first().click();
        }
    },
    onEditFeatureClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'a'),
        elementData = this._getFeatureData($a.data('id')).elementData,
        _processFeatureData = $CL.bind(function() {
            this.processFeatureData(elementData);
            this._fillFeatureSelect('all');
            this.$el.find('select[name=feature-edit]').val(elementData.id);
            this.$el.find('.feature-tab .js-btn-next').click();
        }, this);

        this.editFeatureId = $a.data('id');

        if (this.featureCollection.isEmpty()) {
            $CL.app().wait();
            this.featureCollection.fetch({
                success : $CL.bind(function() {
                    $CL.app().stopWait();
                    _processFeatureData();
                }, this),
                error : function(col, jqX) {
                    $CL.app().stopWait().alert("Failed fetching data for features. Server Response: " + jqX.responseText);
                }
            });

            return;
        }

        _processFeatureData();
    },
    onRemoveFeatureClick : function(e) {
        e.preventDefault();

        var $a = $CL.jTarget(e.target, 'a');

        this._deactivateFeature($a.data('id'));
    },
    processFeatureData : function(elementData) {
        this.elementLoader.loadElement(elementData, $CL.bind(function(element){
            if (!_.isNull(this.activeFeature)) {
                if (this.activeFeatureView) {
                    this.activeFeatureView.undelegateEvents();
                }
                this.$el.find('.js-feature-options .js-feature-options-container').html('');

                if (this.activeFeatureHelpView) {
                    this.activeFeatureHelpView.undelegateEvents();
                }

            }
            this.$el.find('#feature-help-con').html('').parent().addClass('hide');

            this.activeFeature = element;
            this.activeFeatureData = elementData;

            this.activeFeatureView = element.getOptionsView(elementData);
            if (this.activeFeatureView) {
                this.activeFeatureView.setElement(this.$el.find('.js-feature-options .js-feature-options-container'));

                if ($CL.has(this.activeFeatureView, 'setMainEditView')) {
                    this.activeFeatureView.setMainEditView(this.mainEditView);
                }

                this.activeFeatureView.render();
            }

            this.activeFeatureHelpView = element.getHelpView(elementData);
            if (this.activeFeatureHelpView) {
                this.activeFeatureHelpView.setElement(this.$el.find('#feature-help-con'));
                this.activeFeatureHelpView.render();
                this.$el.find('#feature-help-con').parent().removeClass('hide');
            }
        }, this));
    },
    onAddFeatureClick : function(e) {
        e.preventDefault();
        var $a = $CL.jTarget(e.target, 'a'),
        _processFeatureDialog = $CL.bind(function() {
            this._showFeatureEdit({feature_type : 'all', feature_data : {}});
        }, this);

        if (!$a.hasClass('disabled')) {
            if (this.featureCollection.isEmpty()) {
                $CL.app().wait();
                this.featureCollection.fetch({
                    success : $CL.bind(function() {
                        $CL.app().stopWait();
                        _processFeatureDialog();
                    }, this),
                    error : function(col, jqX) {
                        $CL.app().stopWait().alert("Failed fetching data for features. Server Response: " + jqX.responseText);
                    }
                });

                return;
            }

            _processFeatureDialog();
        }
    },
    onFeatureFilterChange : function(e) {
        this._fillFeatureSelect($(e.target).val());
    },
    onFeatureEditChange : function(e) {
        var $sel = $(e.target);

        if ($sel.val() != "none") {
            $sel.parent().removeClass('error');
            var feature = this.featureCollection.findWhere({id : parseInt($sel.val())});
            this.processFeatureData(feature.toJSON());
        } else {
            this._removeActiveFeature();
        }
    },
    _removeActiveFeature : function() {
        this.activeFeature = null;
        
        this.activeFeatureData = null;
        
        if (!_.isNull(this.activeFeatureView)) {
            this.activeFeatureView.undelegateEvents();
        }
        
        this.activeFeatureView = null;
        
        if (!_.isNull(this.activeFeatureHelpView)) {
            this.activeFeatureHelpView.undelegateEvents();
        }
        
        this.activeFeatureHelpView = null;
        this.$el.find('.js-feature-options .js-feature-options-container').html('');
    },
    _showFeatureEdit : function(options) {
        this._removeActiveFeature();
        this.$el.find('select[name=js-feature-filter]').val(options.feature_type);
        this._fillFeatureSelect(options.feature_type);

        this.$el.find('.feature-tab').addClass('hide').filter('.add-feature').removeClass('hide');
    },
    _fillFeatureSelect : function(featureType) {
        this.$el.find('select[name=feature-edit]').find(':not(.intro-option)').remove();

        var features = $CL.clone(this.featureCollection.toJSON());

        if (featureType != 'all') {
            features = _.where(features, {type : featureType});
        }

        _.each(features, function(feature) {
            $('select[name=feature-edit]').append($('<option />').attr('value', feature.id).html(feature.module + '::' + feature.name));
        });
    },
    _activateFeature : function(feature, options) {
        if (_.isNull(this.activatedFeatures)) {
            this.activatedFeatures = [];
        }
        var elementData = feature.toJSON();
        elementData.options = $CL.clone(options);
        this.activatedFeatures.push({
            elementData : elementData
        });

        this._renderActivatedFeatures();
    },
    _deactivateFeature : function(featureId) {
        var removedFeature = this._getFeatureData(featureId);
        this.activatedFeatures = _.without(this.activatedFeatures, removedFeature);
        this._renderActivatedFeatures();
    },
    _replaceEditedFeature : function(feature, options) {
        var featureData = this._getFeatureData(feature.get('id'));
        featureData.elementData = feature.toJSON();
        featureData.elementData.options = options;
        this.editFeatureId = -1;
    },
    _renderActivatedFeatures : function() {
        $('#activated-features').find('.ts-tr').filter(':not(.js-default-row)').remove();

        var $copyTr = $('#activated-features').find('.js-copy-row');

        if (this.activatedFeatures.length == 0) {
            $('#activated-features').find('.js-th').addClass('hide');
        } else {
            $('#activated-features').find('.js-th').removeClass('hide');

            $.each(this.activatedFeatures, $CL.bind(function(i, activatedFeatureData) {
                var $newTr = $copyTr.clone();

                $newTr.removeClass('hide js-copy-row js-default-row').addClass('activated-feature')
                .children().first().html(
                    helpers.wrapVisibleSpans(
                        activatedFeatureData.elementData.module + "::"
                        + activatedFeatureData.elementData.name,
                        '::'
                    )
                );

                $newTr.children().last().html($CL._template('application_edit_remove')({id : activatedFeatureData.elementData.id, showSort : true}));
                $('#activated-features .ts-tr').last().after($newTr);
            }, this));

            $('#activated-features .span12').clickableSort({
                cancel : '.js-default-row'
            });
        }
    },
    _getFeatureData : function(featureId) {
        return _.find(this.activatedFeatures, function(feature) {
            return feature.elementData.id == featureId;
        });
    }
});