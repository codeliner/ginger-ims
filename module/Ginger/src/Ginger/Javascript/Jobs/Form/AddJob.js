var Form = $CL.namespace('Ginger.Jobs.Form');

$CL.require('Cl.Form.Form');
$CL.require('Cl.Form.Element.Text');
$CL.require('Cl.Form.Element.Textarea');

Form.AddJob = function() {};

Form.AddJob = $CL.extendClass(Form.AddJob, Cl.Form.Form, {
    name : "AddJob",
    init : function() {
        this.elements = [
            $CL.makeObj('Cl.Form.Element.Text', {
                'name' : 'name',
                'label' : $CL.translate('JOBS::FORM::LABEL::JOBNAME')
            }),
            $CL.makeObj('Cl.Form.Element.Textarea', {
                'name' : 'description',
                'label' : $CL.translate('FORM::LABEL::DESCRIPTION')
            })
        ]
    }
});