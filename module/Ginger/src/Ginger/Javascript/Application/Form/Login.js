var Form = $CL.namespace('Ginger.Application.Form');

$CL.require('Cl.Form.Form');
$CL.require('Cl.Form.Element.Email');

Form.Login = function() {};

Form.Login = $CL.extendClass(Form.Login, Cl.Form.Form, {
    name : "Login",
    init : function() {
        this.elements = [
            $CL.makeObj('Cl.Form.Element.Email', {
                'name' : 'email',
                'label' : $CL.translate('LOGIN::FORM::LABEL::EMAIL')
            }),
            $CL.makeObj('Cl.Form.Element.Text', {
                'name' : 'password',
                'label' : $CL.translate('LOGIN::FORM::LABEL::PASSWORD')
            })
        ]
    }
});