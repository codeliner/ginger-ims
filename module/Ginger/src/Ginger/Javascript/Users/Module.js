var Users = $CL.namespace('Ginger.Users');

$CL.require("Cl.Application.Module.ModuleInterface");

//controllers
$CL.require("Ginger.Users.Controller.Index");
//forms
$CL.require("Ginger.Users.Form.User");
//views
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
                    }
                }
            },
            service_manager : {
                factories : {
                    //views
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
    getController : function(controllerName) {
        controllerName = controllerName.ucfirst();

        if ($CL.classExists("Ginger.Users.Controller." + controllerName)) {
            return $CL.get("Ginger.Users.Controller." + controllerName);
        } else {
            $CL.exception("unknown controllername", "Ginger.Users.Module", controllerName);
        }
    }
}


