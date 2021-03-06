<?php
namespace Ginger\Model\File\Reader;
/**
 * Test class for Xml.
 * Generated by PHPUnit on 2013-06-30 at 20:45:15.
 */
class XmlTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Xml
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Xml;
    }

    /**
     * @covers Ginger\Model\File\Reader\Xml::fromFile
     */
    public function testFromFile()
    {
        $data = $this->object->fromFile('tests/PHPUnit/testdata/xml/list-data-paths.xml');

        $checkArr = array(
            array(
                'name' => 'Ginger IMS',
                'version' => '0.5',
                'developers' => array(
                    array(
                        'name' => 'Alexander Miertsch',
                        'skills' => array(
                            'PHP',
                            'JavaScript',
                            'ZF2',
                            'Backbone',
                            'Codeliner.js'
                        ),
                        'position' => 'architect'
                    ),
                    array(
                        'name' => 'D.F.',
                        'skills' => array(
                            'CSS',
                            'Design',
                            'CS Suite'
                        ),
                        'position' => 'grafic design'
                    )
                )
            ),
            array(
                'name' => 'Codeliner.js',
                'version' => '2.0',
                'developers' => array(
                    //if a list contains only one child, there is no generic way to detect it as list,
                    //so here we get the xml single element name as key and not zero as expected
                    'developer' => array(
                        'name' => 'Alexander Miertsch',
                        'skills' => array(
                            'PHP',
                            'JavaScript',
                            'ZF2',
                            'Backbone',
                            'Codeliner.js'
                        ),
                        'position' => 'architect'
                    ),
                )
            )
        );

        $this->assertEquals($checkArr, $data);
    }
}
