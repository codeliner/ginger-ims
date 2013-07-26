var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require("Cl.Backbone.View");

Partial.AttributeMapFeatureOptions = function() {};

Partial.AttributeMapFeatureOptions = $CL.extendClass(Partial.AttributeMapFeatureOptions, Cl.Backbone.View, {
    mainEditView : null,
    events : {
        'click .js-attributemap-column-ok' : 'onColumnOkClick',
        'click .js-attributemap-column .js-remove' : 'onColumnRemoveClick',
        'click .js-attributemap-column .js-edit' : 'onColumnEditClick'
    },
    setMainEditView : function(editView) {
        this.mainEditView = editView;
    },
    render : function() {
        
        this.data.sourceInfo = $CL.clone(this.mainEditView.activeMapper.sourceInfoData);
        this.data.targetInfo = $CL.clone(this.mainEditView.activeMapper.targetInfoData);
        
        if ($CL.isDefined(this.data.options['attribute_map'])) {
            var definedSourceColumns = [];
            var definedTargetColumns = [];
            $.each(this.data.options.attribute_map, $CL.bind(function(sourceColumn, targetColumn) {
                definedSourceColumns.push(sourceColumn);
                definedTargetColumns.push(targetColumn);
            }, this));

            this.data.sourceInfo.data_structure = _.filter(this.mainEditView.activeMapper.sourceInfoData.data_structure, function(columnDesc) {
                return _.contains(definedSourceColumns, columnDesc.name) != true;
            });

            this.data.targetInfo.data_structure = _.filter(this.mainEditView.activeMapper.targetInfoData.data_structure, function(columnDesc) {
                return _.contains(definedTargetColumns, columnDesc.name) != true;
            });
        } 
        
        Cl.Backbone.View.prototype.render.apply(this);
    },
    onColumnOkClick : function(e) {
        e.preventDefault();
        var $tr = $(e.target).parents('.ts-tr'),
        $sourceSelect = $tr.find('.js-source-td').find('select'),
        sourceColumn  = $sourceSelect.val(),
        $targetSelect = $tr.find('.js-target-td').find('select'),
        targetColumn  = $targetSelect.val(),
        $dataTr = $tr.clone();

        $dataTr.removeAttr('id').addClass('js-attributemap-column');

        $dataTr.find('.js-source-td').html(sourceColumn);
        $dataTr.find('.js-target-td').html(targetColumn)
            .next().html($CL._template('application_edit_remove')({id : 0}));

        $tr.before($dataTr);

        if ($tr.parent().find('.ts-tr').last()[0] != $tr[0]) {
            $tr.parent().find('.ts-tr').last().after($tr);
        }
    },
    onColumnRemoveClick : function(e) {
        e.preventDefault();
        $(e.target).parents('.ts-tr').remove();
    },
    onColumnEditClick : function(e) {
        e.preventDefault();
        var $tr = $(e.target).parents('.ts-tr'),
        sourceColumn = $tr.find('.js-source-td').html(),
        targetColumn = $tr.find('.js-target-td').html(),
        $sourceSelect = $('#js-attributemap-column-chooser').find('.js-source-td').find('select'),
        $targetSelect = $('#js-attributemap-column-chooser').find('.js-target-td').find('select');

        $sourceSelect.val(sourceColumn);
        $targetSelect.val(targetColumn);

        $tr.replaceWith($('#js-attributemap-column-chooser').removeClass('hide'));
    }
});


