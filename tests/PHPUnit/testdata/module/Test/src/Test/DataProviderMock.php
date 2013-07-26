<?php
namespace Test;
/**
 * Description of DataProviderMock
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class DataProviderMock
{
    public function provideTestData()
    {
        return array(
            'module' => 'Test',
            'name' => 'installsource',
            'class' => 'MockObject\Source',
            'link' => '/installsource'
        );
    }
}