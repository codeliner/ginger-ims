var Form = $CL.namespace('Ginger.Users.Form');

$CL.require('Cl.Form.Form');
$CL.require('Cl.Form.Element.Text');
$CL.require('Cl.Form.Element.Email');

Form.User = function() {};

Form.User = $CL.extendClass(Form.User, Cl.Form.Form, {
    name : "user",
    init : function() {
        this.elements = [            
            $CL.makeObj('Cl.Form.Element.Text', {
                'name' : 'lastname',
                'label' : $CL.translate('USERS::FORM::LABEL::LASTNAME')
            }),
            $CL.makeObj('Cl.Form.Element.Text', {
                'name' : 'firstname',
                'label' : $CL.translate('USERS::FORM::LABEL::FIRSTNAME')
            }),
            $CL.makeObj('Cl.Form.Element.Email', {
                'name' : 'email',
                'label' : $CL.translate('USERS::FORM::LABEL::EMAIL')
            }),
            $CL.makeObj('Cl.Form.Element.Text', {
                'name' : 'password',
                'label' : $CL.translate('USERS::FORM::LABEL::PASSWORD')
            })
        ]
    }
});