var Ginger = $CL.namespace("Ginger");

$CL.require("Cl.Application.Application");

Ginger.AppInitializer = function(){};

Ginger.AppInitializer.prototype = {
    init : function() {
        $CL.log('loaded modules: ', $APPLICATION_MODULES);
        
        var application = $CL.makeObj("Cl.Application.Application", {
            modules : $APPLICATION_MODULES
        });

        var eventManager = $CL.get("shared_event_manager");
        eventManager.attach("application", "wait", function() {
            if ($("#overlay").length == 0) {
                $("body").append($("<div />").attr("id", "overlay").css({
                    top : "0px",
                    left : "0px",
                    width : "100%",
                    height : "100%",
                    position : "fixed",
                    backgroundColor : "#eee",
                    opacity : "0.4"
                }).html("&nbsp;"));
            }
        });

        eventManager.attach("application", "stopWait", function() {
            $("#overlay").remove();
        });

        eventManager.attach("application", "alert", function(e) {

            alert(e.getParam(
                'msg',
                $CL.translate('ERROR:ERROR') + '\n' +
                $CL.translate("ERROR:SORRY")
                ));
        });

        $CL.register('application', application);

        var hash = window.location.hash.replace('#', '');
        window.location.hash = "";

        application.bootstrap().run();
        
        $CL.log($CL.get('auth_adapter'));
        
        //The flag is set serverside in the CheckActiveUser dispatch listener
        //and indicate that credentials are required
        if ($CL.variable('$LOGIN_REQUIRED', false)) {
            if (!$CL.get('auth_adapter').checkCredentials().isValid()) {
                $CL.app().router.callRoute('users_auth_login');
                return;
            }
        }

        if (hash == "") {
            $CL.get("application").router.callRoute('dashboard');
        } else {

            if ($CL.get("application").router.hasRoute(hash)) {
                window.location.hash = hash;
            } else {
                var moduleData = _.find($CL.variable('connect_modules', []), function(data) {
                    return hash.indexOf(data.module.toLowerCase() + "/") == 0;
                });
                
                if (moduleData) {
                    $CL.get('application').lazyLoadModule(
                        moduleData.module,
                        function() {
                            window.location.hash = hash;
                        }
                    );
                } else {
                    $CL.get("application").alert("Can not dispatch the route: " + hash);
                }
            }
        }
    }
};
