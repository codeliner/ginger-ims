var Ginger = $CL.namespace("Ginger");

$CL.require("Cl.Core.String");
$CL.require("Cl.Core.Date");
$CL.require("Cl.Application.Application");

Ginger.AppInitializer = function(){};

Ginger.AppInitializer.prototype = {
    init : function() {
        //set some global stuff for templates
        window.helpers = {
            uri : function(route, params) {
                return $CL.get("application").router.getUri(route, params);
            },
            datetime : function(dbDateStr) {
                if (!dbDateStr) {
                    return "-";
                }
                var dbDate = Date.parseDate(dbDateStr, Date.getDbStrFmt(true)),
                dbStr = dbDate.toString(true);

                if (dbDate.getHours() > 11) {
                    dbStr += " " + $CL.translate('GENERAL::TIME::AM');
                } else {
                    dbStr += " " + $CL.translate('GENERAL::TIME::PM');
                }

                return dbStr;
            },
            time : function(dbDateStr) {
                if (!dbDateStr) {
                    return "-";
                }
                var dbDate = Date.parseDate(dbDateStr, Date.getDbStrFmt(true)),
                dbStr = dbDate.toString(true, true);

                if (dbDate.getHours() > 11) {
                    dbStr += " " + $CL.translate('GENERAL::TIME::AM');
                } else {
                    dbStr += " " + $CL.translate('GENERAL::TIME::PM');
                }

                return dbStr;
            },
            duration : function(start, end) {
                if (!start || !end) {
                    return "-";
                }
                var startDate = Date.parseDate(start, Date.getDbStrFmt(true)),
                endDate = Date.parseDate(end, Date.getDbStrFmt(true)),
                duration = endDate.toTimestamp() - startDate.toTimestamp(),
                durationStr = "",
                h = 0, min = 0, sec = 0;

                if (duration > 0) {
                    if (duration < 60) {
                        sec = duration;
                    } else {
                        min = Math.floor(duration / 60);
                        sec = duration % 60;

                        if (min > 59) {
                            h = Math.floor(min / 60);
                            min = min % 60;
                        }
                    }
                }

                if (h > 0) {
                    durationStr += h + " h ";
                }

                if (min > 0) {
                    durationStr += min + " min ";
                }

                durationStr += sec + " s";

                return durationStr;
            },
            wrapVisibleSpans : function(text, separator) {
                var textParts = text.split(separator);

                var newText = "";

                _.each(textParts, function(part, i) {
                   newText += '<span '
                       + ((i+1 < textParts.length)? 'class="visible-large-desktop"' : '')
                       + '>'
                       + part
                       + ((i+1 < textParts.length)? separator : '')
                       + '</span>';
                });

                return newText;
            }
        };

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

        if (hash == "") {
            $CL.get("application").router.callRoute('dashboard');
        } else {

            if ($CL.get("application").router.hasRoute(hash)) {
                window.location.hash = hash;
            } else {
                var moduleData = _.find($CL.variable('connect_modules', []), function(data) {
                    return hash.indexOf(data.module.toLowerCase() + "/") == 0;
                });

                $CL.log(moduleData);

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
