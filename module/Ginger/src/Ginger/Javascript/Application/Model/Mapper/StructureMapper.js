var Mapper = $CL.namespace('Ginger.Application.Model.Mapper');

$CL.require("Ginger.Application.Service.ModuleElement.ElementInterface");
$CL.require("Ginger.Application.View.Partial.StructureMapperOptions");
$CL.require("Cl.Ajax.Queue");


Mapper.StructureMapper = function() {
    this.mapping = {};
    this.disabledKeys = {};
    this.optionsView = null;
    this.lastSourceId = null;
    this.sourceInfoData = null;
    this.lastTargetId = null;
    this.targetInfoData = null;
    this.sourceInfoCollection = null;
    this.targetInfoCollection = null;
};

Mapper.StructureMapper.prototype = {
    __IMPLEMENTS__ : [Ginger.Application.Service.ModuleElement.ElementInterface],
    jobname : null,
    taskId : null,
    setOptionsView : function(optionsView) {
        this.optionsView = optionsView;
    },
    setSourceInfoCollection : function(sourceInfoCollection) {
        this.sourceInfoCollection = sourceInfoCollection;
    },
    setTargetInfoCollection : function(targetInfoCollection) {
        this.targetInfoCollection = targetInfoCollection;
    },
    setJobname : function(jobname) {
        this.jobname = jobname;
    },
    setTaskId : function(taskId) {
        this.taskId = taskId;
    },
    getOptionsView : function(elementData) {
        var sourceId = $('select[name=source]').val();
        var targetId = $('select[name=target]').val();
        var queue = $CL.makeObj('Cl.Ajax.Queue');

        queue.events().attach("start", function(){
            $CL.app().wait();
        }, 0, true);

        //check if we need to fetch source info, this is the case when last source is
        //not the same as active source or when a jobname and a taskId is set
        if (this.lastSourceId != sourceId
            || (!_.isNull(this.jobname) && !_.isNull(this.taskId))) {
            //cache the active sourceId, maybe we don't need to fetch source info again when
            //getOptionsView() is called
            this.lastSourceId = sourceId;

            //If a jobname and a taskId is set, we fetch source info with this params
            //to get data_type and data_structure for the source with task specific options applied to it.
            //The specific informations are not applied to the sourceInfo entity, cause this could have
            //unknown side effects
            if (!_.isNull(this.jobname) && !_.isNull(this.taskId)) {
                queue.addJqXhr(
                    $.get(
                        this.sourceInfoCollection.url
                            + '/' + sourceId
                            + '/' + this.jobname
                            + '/' + this.taskId,
                        $CL.bind(function(response) {
                            this.sourceInfoData = response;
                        }, this),
                        "json"
                    ).fail(function(jqX) {
                        $CL.app().alert('Failed to fetch source info for source: ' + sourceId, jqX);
                    })
                );
            } else {
                //No jobname or taskId provided, so we have to fetch non specific source infos
                var sourceInfo = this.sourceInfoCollection.get(sourceId);

                if (!sourceInfo) {
                    this.sourceInfoCollection.add({id : sourceId});
                    sourceInfo = this.sourceInfoCollection.get(sourceId);
                    queue.addJqXhr(
                        sourceInfo.fetch({
                            success : $CL.bind(function(model) {
                                this.sourceInfoData = model.toJSON();
                            }, this)
                        }).fail(function(jqX) {
                            $CL.app().alert('Failed to fetch source info for source: ' + sourceId, jqX);
                        })
                    );
                } else {
                    this.sourceInfoData = sourceInfo.toJSON();
                }
            }
        }

        //same for target info as for source info
        if (this.lastTargetId != targetId
            || (!_.isNull(this.jobname) && !_.isNull(this.taskId))) {

            this.lastTargetId = targetId;

            if (!_.isNull(this.jobname) && !_.isNull(this.taskId)) {
                queue.addJqXhr(
                    $.get(
                        this.targetInfoCollection.url
                            + '/' + targetId
                            + '/' + this.jobname
                            + '/' + this.taskId,
                        $CL.bind(function(response) {
                            this.targetInfoData = response;
                        }, this),
                        "json"
                    ).fail(function(jqX) {
                        $CL.app().alert('Failed to fetch target info for target: ' + targetId, jqX);
                    })
                );
            } else {
                var targetInfo = this.targetInfoCollection.get(targetId);

                if (!targetInfo) {
                    this.targetInfoCollection.add({id : targetId});

                    targetInfo = this.targetInfoCollection.get(targetId);

                    queue.addJqXhr(
                        targetInfo.fetch({
                            success : $CL.bind(function(model) {
                                this.targetInfoData = model.toJSON();
                            }, this)
                        }).fail(function(jqX) {
                            $CL.app().alert('Failed to fetch target info for target: ' + targetId, jqX);
                        })
                    );
                } else {
                    this.targetInfoData = targetInfo.toJSON();
                }
            }
        }

        queue.setFinishCallback($CL.bind(function() {
            this.optionsView.setData({
                elementData : $CL.clone(elementData),
                sourceInfo : $CL.clone(this.sourceInfoData),
                targetInfo : $CL.clone(this.targetInfoData)
            });

            this.optionsView.stopBlocking();
            $CL.app().stopWait();
        }, this));

        this.optionsView.blockRendering();
        
        queue.close();

        return this.optionsView;
    },
    getHelpView : function() {
        return null;
    },
    collectOptions : function() {
        var $table = $('#js-table-structure-mapping');
        var mapping = {};
        $table.find('.ts-tr').each(function(i, tr) {
            var $tr = $(tr);

            if ($tr.attr('id') != "js-mapper-column-chooser" && !$tr.children().first().hasClass('ts-th')) {
                mapping[$tr.find('.js-source-td').html()] = $tr.find('.js-target-td').html();
            }
        });

        return {mapping : mapping};
    }
}