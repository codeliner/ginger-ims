var Form = $CL.namespace('Ginger.Application.Form');

$CL.require('Cl.Form.Form');
$CL.require('Cl.Form.Element.Text');

Form.Login = function() {};

Form.Login = $CL.extendClass(Form.Login, Cl.Form.Form, {
    name : "Login",
    init : function() {
        this.elements = [
            $CL.makeObj('Cl.Form.Element.Text', {
                'name' : 'username',
                'label' : $CL.translate('LOGIN::FORM::LABEL::USERNAME')
            }),
            $CL.makeObj('Cl.Form.Element.Text', {
                'name' : 'password',
                'label' : $CL.translate('LOGIN::FORM::LABEL::PASSWORD')
            })
        ]
    }
});