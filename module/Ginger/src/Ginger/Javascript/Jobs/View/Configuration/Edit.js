var Configuration = $CL.namespace('Ginger.Jobs.View.Configuration');

$CL.require('Cl.Backbone.View');
$CL.require('Cl.Bootstrap.Modal');
$CL.require('Cl.Popup.Dialog');

Configuration.Edit = function() {};

Configuration.Edit = $CL.extendClass(Configuration.Edit, Cl.Backbone.View, {
    jobName : null,
    elementLoader : null,
    footer : null,
    sidebar : null,
    activeSource : null,
    activeSourceData : null,
    activeSourceView : null,
    activeSourceHelpView : null,
    activeTarget : null,
    activeTargetData : null,
    activeTargetView : null,
    activeTargetHelpView : null,
    activeMapper : null,
    activeMapperData : null,
    activeMapperView : null,
    activeMapperHelpView : null,
    canRefreshMapper : true,
    sourceModal : null,
    targetModal : null,
    setElementLoader : function(elementLoader) {
        this.elementLoader = elementLoader;
    },
    setJobName : function(jobName) {
        this.jobName = jobName;
    },
    setFooter : function(footer) {
        this.footer = footer;
    },
    setSidebar : function(sidebar) {
        this.sidebar = sidebar;
    },
    events : {
        'change select[name=source]' : 'onSourceChange',
        'click #js-source-question' : 'onSourceQuestionClick',
        'click #js-source-options-btn' : 'onSourceOptionsClick',
        'change select[name=target]' : 'onTargetChange',
        'click #js-target-question' : 'onTargetQuestionClick',
        'click #js-target-options-btn' : 'onTargetOptionsClick'
    },
    render : function() {
        this.parent.prototype.render.apply(this);
        this.reset();

        //if config key is present in data, we are in an edit mode of a previous saved configuration
        //in this case we have to handle saved options
        if ($CL.isDefined(this.data['config'])) {
            this.provideConfigToElements();
        }

        if (!_.isNull(this.activeSourceData)) {
            this.$el.find('select[name=source]').val(this.activeSourceData.id);
            this.$el.find('select[name=target]').val(this.activeTargetData.id);
            this.processSourceData(this.activeSourceData);
        }

        if (_.isNull(this.sourceModal)) {
            this.sourceModal = $CL.makeObj('Cl.Popup.Dialog', {selector : '#sourceModal'});
        }

        this.sourceModal.initPopup();

        if (_.isNull(this.targetModal)) {
            this.targetModal = $CL.makeObj('Cl.Popup.Dialog', {selector : '#targetModal'});
        }

        this.targetModal.initPopup();

        this._enableAreas();
    },
    reset : function() {
        this.activeSource = null;
        this.activeSourceData = null;
        this.activeSourceView = null;
        this.activeSourceHelpView = null;
        this.activeTarget = null;
        this.activeTargetData = null;
        this.activeTargetView = null;
        this.activeTargetHelpView = null;
        this.activeMapper = null;
        this.activeMapperData = null;
        this.activeMapperView = null;
        this.activeMapperHelpView = null;
        this.canRefreshMapper = true;
    },
    resetMapperOptions : function() {
        this.canRefreshMapper = true;
        if ($CL.isDefined(this.data['config']) && $CL.isDefined(this.data.config['mapper'])) {
            this.data.config.mapper.options = {};
            var elementData = _.findWhere(this.data.mappers, {name : this.data.config.mapper.name});
            elementData.options = {};
        }
    },
    refreshMapper : function() {
        if (this.activeSourceData.data_type == 0 && this.activeTargetData.data_type == 0) {
            this._disableMapper();
        } else {
            this.canRefreshMapper = true;
            this._enableMapper();
        }
    },
    provideConfigToElements : function() {
        //inject saved options in the global collections, to hide the edit mode for other methods
        this.activeSourceData = _.findWhere(this.data.sources, {id : parseInt(this.data.config.source.id)});
        this.activeSourceData.options = this.data.config.source.options;

        this.activeTargetData = _.findWhere(this.data.targets, {id : parseInt(this.data.config.target.id)});
        this.activeTargetData.options = this.data.config.target.options;

        if ($CL.isDefined(this.data.config['mapper'])) {
            var elementData = _.findWhere(this.data.mappers, {name : this.data.config.mapper.name});
            elementData.options = this.data.config.mapper.options;
        }
    },
    processSourceData : function(elementData) {
        this.elementLoader.loadElement(elementData, $CL.bind(function(element){
            //hide all source specific elements first
            this._hideSourceViews();

            //if we already have an active view, we have to unbind all events and clear the container
            if (!_.isNull(this.activeSource)) {
                if (this.activeSourceView) {
                    this.activeSourceView.undelegateEvents();
                }
                this.$el.find('#js-source-options').html('');
            }
            this.activeSource = element;
            this.activeSourceData = elementData;

            this.activeSourceView = element.getOptionsView(elementData);
            if (this.activeSourceView) {
                this.activeSourceView.setElement(this.$el.find('#js-source-options'));
                if ($CL.has(this.activeSourceView, "setMainEditView")) {
                    this.activeSourceView.setMainEditView(this);
                }
                this.activeSourceView.render();
                $('#js-source-options-btn').removeClass('disabled');
            }

            this.activeSourceHelpView = element.getHelpView(elementData);

            if (!_.isNull(this.activeSourceHelpView)) {
                $('#js-source-question').removeClass('hide');
            }

            this._enableAreas();
        }, this));
    },
    processTargetData : function(elementData) {
        this.elementLoader.loadElement(elementData, $CL.bind(function(element){
            //hide all target specific elements first
            this._hideTargetViews();

            //if we already have an active view, we have to unbind all events and clear the container
            if (!_.isNull(this.activeTarget)) {
                if (this.activeTargetView) {
                    this.activeTargetView.undelegateEvents();
                }
                this.$el.find('#js-target-options').html('');
            }

            this.activeTarget = element;
            this.activeTargetData = elementData;

            this.activeTargetView = element.getOptionsView(elementData);

            if (this.activeTargetView) {
                this.activeTargetView.setElement(this.$el.find('#js-target-options'));
                if ($CL.has(this.activeTargetView, "setMainEditView")) {
                    this.activeTargetView.setMainEditView(this);
                }
                this.activeTargetView.render();
                $('#js-target-options-btn').removeClass('disabled');
            }

            this.activeTargetHelpView = element.getHelpView(elementData);

            this._enableAreas();
        }, this));
    },
    onConfigSave : function() {
        var sourceId = $('select[name=source]').val(),
        targetId = $('select[name=target]').val(),
        mapperId = this.activeMapper ? this.activeMapperData.id : null,
        sourceOptions = {},
        targetOptions = {},
        mapperOptions = {};

        if (this.activeSource) {
            var result = this.activeSource.collectOptions();
            if (result === false) {
                return;
            }

            sourceOptions = result;
        }

        if (this.activeTarget) {
            var result = this.activeTarget.collectOptions();
            if (result === false) {
                return;
            }

            targetOptions = result;
        }

        if (this.activeMapper) {
            var result = this.activeMapper.collectOptions();

            if (result === false) {
                return;
            }

            mapperOptions = result;
        }

        var configData = {
            source : {
                id : sourceId,
                options : sourceOptions
            },
            target : {
                id : targetId,
                options : targetOptions
            }
        };

        if (!_.isNull(mapperId)) {
            configData['mapper'] = {
                id : mapperId,
                options : mapperOptions
            };
        }

        if ($CL.isDefined(this.data['config'])) {
            configData['id'] = this.data.config.id;
        }

        this.trigger('config-save', configData);

        $CL.app().router.forward(
            'jobs_configuration_save',
            {
                jobname : this.jobName,
                config : configData,
                callback : $CL.bind(function(configModel){
                    this.data.config = configModel.toJSON();
                    this.provideConfigToElements();
                    this.footer.showSavedSuccessful();
                    $CL.app().router.navigate(
                        helpers.uri('jobs_configuration_edit', {jobname : this.jobName, id : this.data.config.id}),
                        {replace : true}
                    );
                    this.trigger('config-save-post', this.data.config);
                }, this)
            }
        );
    },
    onConfigCancel : function() {
        $CL.app().router.callRoute('jobs_job_edit', {name : this.jobName});
    },
    onConfigExport : function() {
        if ($CL.isDefined(this.data['config'])) {
            $CL.setUri('/export/configuration/' + this.jobName + '/' + this.data.config.id);
        } else {
            var savePostListener = $CL.bind(function(config) {
                $CL.setUri('/export/configuration/' + this.jobName + '/' + config.id);
            }, this);

            this.on('config-save-post', function(config) {
                savePostListener(config);
                this.off(savePostListener);
            }, this);

            this.onConfigSave();
        }

    },
    onConfigImport : function(config) {

        if ($CL.isDefined(this.data['config'])) {
            config['id'] = this.data.config.id;
        }

        $CL.app().router.forward(
            'jobs_configuration_save',
            {
                jobname : this.jobName,
                config : config,
                callback : $CL.bind(function(configModel){
                    $CL.app().router.navigate('placebo');
                    $CL.app().router.callRoute(
                        'jobs_configuration_edit',
                        {jobname : this.jobName, id : configModel.get('id')}
                    );
                }, this)
            }
        );
    },
    onSourceQuestionClick : function(e) {
        e.preventDefault();
        this.sourceModal.show();
        this.activeSourceHelpView.setElement($('#sourceModal').find('.modal-body')[0]);
        this.activeSourceHelpView.render();
    },
    onSourceOptionsClick : function(e) {
        var $btn = $CL.jTarget(e.target, 'button');
        if (!$btn.hasClass('disabled')) {
            $btn.toggleClass('active');

            if ($btn.hasClass('active')) {
                $('#js-source-options').position({
                    my : 'left top',
                    at: 'left bottom',
                    of: '#js-source-area'
                });

                $('#js-source-options').width($('#js-source-area').width()).removeClass('hide').slideDown(400);
            } else {
                $('#js-source-options').slideUp(400, function(){
                    $('#js-source-options').addClass('hide');
                });
            }
        }
    },
    onSourceChange : function(e) {
        var $sel = $(e.target);

        var elementData = _.findWhere(this.data.sources, {id : parseInt($sel.val())});

        this.resetMapperOptions();

        if (elementData) {
            this.processSourceData(elementData);
        } else {
            this.activeSourceData = null;
            this.activeSource = null;
            this.activeSourceView = null;
            this.activeSourceHelpView = null;
            this._hideSourceViews();
            this._disableAreas();
        }
    },
    onTargetQuestionClick : function(e) {
        e.preventDefault();
        this.targetModal.show();
        this.activeTargetHelpView.setElement($('#targetModal').find('.modal-body')[0]);
        this.activeTargetHelpView.render();
    },
    onTargetOptionsClick : function(e) {
        var $btn = $CL.jTarget(e.target, 'button');
        if (!$btn.hasClass('disabled')) {
            $btn.toggleClass('active');

            if ($btn.hasClass('active')) {
                $('#js-target-options').position({
                    my : 'left top',
                    at: 'left bottom',
                    of: '#js-source-area'
                });

                $('#js-target-options').width($('#js-target-area').width()).removeClass('hide').slideDown(400);
            } else {
                $('#js-target-options').slideUp(400, function(){
                    $('#js-target-options').addClass('hide');
                });
            }
        }
    },
    onTargetChange : function(e) {
        var $sel = $(e.target);
        var elementData = _.findWhere(this.data.targets, {id : parseInt($sel.val())});

        this.resetMapperOptions();
        if (elementData) {
            this.processTargetData(elementData);
        } else {
            this.activeTargetData = null;
            this.activeTarget = null;
            this.activeTargetView = null;
            this.activeTargetHelpView = null;
            this._hideTargetViews();
            this._disableAreas();
        }
    },
    _enableAreas : function() {
        if (!_.isNull(this.activeSource) && !_.isNull(this.activeTarget)) {
            this._enableSave();
            this._enableMapper();
        }

        if (!_.isNull(this.activeSource)) {
            this._enableTarget();
        }

        if (!_.isNull(this.activeSource) || !_.isNull(this.activeTarget)) {
            this._enableFeatures();
        }
    },
    _disableAreas : function() {
        if (_.isNull(this.activeSource) || _.isNull(this.activeTarget)) {
            this._disableMapper();
            this._disableSave();
        }

        if (_.isNull(this.activeSource)) {
            this._disableTarget();
        }

        if (_.isNull(this.activeSource) && _.isNull(this.activeTarget)) {
            this._disableFeatures();
        }
    },
    _enableTarget : function() {
        var $area = this.$el.find('#js-target-area');

        if ($area.hasClass('disabled')) {
            $area.removeClass('disabled');
            $area.find('legend').removeClass('muted');
            var targetId = $area.find('select').removeAttr('disabled').val();

            var targetData = _.findWhere(this.data.targets, {id : parseInt(targetId)});

            if (targetData) {
                this.processTargetData(targetData);
            }
        }

        if (!_.isNull(this.activeTargetView)) {
            $('#js-target-options-btn').removeClass('disabled');
        }

        if (!_.isNull(this.activeTargetHelpView)) {
            $('#js-target-question').removeClass('hide');
        }
    },
    _disableTarget : function() {
        var $area = this.$el.find('#js-target-area');

        $area.addClass('disabled').find('legend').addClass('muted');
        $area.find('select').attr({disabled : true});

        //attention: options aren't reset, they are just hidden, if target will be enabled, options
        //are displayed again
        this._hideTargetViews();
    },
    _enableFeatures : function() {
        this.trigger('enableFeatures');
    },
    _disableFeatures : function() {
        this.trigger('disableFeatures');
    },
    _enableMapper : function() {
        var mapperName = null;

        if (this.activeSourceData.data_type == 1 && this.activeTargetData.data_type == 1) {
            mapperName = "TableStructureMapper";
        } else if (this.activeSourceData.data_type != 0 && this.activeTargetData.data_type != 0) {
            mapperName = "DocumentStructureMapper";
        }

        var elementData = _.findWhere(this.data.mappers, {name : mapperName});
        if (elementData) {
            this.elementLoader.loadElement(elementData, $CL.bind(function(element){
                //reset previous used mapper
                if (!_.isNull(this.activeMapper) && this.activeMapper != element) {
                    if (this.activeMapperView) {
                        this.activeMapperView.undelegateEvents();
                    }
                    this.$el.find('#js-mapper-options').html('');
                } else {
                    //if lastMapper is same like active mapper and this.canRefreshMapper is false
                    //the function will be aborted, to avoid overhead
                    if (!this.canRefreshMapper) {
                        return;
                    }
                }

                //set new activeMapper
                this.activeMapper = element;
                //lock mapper, to avoid overhead when _enableMapper is called several times
                this.canRefreshMapper = false;

                //check if we should provide detailed informations to mapper
                //mapper can use jobname and configId to fetch source and target infos for
                //the actual configuration, maybe they provide another data_structure, when options
                //are set (f.e. the Ginger.Model.File.SourceFile and the Ginger.Model.Directory.SourceDirectory elements)
                if ($CL.isDefined(this.data['config'])) {
                    if ($CL.has(this.activeMapper, "setJobname")) {
                        this.activeMapper.setJobname(this.data.config.job.name);
                    }

                    if ($CL.has(this.activeMapper, "setConfigurationId")) {
                        this.activeMapper.setConfigurationId(this.data.config.id);
                    }
                } else {
                    this.activeMapper.setJobname(null);
                    this.activeMapper.setConfigurationId(null);
                }

                this.activeMapperData = elementData;
                this.activeMapperView = element.getOptionsView(elementData);
                if (this.activeMapperView) {
                    this.activeMapperView.setElement(this.$el.find('#js-mapper-options'));
                    if ($CL.has(this.activeMapperView, "setMainEditView")) {
                        this.activeMapperView.setMainEditView(this);
                    }
                    this.activeMapperView.render();
                }
            }, this));
        }
    },
    _disableMapper : function() {
        this.activeMapperData = null;
        this.activeMapper = null;
        this.activeMapperView = null;
        $('#js-mapper-options').html('');
    },
    _enableSave : function() {
        this.footer.enableSave();
    },
    _disableSave : function() {
        this.footer.disableSave();
    },
    _hideSourceViews : function() {
        $('#js-source-question').addClass('hide');
        $('#js-source-options').addClass('hide');
        $('#js-source-options-btn').addClass('disabled');
        $('#js-source-options-btn').removeClass('active');
    },
    _hideTargetViews : function() {
        $('#js-target-question').addClass('hide');
        $('#js-target-options').addClass('hide');
        $('#js-target-options-btn').addClass('disabled');
        $('#js-target-options-btn').removeClass('active');
    }
});