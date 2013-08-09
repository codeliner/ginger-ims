var Controller = $CL.namespace("Ginger.Users.Controller");

$CL.require("Cl.Application.Mvc.AbstractController");

Controller.User = function() {};

Controller.User = $CL.extendClass(Controller.User, Cl.Application.Mvc.AbstractController, {
    usersCollection : null,
    setUsersCollection : function(usersCollection) {
        this.usersCollection = usersCollection;
    },
    createFirstUserAction : function() {
        var userData = this.getMvcEvent().getRouteMatch().getParam('userData');
        
        if (userData) {
            $CL.app().wait();
            
            var password = userData.password;
            this._addCredentialsData(userData);
            
            delete userData.password;
            
            var newUser = $CL.makeObj('Ginger.Users.Entity.User');
            this.usersCollection.add(newUser);
            newUser.set(userData);
            
            newUser.sync('create', newUser, {
                success : $CL.bind(function(response){
                    $CL.get('user_manager').logoutUser();
                    $CL.get('auth_adapter').setUsername(userData.email);
                    $CL.get('auth_adapter').setPassword(password);
                    $CL.get('auth_adapter').validCredentials = true;
                    
                    newUser.set(response);
                    
                    $CL.app().stopWait().router.callRoute('users_user_show', {id : response.id});
                }, this),
                error : $CL.bind(function(jqXhr, type, thrown) {
                    $CL.app().stopWait().alert("Failed to create user.", jqXhr);
                }, this)
            });
            
            this.getMvcEvent().stopPropagation();
        } else {
            $CL.exception('No userData found for first user', 'Ginger.Users.Controller.User');
        }
    },
    showAction : function() {
        var userId = this.getMvcEvent().getRouteMatch().getParam('id');
        
        var finish = $CL.bind(function(userData) {
            this._addBreadcrumbs('show', userData);
            var v = $CL.get('Ginger.Users.View.User.Show');
            v.setData(userData);
            return v;
        }, this);
        
        var user = this.usersCollection.get(userId);
        
        if (!user) {
            this.usersCollection.fetch()
            .done($CL.bind(function(data) {
                var userData = _.findWhere(data, {id : parseInt(userId)});
                this.getMvcEvent().setResponse(finish(userData));
                $CL.app().continueDispatch(this.getMvcEvent());
            }, this))
            .fail(function(jqXhr) {
                $CL.app().alert(
                'Failed to fetch data for user', 
                'Ginger.Users.Controller.User', 
                {
                    action : 'show',
                    userId : userId
                });
            }).always(function() {
                $CL.app().stopWait();
            });
            
            this.getMvcEvent().stopPropagation();
            return;
        }
        
        return finish(user.toJSON());
    },
    _addCredentialsData : function(userData) {
        userData['apiKey'] = $CL.get('auth_adapter').generateApiKey(userData.email);
        userData['secretKey'] = $CL.get('auth_adapter').generateSecretKey(userData.password);
    },
    _addBreadcrumbs : function(action, data) {

        var breadcrumbs = [];
        
        var activeUser = $CL.get('user_manager').getActiveUser();
        
        if (activeUser.get('isAdmin')) {
            var indexLink = ($CL.isDefined(action))? helpers.uri('users_overview') : '';

            breadcrumbs.push({link : indexLink, label : $CL.translate('HEADLINE::USERS')});
        }
        
        
        if ($CL.isDefined(action)) {

            var translations = {
                show : data.firstname + " " + data.lastname
            };

            breadcrumbs.push({
                link : '',
                label : translations[action]
            });
        }

        this.getMvcEvent().setParam(
            'breadcrumbs',
            breadcrumbs
        );
    }
});