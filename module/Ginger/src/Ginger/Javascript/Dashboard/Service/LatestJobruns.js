var Service = $CL.namespace('Ginger.Dashboard.Service');

Service.LatestJobruns = function() {};

Service.LatestJobruns.prototype = {
    url : "/rest/latest-jobruns",

    fetch : function(options) {

        var successCb = function() {};
        var errorCb = function() {};

        if (options) {
            if (options.success) {
                successCb = options.success;
            }

            if (options.error) {
                errorCb = options.error;
            }
        }

        $.get(this.url, function(data) {
            successCb(data);
        }).fail(function(jqX) {
            errorCb(jqX);
        });
    }
}