var Auth = $CL.namespace('Ginger.Users.Service.Auth');

$CL.require("Cl.Crypto.rollups.md5");
$CL.require("Cl.Crypto.rollups.sha1");
$CL.require("Cl.Crypto.rollups.hmac-sha1");
$CL.require("Cl.Jquery.Plugin.Store");

Auth.Adapter = function() {};

Auth.Adapter.prototype = {
    activeApiKey : null,
    activeSecretKey : null,
    checkCredentialsMode : false,
    validCredentials : false,
    getActiveApiKey : function() {
        if (_.isNull(this.activeApiKey)) {
            this.activeApiKey = $.store.get('api_key') || null;
        }
        return this.activeApiKey;
    },
    getActiveSecretKey : function() {
        if (_.isNull(this.activeSecretKey)) {
            this.activeSecretKey = $.store.get('secret_key') || null;
        }
        return this.activeSecretKey;
    },
    setUsername : function(username) {
        this.activeApiKey = this.generateApiKey(username);
        $.store.set('api_key', this.activeApiKey);
    },
    setPassword : function(password) {
        this.activeSecretKey = this.generateSecretKey(password);
        $.store.set('secret_key', this.activeSecretKey);
    },
    generateApiKey : function(username) {
        return CryptoJS.MD5(username).toString();
    },
    generateSecretKey : function(password) {
        return CryptoJS.SHA1(password).toString();
    },
    checkCredentials : function() {
        $CL.app().wait();
        this.validCredentials = false;
        this.checkCredentialsMode = true;
        
        $CL.sjax().get('/rest/users/-1', $CL.bind(function(resp) {
            this.validCredentials = true;
        }, this), "json");
        
        this.checkCredentialsMode = false;
        
        $CL.app().stopWait();
        
        return this;
    },
    isValid : function() {
        return this.validCredentials;
    },
    onBeforeAjaxSend : function(jqXhr, request) {
        if (_.isNull(this.getActiveApiKey()) 
            || _.isNull(this.getActiveSecretKey())) {
                return;
            }
        
        if (!this.checkCredentialsMode && !this.isValid()) {
            $CL.app().router.callRoute('users_auth_login');
            jqXhr.abort();
            $CL.exception(
                'Abort ajax request, cause valid credentials missing', 
                'Ginger.Users.Service.Auth.Adapter'
            );
        }        
         
        var requestHash = CryptoJS.HmacSHA1(
            decodeURI(request.url), 
            this.getActiveSecretKey());

        jqXhr.setRequestHeader('Api-Key', this.getActiveApiKey());
        jqXhr.setRequestHeader('Request-Hash', requestHash);        
    },
    onAppAlert : function(e) {
        if (this.checkCredentialsMode) {
            return;
        }
        
        if (!_.isNull(e.getParam('jqX'))) {
            if (e.getParam('jqX').status === 401) {
                $CL.app().router.callRoute('users_auth_login');                
            }
        }
    },
    clearCredentials : function() {
        $.store.remove('api_key');
        $.store.remove('secret_key');
        this.activeApiKey = null;
        this.activeSecretKey = null;
        this.validCredentials = false;
    }
};