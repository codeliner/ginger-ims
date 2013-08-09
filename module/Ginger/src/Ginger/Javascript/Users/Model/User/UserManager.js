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
        if (_.isNull(this.activeUser) && !_.isNull(this.authAdpater.activeApiKey)) {
            $CL.app().wait();
            var activeUser = $CL.makeObj('Ginger.Users.Entity.User');
            var jqXhr = $CL.sjax().get('/rest/users/-1', function(data) {
                activeUser.set(data);
            }, 'json');
            
            jqXhr.fail(function(jqXhr) {
                $CL.app().alert('Failed to fetch data for active user', jqXhr);
            });
            
            $CL.app().stopWait();
            
            this.usersCollection.add(activeUser);
            this.activeUser = activeUser;
        }
        
        return this.activeUser;
    },
    logoutUser : function() {
        this.activeUser = null;
        this.authAdpater.clearCredentials();
    }
};