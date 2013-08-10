var User = $CL.namespace('Ginger.Users.Model.User');

$CL.require('Ginger.Users.Entity.User');

User.UserManager = function() {};

User.UserManager.prototype = {
    activeUser : null,
    authAdpater : null,
    usersCollection : null,
    setAuthAdapter : function(authAdapter) {
        this.authAdpater = authAdapter;
    },
    setUsersCollection : function(usersCollection) {
        this.usersCollection = usersCollection;
    },
    getActiveUser : function() {
        //Request for active user if no users are registered a dummy admin is returned 
        if (_.isNull(this.activeUser) 
            && (this.authAdpater.isValid() || !$CL.variable('$LOGIN_REQUIRED', false))) {
            $CL.app().wait();
            var activeUser = $CL.makeObj('Ginger.Users.Entity.User');
            var jqXhr = $CL.sjax().get('/rest/users/-1', function(data) {
                activeUser.set(data);
            }, 'json');
            
            var requestFailed = false;
            
            jqXhr.fail(function(jqXhr) {
                $CL.app().alert('Failed to fetch data for active user', jqXhr);
                requestFailed = true;
            });
            
            $CL.app().stopWait();
            
            if (!requestFailed) {
                this.usersCollection.add(activeUser);
                this.activeUser = activeUser;
            }
        }
        
        return this.activeUser;
    },
    logoutUser : function() {
        this.activeUser = null;
        this.authAdpater.clearCredentials();
    }
};