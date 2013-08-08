var Auth = $CL.namespace('Ginger.Application.Service.Auth');

$CL.require("Cl.Crypto.rollups.md5");
$CL.require("Cl.Crypto.rollups.sha1");
$CL.require("Cl.Crypto.rollups.hmac-sha1");
$CL.require("Cl.Jquery.Plugin.Store");

Auth.Adapter = function() {};

Auth.Adapter.prototype = {
    activeApiKey : null,
    activeSecretKey : null,
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
        this.activeApiKey = CryptoJS.MD5(username).toString();
        $.store.set('api_key', this.activeApiKey);
    },
    setPassword : function(password) {
        this.activeSecretKey = CryptoJS.SHA1(password).toString();
        $.store.set('secret_key', this.activeSecretKey);
    },
    onBeforeAjaxSend : function(jqXhr, request) {
        if (!_.isNull(this.getActiveApiKey()) 
            && !_.isNull(this.getActiveSecretKey())) {
            var requestHash = CryptoJS.HmacSHA1(
                decodeURI(request.url), 
                this.getActiveSecretKey());
                
            jqXhr.setRequestHeader('Api-Key', this.getActiveApiKey());
            jqXhr.setRequestHeader('Request-Hash', requestHash);
        }
    },
    clearCredentials : function() {
        $.store.remove('api_key');
        $.store.remove('secret_key');
    }
};