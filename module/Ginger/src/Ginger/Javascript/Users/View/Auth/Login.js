var Auth = $CL.namespace('Ginger.Users.View.Auth');

$CL.require('Cl.Backbone.FormView');
$CL.require('Cl.Form.View.InputText');
$CL.require('Cl.Form.View.Password');

Auth.Login = function() {};

Auth.Login = $CL.extendClass(Auth.Login, Cl.Backbone.FormView);