<?php
return array(    
    'service_manager' => array(
        'factories' => array(
            'css_manager' => 'Css\Service\CssManagerFactory',
        ),        
    ),
    'css_manager' => array(
        'files' => array()
    )
);
