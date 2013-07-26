var Form = $CL.namespace('Ginger.Users.View.Form');

$CL.require('Cl.Backbone.FormView');
$CL.require('Cl.Form.View.InputText');
$CL.require('Cl.Form.View.Password');


Form.User = function() {};

Form.User = $CL.extendClass(Form.User, Cl.Backbone.FormView);