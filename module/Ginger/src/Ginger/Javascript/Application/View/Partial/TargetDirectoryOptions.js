var Partial = $CL.namespace('Ginger.Application.View.Partial');

$CL.require('Cl.Backbone.View');

Partial.TargetDirectoryOptions = function() {};

Partial.TargetDirectoryOptions = $CL.extendClass(Partial.TargetDirectoryOptions, Cl.Backbone.View, {
    events : {
        'change select[name=targetdirectory-dir]' : 'onDirChange'
    },
    render : function() {
        Cl.Backbone.View.prototype.render.apply(this);

        if ($CL.isDefined(this.data.options)) {
            this.$el.find('input[name=targetdirectory-filename-pattern]').val(this.data.options.filename_pattern);

            if (this.data.options.target_dir != "inbox" && this.data.options.target_dir != "outbox") {
                this.$el.find('select[name=targetdirectory-dir]').val('custom');
                this.$el.find('input[name=targetdirectory-custom-dir]').val(this.data.options.target_dir);
                $('#targetdirectory-custom-dir-row').removeClass('hide');
            } else {
                this.$el.find('select[name=targetdirectory-dir]').val(this.data.options.target_dir);
            }
        }
    },
    onDirChange : function(e) {
        var dir = $(e.target).val();

        if (dir == "custom") {
            $('#targetdirectory-custom-dir-row').removeClass('hide');
        } else {
            $('#targetdirectory-custom-dir-row').addClass('hide');
        }
    },
    collectOptions : function() {
        var options = {
            target_dir : this.$el.find('select[name=targetdirectory-dir]').val(),
            filename_pattern : this.$el.find('input[name=targetdirectory-filename-pattern]').val()
        };

        if (options.target_dir == "custom") {
            options.target_dir = this.$el.find('input[name=targetdirectory-custom-dir]').val();
        }

        return options;
    }
});