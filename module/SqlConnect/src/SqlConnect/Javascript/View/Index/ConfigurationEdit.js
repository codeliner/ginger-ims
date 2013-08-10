var Index = $CL.namespace('SqlConnect.View.Index');

$CL.require('Cl.Backbone.View');
$CL.require('Cl.Core.String');

Index.ConfigurationEdit = function() {};

Index.ConfigurationEdit = $CL.extendClass(Index.ConfigurationEdit, Cl.Backbone.View, {
    dirverParams : {
        PDOMySql : {
            'dbname' : 'input',
            'charset' : 'input',
            'host' : 'input',
            'port' : 'input',
            'user' : 'input',
            'password' : 'input',
            'driverOptions' : 'textarea'
        }
    },
    events : {
        'click .js-driver-save .js-btn-save' : 'onSaveClick',
        'click .js-driver-save .js-btn-cancel' : 'onCancelClick',
        'change select[name=driverClass]' : 'onDriverClassChange',
        'click input[name=show-password]' : 'onShowPasswordClick',
        'click input[name=source]' : 'onConnectSiteClick',
        'click input[name=target]' : 'onConnectSiteClick',
        'click label[for=source]' : 'onConnectSiteLabelClick',
        'click label[for=target]' : 'onConnectSiteLabelClick'
    },
    render : function() {
        this.parent.prototype.render.apply(this);

        if (!this.data.isNew) {
            this.$el.find('input[name=name]').val(this.data.name);

            if (this.data.isSource) {
                this.$el.find('input[name=source]').attr('checked', 'checked');
            }

            if (this.data.isTarget) {
                this.$el.find('input[name=target]').attr('checked', 'checked');
            }

            this.$el.find('select[name=driverClass]').val(this.data.params.driverClass).change();

            var params = $CL.clone(this.data.params);

            _.each(this.dirverParams[params.driverClass], function(inputType, name) {

                if (inputType == 'textarea') {
                    params[name] = JSON.stringify(params[name]).trim('{').trim('}');
                }

                this.$el.find('.' + params.driverClass).find(inputType + '[name='+name+']')
                .val(params[name]);

            }, this);
        }
    },
    onSaveClick : function(e) {
        e.preventDefault();

        var isSource = this.$el.find('input[name=source]').is(':checked'),
        isTarget = this.$el.find('input[name=target]').is(':checked'),
        driverClass = this.$el.find('select[name=driverClass]').val(),
        connectionName = this.$el.find('input[name=name]').val();

        if (connectionName == "") {
            this._setError($CL.translate('SQLCONNECT::ERROR::CONNECTION_NAME_EMPTY'));
            return;
        }

        if (driverClass == "none") {
            this._setError($CL.translate('SQLCONNECT::ERROR::CHOOSE_DRIVER'));
            return;
        }

        if (!isSource && !isTarget) {
            this._setError($CL.translate('SQLCONNECT::ERROR::CHOOSE_CONNECT_SITE'));
            return;
        }

        var params = {};
        var error = false;
        _.each(this.dirverParams[driverClass], function(inputType, name) {
            params[name] = this.$el.find('.' + driverClass).find(inputType + '[name='+name+']').val();
            if (inputType == 'textarea') {
                try {
                    params[name] = JSON.parse('{' + params[name] + '}');
                } catch(e) {
                    error = true;
                    $CL.log(e);
                    this._setError($CL.translate('ERROR::JSON_PARSE'));
                }
            }
        }, this);

        if (error) {
            return;
        }

        params['driverClass'] = driverClass;

        var config = {
            name : connectionName,
            params : params,
            isSource : isSource,
            isTarget : isTarget,
            isNew : this.data.isNew
        }

        $CL.app().wait();

        $.post('/sqlconnect/rest/connection/test', {connection : JSON.stringify(params)}, $CL.bind(function(response) {
            $CL.app().router.forward('sqlconnect_configuration_save', {connectionConfig : config});
        }, this), 'json').fail($CL.bind(function(jqX) {
            this._setError($CL.translate('SQLCONNECT::ERROR::CONNECTION_FAILED') + '<br />' + jqX.responseText);
        }, this)).always(function() {
            $CL.app().stopWait();
        });
    },
    onCancelClick : function(e) {
        e.preventDefault();
        $CL.app().router.callRoute('sqlconnect_configuration');
    },
    onDriverClassChange : function(e) {
        var $sel = $(e.target);

        this.$el.find('.js-error-box').addClass('hide');

        this.$el.find('.js-driver-config').addClass('hide')
        .filter('.' + $sel.val()).removeClass('hide');
    },
    onConnectSiteClick : function(e) {
        this.$el.find('.js-error-box').addClass('hide');
    },
    onConnectSiteLabelClick : function(e) {
        if (e.target.nodeName.toLowerCase() == 'label') {
            $(e.target).find('input').click();
        }
    },
    onShowPasswordClick : function(e) {
        var $chk = $(e.target);

        var $passInput = $chk.parents('.js-driver-config').find('input[name=password]');

        if ($chk.is(':checked')) {
            var $text = $('<input />').attr({type : 'text', name : 'password'}).val($passInput.val()).addClass($passInput.attr('class'));
            $passInput.replaceWith($text);
        } else {
            var $text = $('<input />').attr({type : 'password', name : 'password'}).val($passInput.val()).addClass($passInput.attr('class'));
            $passInput.replaceWith($text);
        }
    },
    _setError : function(msg) {
        this.$el.find('.js-error-box').removeClass('hide').find('.alert').html(msg);
    }
});