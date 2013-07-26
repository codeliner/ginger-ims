var Index = $CL.namespace('Ginger.Jobs.View.Index');

$CL.require('Cl.Backbone.FormView');
$CL.require('Cl.Form.View.InputText');
$CL.require('Cl.Form.View.Textarea');

Index.AddJobForm = function() {};

Index.AddJobForm = $CL.extendClass(Index.AddJobForm, Cl.Backbone.FormView);