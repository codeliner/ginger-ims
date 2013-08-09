var Users = $CL.namespace('Ginger.Users');

$CL.require("Cl.Application.Module.ModuleInterface");

//controllers
$CL.require("Ginger.Users.Controller.Index");
$CL.require("Ginger.Users.Controller.Auth");
//services
$CL.require("Ginger.Users.Service.Auth.Adapter");
//collections
$CL.require('Ginger.Users.Collection.Users');
//models
$CL.require("Ginger.Users.Model.User.UserManager");
//forms
$CL.require("Ginger.Users.Form.User");
$CL.require("Ginger.Users.Form.Login");
//views
$CL.require("Ginger.Users.View.Auth.Login");
$CL.require("Ginger.Users.View.Partial.ActiveUser");
$CL.require("Ginger.Users.View.Form.User");

Users.Module = function() {
    this.__IMPLEMENTS__ = [Cl.Application.Module.ModuleInterface];
};

Users.Module.prototype = {
    getConfig : function() {
        return {
            router : {
                routes : {
                    'users_overview' : {
                        route : 'users/overview/',
                        callback : function() {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Users.Module",
                                    controller : "index",
                                    action : "index"
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route;
                        }
                    },
                    'users_auth_login' : {
                        route : 'users/auth/login',
                        callback : function() {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Users.Module",
                                    controller : "auth",
                                    action : "login",
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route;
                        }
                    },
                    'users_auth_logout' : {
                        route : 'users/auth/logout',
                        callback : function() {
                            return $CL.makeObj(
                                "Cl.Application.Router.RouteMatch",
                                {
                                    module : "Ginger.Users.Module",
                                    controller : "auth",
                                    action : "logout",
                                }
                            );
                        },
                        build : function(routeParams) {
                            return this.route;
                        }
                    }
                }
            },
            service_manager : {
                factories : {
                    //controllers
                    'Ginger.Users.Controller.Auth' : function(sl) {
                        var c = $CL.makeObj('Ginger.Users.Controller.Auth');
                        c.setAuthAdapter(sl.get('auth_adapter'));
                        c.setUserManager(sl.get('user_manager'));
                        return c;
                    },   
                    //models
                    'user_manager' : function(sl) {
                        var um = $CL.makeObj('Ginger.Users.Model.User.UserManager');
                        
                        um.setAuthAdapter(sl.get('auth_adapter'));
                        um.setUsersCollection(sl.get('Ginger.Users.Collection.Users'));
                        
                        return um;
                    },
                    //services
                    'auth_adapter' : function(sl) {
                        return $CL.makeObj("Ginger.Users.Service.Auth.Adapter");
                    },
                    //views
                    "Ginger.Users.View.Auth.Login" : function(sl) {
                        var v = $CL.makeObj('Ginger.Users.View.Auth.Login');
                        v.setForm(sl.get('Ginger.Users.Form.Login'));
                        v.setTemplate($CL._template('users_auth_login'));
                        return v;
                    },
                    "Ginger.Users.View.Partial.ActiveUser" : function(sl) {
                        var v = $CL.makeObj("Ginger.Users.View.Partial.ActiveUser");
                        v.setTemplate($CL._template('users_nav_active_user'));
                        return v;
                    },
                    'Ginger.Users.View.Form.User' : function(sl) {
                        var v = $CL.makeObj('Ginger.Users.View.Form.User');
                        v.setForm($CL.makeObj('Ginger.Users.Form.User'));
                        v.setTemplate($CL._template('users_form_user'));
                        return v;
                    }
                }
            }
        };

    },
    onBootstrap : function(e) {
        //register breadcrumbs and layout listener
        $CL.get("application").events().attach("render", function(e) {
            var layout = e.getResponse();

            if (layout && $CL.isInstanceOf(layout, Cl.Backbone.Layout)) {
                var activeUser = $CL.get('user_manager').getActiveUser();
                
                if (activeUser){
                    var uv = $CL.get('Ginger.Users.View.Partial.ActiveUser');
                    uv.setElement($('#head-nav-right'));
                    uv.setData(activeUser.toJSON());
                    layout.addChild(uv);
                } 
            }
        });
        
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

        if ($CL.classExists("Ginger.Users.Controller." + controllerName)) {
            return $CL.get("Ginger.Users.Controller." + controllerName);
        } else {
            $CL.exception("unknown controllername", "Ginger.Users.Module", controllerName);
        }
    }
}


