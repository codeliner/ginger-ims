<?php
namespace MockObject;
/**
 * Description of FileDataStub
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class FileDataStub
{
    public static function getData()
    {
        return array(
            'firstKey' => 'a string',
            'secondKey' => 2,
            'thirdKey' => array(
                'one',
                'two',
                'three'
            ),
            'fourthKey' => array(
                'deepKey' => array(
                    'depestKey' => 'a string'
                )
            )
        );
    }
}