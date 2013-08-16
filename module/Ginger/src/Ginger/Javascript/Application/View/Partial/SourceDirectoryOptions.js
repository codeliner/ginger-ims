var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require('Cl.Backbone.View');

Partial.SourceDirectoryOptions = function() {};

Partial.SourceDirectoryOptions = $CL.extendClass(Partial.SourceDirectoryOptions, Cl.Backbone.View, {
    mainEditView : null,
    events : {
        'click label[for=sourcedirectory-iterator-mode]' : 'onIteratorModeClick',
        'change select[name=sourcedirectory-dir]' : 'onDirChange'
    },
    render : function() {
        Cl.Backbone.View.prototype.render.apply(this);

        if ($CL.isDefined(this.data.options)) {
            this.$el.find('input[name=sourcedirectory-file-pattern]').val(this.data.options.file_pattern);

            if (this.data.options.source_dir != "inbox" && this.data.options.source_dir != "outbox") {
                this.$el.find('select[name=sourcedirectory-dir]').val('custom');
                this.$el.find('input[name=sourcedirectory-custom-dir]').val(this.data.options.source_dir);
                $('#sourcedirectory-custom-dir-row').removeClass('hide');
            } else {
                this.$el.find('select[name=sourcedirectory-dir]').val(this.data.options.source_dir);
            }

            if (this.data.options.iterator_mode == "data") {
                this.$el.find('input[name=sourcedirectory-iterator-mode]').attr('checked', 'checked');
                this.mainEditView.activeSourceData.data_type = 3;
            }
        }
    },
    setMainEditView : function(editView) {
        if (_.isNull(this.mainEditView) || this.mainEditView != editView) {
            editView.on('task-save-post', this.onPostTaskSave, this);
            this.mainEditView = editView;
        }
    },
    onIteratorModeClick : function(e) {
        if (e.target.nodeName.toLowerCase() != "input") {
            var $label = $CL.jTarget(e.target, 'label');

            if ($label.find('input').is(':checked')) {
                $label.find('input').removeAttr('checked');
            } else {
                $label.find('input').attr('checked', 'checked');
            }
        }

        if (this.$el.find('input[name=sourcedirectory-iterator-mode]').is(':checked')) {
            this.mainEditView.activeSourceData.data_type = 3;
        } else {
            this.mainEditView.activeSourceData.data_type = 0;
        }
    },
    onDirChange : function(e) {
        var dir = $(e.target).val();

        if (dir == "custom") {
            $('#sourcedirectory-custom-dir-row').removeClass('hide');
        } else {
            $('#sourcedirectory-custom-dir-row').addClass('hide');
        }
    },
    /**
     * Listener for the task-save-post event of the main edit view
     *
     * @param {Object} task
     *
     * @return void
     */
    onPostTaskSave : function(task) {
        //maybe source options are changed, so call refreshMapper to inform the main view about a possible change
        this.mainEditView.refreshMapper();
    },
    collectOptions : function() {
        var options = {
            source_dir : this.$el.find('select[name=sourcedirectory-dir]').val(),
            iterator_mode : "file",
            file_pattern : this.$el.find('input[name=sourcedirectory-file-pattern]').val()
        };

        if (options.source_dir == "custom") {
            options.source_dir = this.$el.find('input[name=sourcedirectory-custom-dir]').val();
        }

        if (this.$el.find('input[name=sourcedirectory-iterator-mode]').is(':checked')) {
            options.iterator_mode = "data";
        }

        return options;
    }
});