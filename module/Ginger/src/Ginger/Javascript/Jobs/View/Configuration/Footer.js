var Configuration = $CL.namespace('Ginger.Jobs.View.Configuration');

$CL.require('Cl.Backbone.View');
$CL.require('Cl.Bootstrap.Modal');
$CL.require('Cl.Popup.Dialog');

Configuration.Footer = function() {};

Configuration.Footer = $CL.extendClass(Configuration.Footer, Cl.Backbone.View, {
    saveEnabled : false,
    importModal : null,
    events : {
        'click .js-btn-save' : 'onSaveClick',
        'click .js-btn-cancel' : 'onCancelClick',
        'click .js-btn-export' : 'onExportClick',
        'click .js-btn-import' : 'onImportClick',
        'click .js-perform-import' : 'onPerformImportClick',
        'click .js-cancel-import' : 'onCancelImportClick',
        'click #import-search-btn' : 'onImportSearchClick',
        'change input[name=import-file]' : 'onImportFileChange'
    },
    render : function() {
        this.parent.prototype.render.apply(this);
        if (!this.saveEnabled) {
            this.disableSave();
        }

        this.importModal = $CL.makeObj('Cl.Popup.Dialog', {selector : '#configurationImportModal'});

        this.importModal.initPopup();
    },
    onSaveClick : function() {
        if (!this.$el.find('.js-btn-save').hasClass('disabled')) {
            this.trigger('save');
        }
    },
    onCancelClick : function() {
        this.trigger('cancel');
    },
    onImportSearchClick : function() {
        this.$el.find('input[name=import-file]').trigger('click');
    },
    onImportFileChange : function(e) {
        var file = $(e.target).val().split('\\').pop();
        this.$el.find('#import-file-name-label').html(file);
    },
    onExportClick : function() {
        this.trigger('export');
    },
    onImportClick : function() {
        this.importModal.show();
    },
    onCancelImportClick : function(e) {
        e.preventDefault();
        this.importModal.close();
    },
    onPerformImportClick : function(e) {
        e.preventDefault();
        this.$el.find('form[name=js-import]').submit();

        $('#import-response').load($CL.bind(function() {
            this.importModal.close();
            var config = $.parseJSON($('#import-response').contents().find('body textarea').html());
            this.trigger('import', config);
        }, this));
    },
    enableSave : function() {
        this.saveEnabled = true;
        this.$el.find('.js-btn-save').removeClass('disabled');
    },
    disableSave : function() {
        this.saveEnabled = false;
        this.$el.find('.js-btn-save').addClass('disabled');
    },
    showSavedSuccessful : function() {
        this.$el.find('.alert-success').removeClass('hide');
        window.setTimeout($CL.bind(function() {
            this.$el.find('.alert-success').addClass('hide');
        }, this), 3000);
    }
});