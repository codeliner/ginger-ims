var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require("Cl.Backbone.BlockingView");
$CL.require("Cl.Jquery.Plugin.Ui.Sortable");

Partial.StructureMapperOptions = function() {};

Partial.StructureMapperOptions = $CL.extendClass(Partial.StructureMapperOptions, Cl.Backbone.BlockingView, {
    events : {
        'click .js-mapper-column-ok' : 'onColumnOkClick',
        'click .js-mapper-columns .js-remove' : 'onColumnRemoveClick',
        'click .js-mapper-columns .js-edit' : 'onColumnEditClick'
    },
    setData : function(data) {
        if ($CL.isDefined(data.elementData.options['mapping'])) {
            var definedSourceColumns = [];
            var definedTargetColumns = [];
            $.each(data.elementData.options.mapping, $CL.bind(function(sourceColumn, targetColumn) {
                definedSourceColumns.push(sourceColumn);
                definedTargetColumns.push(targetColumn);
            }, this));

            data.sourceInfo.data_structure = _.filter(data.sourceInfo.data_structure, function(columnDesc) {
                return _.contains(definedSourceColumns, columnDesc.name) != true;
            });

            data.targetInfo.data_structure = _.filter(data.targetInfo.data_structure, function(columnDesc) {
                return _.contains(definedTargetColumns, columnDesc.name) != true;
            });
        }

        this.data = data;
    },
    onColumnOkClick : function(e) {
        e.preventDefault();
        var $tr = $(e.target).parents('.ts-tr'),
        $sourceSelect = $tr.find('.js-source-td').find('select'),
        sourceColumn  = $sourceSelect.val(),
        $targetSelect = $tr.find('.js-target-td').find('select'),
        targetColumn  = $targetSelect.val(),
        $dataTr = $tr.clone();

        $dataTr.removeAttr('id').addClass('js-mapper-columns');

        $dataTr.find('.js-source-td').html(sourceColumn);
        $dataTr.find('.js-target-td').html(targetColumn)
            .next().html($CL._template('application_edit_remove')({id : 0}));

        $tr.before($dataTr);

        $sourceSelect.find('option').each(function(i, option) {
            if ($(option).html() == sourceColumn) {
                $(option).remove();
            }
        });

        if ($sourceSelect.find('option').length == 0) {
            $tr.addClass('hide');
        }

        $targetSelect.find('option').each(function(i, option){
            if ($(option).html() == targetColumn) {
                $(option).remove();
            }
        });

        if ($targetSelect.find('option').length == 0) {
            $tr.addClass('hide');
        }

        if ($tr.parent().find('tr').last()[0] != $tr[0]) {
            $tr.parent().find('tr').last().after($tr);
        }
    },
    onColumnRemoveClick : function(e) {
        e.preventDefault();
        var $tr = $(e.target).parents('.ts-tr'),
        sourceColumn = $tr.find('.js-source-td').html(),
        targetColumn = $tr.find('.js-target-td').html(),
        $sourceSelect = $('#js-mapper-column-chooser').find('.js-source-td').find('select'),
        $targetSelect = $('#js-mapper-column-chooser').find('.js-target-td').find('select');

        $sourceSelect.append($('<option />').html(sourceColumn));
        $targetSelect.append($('<option />').html(targetColumn));
        $tr.remove();
        $('#js-mapper-column-chooser').removeClass('hide');
    },
    onColumnEditClick : function(e) {
        e.preventDefault();
        var $tr = $(e.target).parents('.ts-tr'),
        sourceColumn = $tr.find('.js-source-td').html(),
        targetColumn = $tr.find('.js-target-td').html(),
        $sourceSelect = $('#js-mapper-column-chooser').find('.js-source-td').find('select'),
        $targetSelect = $('#js-mapper-column-chooser').find('.js-target-td').find('select');


        $sourceSelect.append($('<option />').html(sourceColumn).attr('selected', true));
        $targetSelect.append($('<option />').html(targetColumn).attr('selected', true));

        $tr.replaceWith($('#js-mapper-column-chooser').removeClass('hide'));
    }
});