var Application = $CL.namespace("Ginger.Application");

$CL.require("Cl.Core.String");
$CL.require("Cl.Core.Date");
$CL.require("Cl.Application.Module.ModuleInterface");
$CL.require("Cl.Backbone.ViewRenderingStrategy");
$CL.require("Cl.Backbone.Layout");
$CL.require("Cl.Jquery.Plugin.Scroll.To");
$CL.require("Cl.Jquery.Plugin.Effect.Core");
//controllers
$CL.require("Ginger.Application.Controller.ModuleLoader");
$CL.require("Ginger.Application.Controller.Auth");
//services
$CL.require("Ginger.Application.Service.ModuleElement.ElementLoader");
$CL.require("Ginger.Application.Service.Auth.Adapter");
//collections
$CL.require('Ginger.Application.Collection.Modules');
$CL.require('Ginger.Application.Collection.Users');
//models
$CL.require("Ginger.Application.Model.User.UserManager");
$CL.require("Ginger.Application.Model.Mapper.StructureMapper");
$CL.require("Ginger.Application.Model.File.SourceFile");
$CL.require("Ginger.Application.Model.Directory.SourceDirectory");
$CL.require("Ginger.Application.Model.Directory.TargetDirectory");
$CL.require("Ginger.Application.Model.Script.SourceScript");
$CL.require("Ginger.Application.Model.Script.DevNullTarget");
$CL.require("Ginger.Application.Model.Feature.AbstractFeature");
$CL.require("Ginger.Application.Model.Feature.ValidatorFeature");
$CL.require("Ginger.Application.Model.Feature.FilterFeature");
$CL.require("Ginger.Application.Model.Feature.AttributeMapFeature");
$CL.require("Ginger.Application.Model.Feature.StaticValueFeature");
//forms
$CL.require("Ginger.Application.Form.Login");
//Views
$CL.require("Ginger.Application.View.Auth.Login");
$CL.require("Ginger.Application.View.Partial.ActiveUser");
$CL.require("Ginger.Application.View.Helper.Breadcrumbs");
$CL.require("Ginger.Application.View.Partial.StructureMapperOptions");
$CL.require("Ginger.Application.View.Partial.SourcefileOptions");
$CL.require("Ginger.Application.View.Partial.SourceDirectoryOptions");
$CL.require("Ginger.Application.View.Partial.TargetDirectoryOptions");
$CL.require("Ginger.Application.View.Partial.SourceScriptOptions");
$CL.require("Ginger.Application.View.Partial.DevNullOptions");
$CL.require("Ginger.Application.View.Partial.AbstractFeatureOptions");
$CL.require("Ginger.Application.View.Partial.ValidatorFeatureOptions");
$CL.require("Ginger.Application.View.Partial.AttributeMapFeatureOptions");
$CL.require("Ginger.Application.View.Partial.AttributeMapFeatureHelp");
$CL.require("Ginger.Application.View.Partial.StaticValueFeatureOptions");
$CL.require("Ginger.Application.View.Partial.StaticValueFeatureHelp");
$CL.require("Ginger.Application.View.Partial.Footer");


Application.Module = function() {
    this.__IMPLEMENTS__ = [Cl.Application.Module.ModuleInterface];
};

Application.Module.prototype = {
    getConfig : function() {
        return {
            router : {
                routes : {
                    'application_load_module' : {
                        route : 'application/load-module/:moduleName/:gotoRoute',
                        callback : function(moduleName, gotoRoute) {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Application.Module",
                                    controller : "moduleLoader",
                                    action : "loadModule",
                                    params : {
                                        moduleName : moduleName,
                                        gotoRoute : gotoRoute
                                    }
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route
                            .replace(':moduleName', routeParams.moduleName)
                            .replace(':gotoRoute', routeParams.gotoRoute);
                        }
                    },
                    'application_auth_login' : {
                        route : 'application/auth/login',
                        callback : function() {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Application.Module",
                                    controller : "auth",
                                    action : "login",
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route;
                        }
                    },
                    'application_auth_logout' : {
                        route : 'application/auth/logout',
                        callback : function() {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Application.Module",
                                    controller : "auth",
                                    action : "logout",
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route;
                        }
                    }
                },
                history : {
                    silent : false,
                    pushState: false,
                    hashChange: true
                }
            },
            view : {
                strategies : [
                    {
                        key : "Cl.Backbone.ViewRenderingStrategy",
                        priority : -90
                    }
                ]
            },
            service_manager : {
                factories : {
                    //controllers
                    'Ginger.Application.Controller.Auth' : function(sl) {
                        var c = $CL.makeObj('Ginger.Application.Controller.Auth');
                        c.setAuthAdapter(sl.get('auth_adapter'));
                        c.setUserManager(sl.get('user_manager'));
                        return c;
                    },                    
                    //models
                    //be aware of the missing Application namespace in mapper alias. It's important,
                    //cause otherwise autoloading of mapper in configuration edit doesn't work.
                    'Ginger.Model.Mapper.TableStructureMapper' : function(sl) {
                        //use object creation of serviceManager, so mapper instance will be cached
                        return sl.get('Ginger.Application.Model.Mapper.StructureMapper');
                    },
                    'Ginger.Model.Mapper.DocumentStructureMapper' : function(sl) {
                        //use object creation of serviceManager, so mapper instance will be cached
                        return sl.get('Ginger.Application.Model.Mapper.StructureMapper');
                    },
                    'Ginger.Model.File.SourceFile' : function(sl) {
                        //use object creation of serviceManager, so mapper instance will be cached
                        return sl.get('Ginger.Application.Model.File.SourceFile');
                    },
                    'Ginger.Model.Directory.SourceDirectory' : function(sl) {
                        return sl.get('Ginger.Application.Model.Directory.SourceDirectory');
                    },
                    'Ginger.Model.Directory.TargetDirectory' : function(sl) {
                        return sl.get('Ginger.Application.Model.Directory.TargetDirectory');
                    },
                    'Ginger.Model.Script.SourceScript' : function(sl) {
                        return sl.get('Ginger.Application.Model.Script.SourceScript');
                    },
                    'Ginger.Model.Script.DevNullTarget' : function(sl) {
                        return sl.get('Ginger.Application.Model.Script.DevNullTarget');
                    },
                    'Ginger.Model.Feature.ValidatorFeature' : function(sl) {
                        return sl.get('Ginger.Application.Model.Feature.ValidatorFeature');
                    },
                    'Ginger.Model.Feature.FilterFeature' : function(sl) {
                        return sl.get('Ginger.Application.Model.Feature.FilterFeature');
                    },
                    'Ginger.Model.Feature.AttributeMapFeature' : function(sl) {
                        return sl.get('Ginger.Application.Model.Feature.AttributeMapFeature');
                    },
                    'Ginger.Model.Feature.StaticValueFeature' : function(sl) {
                        return sl.get('Ginger.Application.Model.Feature.StaticValueFeature');
                    },
                    'Ginger.Application.Model.Mapper.StructureMapper' : function(sl) {
                        var m = $CL.makeObj('Ginger.Application.Model.Mapper.StructureMapper');
                        m.setOptionsView(sl.get('Ginger.Application.View.Partial.StructureMapperOptions'));
                        m.setSourceInfoCollection(sl.get('Ginger.Jobs.Collection.SourceInfos'));
                        m.setTargetInfoCollection(sl.get('Ginger.Jobs.Collection.TargetInfos'));
                        return m;
                    },
                    'Ginger.Application.Model.File.SourceFile' : function(sl) {
                        var m = $CL.makeObj('Ginger.Application.Model.File.SourceFile');
                        m.setOptionsView(sl.get('Ginger.Application.View.Partial.SourcefileOptions'));
                        return m;
                    },
                    'Ginger.Application.Model.Directory.SourceDirectory' : function(sl) {
                        var m = $CL.makeObj('Ginger.Application.Model.Directory.SourceDirectory');
                        m.setOptionsView(sl.get('Ginger.Application.View.Partial.SourceDirectoryOptions'));
                        return m;
                    },
                    'Ginger.Application.Model.Directory.TargetDirectory' : function(sl) {
                        var m = $CL.makeObj('Ginger.Application.Model.Directory.TargetDirectory');
                        m.setOptionsView(sl.get('Ginger.Application.View.Partial.TargetDirectoryOptions'));
                        return m;
                    },
                    'Ginger.Application.Model.Script.SourceScript' : function(sl) {
                        var m = $CL.makeObj('Ginger.Application.Model.Script.SourceScript');
                        m.setOptionsView(sl.get('Ginger.Application.View.Partial.SourceScriptOptions'));
                        return m;
                    },
                    'Ginger.Application.Model.Script.DevNullTarget' : function(sl) {
                        var m = $CL.makeObj('Ginger.Application.Model.Script.DevNullTarget');
                        m.setOptionsView(sl.get('Ginger.Application.View.Partial.DevNullOptions'));
                        return m;
                    },
                    'Ginger.Application.Model.Feature.ValidatorFeature' : function(sl) {
                        var m = $CL.makeObj('Ginger.Application.Model.Feature.ValidatorFeature');
                        var v = sl.get('Ginger.Application.View.Partial.AbstractFeatureOptions');
                        v.id = "ValidatorFEature";
                        v.setAdvancedOptionsView(sl.get('Ginger.Application.View.Partial.ValidatorFeatureOptions'));
                        m.setOptionsView(v);
                        return m;
                    },
                    'Ginger.Application.Model.Feature.FilterFeature' : function(sl) {
                        var m = $CL.makeObj('Ginger.Application.Model.Feature.FilterFeature');
                        var v = sl.get('Ginger.Application.View.Partial.AbstractFeatureOptions');
                        v.id = "FilterFeature";
                        m.setOptionsView(v);
                        return m;
                    },
                    'Ginger.Application.Model.Feature.AttributeMapFeature' : function(sl) {
                        var m = $CL.makeObj('Ginger.Application.Model.Feature.AttributeMapFeature');
                        var v = sl.get('Ginger.Application.View.Partial.AttributeMapFeatureOptions');
                        m.setOptionsView(v);
                        m.setHelpView(sl.get('Ginger.Application.View.Partial.AttributeMapFeatureHelp'));
                        return m;
                    },
                    'Ginger.Application.Model.Feature.StaticValueFeature' : function(sl) {
                        var m = $CL.makeObj('Ginger.Application.Model.Feature.StaticValueFeature');
                        var v = sl.get('Ginger.Application.View.Partial.AbstractFeatureOptions');
                        v.id = "StaticValueFeature";
                        v.setAdvancedOptionsView(sl.get('Ginger.Application.View.Partial.StaticValueFeatureOptions'));
                        m.setOptionsView(v);
                        m.setHelpView(sl.get('Ginger.Application.View.Partial.StaticValueFeatureHelp'));
                        return m;
                    },
                    'user_manager' : function(sl) {
                        var um = $CL.makeObj('Ginger.Application.Model.User.UserManager');
                        
                        um.setAuthAdapter(sl.get('auth_adapter'));
                        um.setUsersCollection(sl.get('Ginger.Application.Collection.Users'));
                        
                        return um;
                    },
                    //Collections
                    'Ginger.Application.Collection.Modules' : function(sl) {
                        var c = $CL.makeObj('Ginger.Application.Collection.Modules');
                        c.reset($CL.variable('connect_modules', {}));
                        return c;
                    },
                    //services
                    'module_element_loader' : function(sl) {
                        return $CL.makeObj('Ginger.Application.Service.ModuleElement.ElementLoader');
                    },
                    'auth_adapter' : function(sl) {
                        return $CL.makeObj("Ginger.Application.Service.Auth.Adapter");
                    },
                    //views
                    "Ginger.Application.View.Auth.Login" : function(sl) {
                        var v = $CL.makeObj('Ginger.Application.View.Auth.Login');
                        v.setForm(sl.get('Ginger.Application.Form.Login'));
                        v.setTemplate($CL._template('application_auth_login'));
                        return v;
                    },
                    "Ginger.Application.View.Partial.ActiveUser" : function(sl) {
                        var v = $CL.makeObj("Ginger.Application.View.Partial.ActiveUser");
                        v.setTemplate($CL._template('application_nav_active_user'));
                        return v;
                    },
                    "Ginger.Application.View.Helper.Breadcrumbs" : function(sl) {
                        var v = $CL.makeObj("Ginger.Application.View.Helper.Breadcrumbs");
                        v.setTemplate($CL._template('application_breadcrumbs'));
                        return v;
                    },
                    "Ginger.Application.View.Partial.StructureMapperOptions" : function(sl) {
                        var v = $CL.makeObj('Ginger.Application.View.Partial.StructureMapperOptions');
                        v.setTemplate($CL._template('application_structure_mapper_options'));
                        return v;
                    },
                    "Ginger.Application.View.Partial.SourcefileOptions" : function(sl) {
                        var v = $CL.makeObj('Ginger.Application.View.Partial.SourcefileOptions');
                        v.setTemplate($CL._template('application_sourcefile_options'));
                        return v;
                    },
                    'Ginger.Application.View.Partial.SourceDirectoryOptions' : function(sl) {
                        var v = $CL.makeObj('Ginger.Application.View.Partial.SourceDirectoryOptions');
                        v.setTemplate($CL._template('application_sourcedirectory_options'));
                        return v;
                    },
                    'Ginger.Application.View.Partial.TargetDirectoryOptions' : function(sl) {
                        var v = $CL.makeObj('Ginger.Application.View.Partial.TargetDirectoryOptions');
                        v.setTemplate($CL._template('application_targetdirectory_options'));
                        return v;
                    },
                    'Ginger.Application.View.Partial.SourceScriptOptions' : function(sl) {
                        var v = $CL.makeObj('Ginger.Application.View.Partial.SourceScriptOptions');
                        v.setTemplate($CL._template('application_sourcescript_options'));
                        return v;
                    },
                    'Ginger.Application.View.Partial.DevNullOptions' : function(sl) {
                        var v = $CL.makeObj('Ginger.Application.View.Partial.DevNullOptions');
                        v.setTemplate($CL._template('application_devnull_options'));
                        return v;
                    },
                    'Ginger.Application.View.Partial.AbstractFeatureOptions' : function(sl) {
                        var v = $CL.makeObj('Ginger.Application.View.Partial.AbstractFeatureOptions');
                        v.setTemplate($CL._template('application_abstract_feature_options'));
                        return v;
                    },
                    'Ginger.Application.View.Partial.ValidatorFeatureOptions' : function(sl) {
                        var v = $CL.makeObj('Ginger.Application.View.Partial.ValidatorFeatureOptions');
                        v.setTemplate($CL._template('application_validator_feature_options'));
                        return v;
                    },
                    'Ginger.Application.View.Partial.AttributeMapFeatureOptions' : function(sl) {
                        var v = $CL.makeObj('Ginger.Application.View.Partial.AttributeMapFeatureOptions');
                        v.setTemplate($CL._template('application_attributemap_feature_options'));
                        return v;
                    },
                    'Ginger.Application.View.Partial.StaticValueFeatureOptions' : function(sl) {
                        var v = $CL.makeObj('Ginger.Application.View.Partial.StaticValueFeatureOptions');
                        v.setTemplate($CL._template('application_staticvalue_feature_options'));
                        return v;
                    }
                },
                'non_shared_services' : [
                    "Ginger.Application.View.Partial.AbstractFeatureOptions"
                ]
            }
        }
    },
    onBootstrap : function(e) {
        //initialize Backbone.Layout
        $CL.get('Cl.Backbone.Layout', {id : 'js_content'});

        //register breadcrumbs and layout listener
        $CL.get("application").events().attach("render", function(e) {
            var layout = e.getResponse();

            if (layout && $CL.isInstanceOf(layout, Cl.Backbone.Layout)) {
                var activeUser = $CL.get('user_manager').getActiveUser();
                
                if (activeUser){
                    var uv = $CL.get('Ginger.Application.View.Partial.ActiveUser');
                    uv.setElement($('#head-nav-right'));
                    uv.setData(activeUser.toJSON());
                    uv.render();
                    layout.addChild(uv);
                }                
                
                var b = $CL.get("Ginger.Application.View.Helper.Breadcrumbs");
                b.setData(e.getParam('breadcrumbs', []));
                layout.addChild(b);

                var sidebar = e.getParam('sidebar');

                if (!sidebar || !$CL.isInstanceOf(sidebar, Cl.Backbone.View)) {
                    sidebar = $CL.get('Ginger.Dashboard.View.Index.Sidebar');
                }

                sidebar.setElement($('#js-sidebar'));
                layout.addChild(sidebar);

                var footer = e.getParam('footer');

                if (!footer || !$CL.isInstanceOf(footer, Cl.Backbone.View)) {
                    footer = $CL.get('Ginger.Application.View.Partial.Footer');
                }

                footer.setElement($('#js-footer'));
                layout.addChild(footer);
            }
        }, 90);

        var _checkSidebar = function() {
            if ($('#js-sidebar').children().first().hasClass('visible-large-desktop')
                && $('#js-sidebar').children().first().is(':hidden')) {
                $('#js-sidebar').parent().removeClass('span4').addClass('span0');
                $('#js_content').removeClass('span8').addClass('span12');
            } else {
                $('#js-sidebar').parent().removeClass('span0').addClass('span4');
                $('#js_content').removeClass('span12').addClass('span8');
            }

            if ($('#js-sidebar').children().first().hasClass('topbar-on-small-devices')) {
                $('#js-sidebar').children().first().removeClass('overrideDisplay');

                if ($('#js-sidebar').children().first().is(':hidden')) {
                    if ($('#js-sidebar').parent().hasClass('span4') || $('#js-sidebar').parent().hasClass('span0')) {
                        $('#js-sidebar').parent().removeClass('span4').addClass('span12');
                        $('#js-sidebar').children().first().addClass('overrideDisplay');

                        var $newRow = $('<div />').addClass('row-fluid').append($('#js-sidebar').parent());

                        $('#js_content').removeClass('span8').addClass('span12').parent().before($newRow);
                    }
                } else {
                    $('#js-sidebar').parent().removeClass('span12').addClass('span4');
                    $('#js-sidebar').children().first().removeClass('overrideDisplay');

                    $('#js_content').removeClass('span12').addClass('span8').after($('#js-sidebar').parent());

                    $('#js_content').prev().remove();
                }
            }

            if ($('#js-sidebar').children().first().hasClass('bottombar-on-small-devices')) {
                $('#js-sidebar').children().first().removeClass('overrideDisplay');

                if ($('#js-sidebar').children().first().is(':hidden')) {
                    if ($('#js-sidebar').parent().hasClass('span4') || $('#js-sidebar').parent().hasClass('span0')) {
                        $('#js-sidebar').parent().removeClass('span4').addClass('span12');
                        $('#js-sidebar').children().first().addClass('overrideDisplay');

                        var $newRow = $('<div />').attr('id', 'sidebar-to-bottombar').addClass('row-fluid').append($('#js-sidebar').parent());

                        $('#js_content').removeClass('span8').addClass('span12').parent().after($newRow);
                    }
                } else {
                    $('#js-sidebar').parent().removeClass('span12').addClass('span4');
                    $('#js-sidebar').children().first().removeClass('overrideDisplay');

                    $('#js_content').removeClass('span12').addClass('span8').after($('#js-sidebar').parent());

                    $('#js_content').next('#sidebar-to-bottombar').remove();
                }
            }
        }

        $CL.get("application").events().attach("finish", function() {
            _checkSidebar();
        });

        $(window).resize(function() {
            _checkSidebar();
        });
        
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
        
        //Register Auth Adpater to listen on ajax calls
        //to inject Api-Key and Request-Hash headers
        var authAdapter = $CL.get('auth_adapter');
        $CL.attachBeforeAjaxSend($CL.bind(authAdapter.onBeforeAjaxSend, authAdapter));
        //also register Auth Adapter to listen on application.alert events
        //to check if a response failed with status 401 
        $CL.app().events().attach('alert', [authAdapter.onAppAlert, authAdapter]);
    },
    getController : function(controllerName) {
        controllerName = controllerName.ucfirst();

        if ($CL.classExists("Ginger.Application.Controller." + controllerName)) {
            return $CL.get("Ginger.Application.Controller." + controllerName);
        } else {
            $CL.exception("unknown controllername", "Ginger.Application.Module", controllerName);
        }
    }
};

