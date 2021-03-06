<?php

namespace Ginger\Model\Connector;

use MockObject\DummyTableTarget;
/**
 * Test class for AbstractElement.
 * Generated by PHPUnit on 2013-05-27 at 22:59:51.
 */
class AbstractElementTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var DummyTableTarget
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        //get access to protected methods via element mock, 
        //that provides public methods to call protected ones internaly
        $this->object = new DummyTableTarget(1, 'dummy', '#', 'MockObject');
    }

    public function testGetAttributeValueTableStructure()
    {
        $data = array(
            'firstname' => 'Alex',
            'lastname'  => 'Miertsch',
            'email'     => 'kontakt@codeliner.ws'
        );

        $this->assertEquals('Miertsch', $this->object->getAttrValue(AbstractElement::DATA_TYPE_TABLE_STRUCTURE, 'lastname', $data));
    }

    public function testGetAttributeValueDocumentStructure()
    {
        $data = array(
            'firstname' => 'Alex',
            'lastname'  => 'Miertsch',
            'email'     => 'kontakt@codeliner.ws',
            'address'   => array(
                'street' => 'main street',
                'street_number' => 10,
                'city'  => 'test city',
                'zip'   => '12345'
            )
        );

        $this->assertEquals('Miertsch', $this->object->getAttrValue(AbstractElement::DATA_TYPE_DOCUMENT_STRUCTURE, 'lastname', $data));
        $this->assertEquals('test city', $this->object->getAttrValue(AbstractElement::DATA_TYPE_DOCUMENT_STRUCTURE, 'address::city', $data));
    }

    public function testGetAttributeValueWithCollection()
    {
        $data = array(
            array(
                'city' => 'berlin',
                'country' => 'germany'
            ),
            array(
                'city' => 'london',
                'country' => 'england'
            ),
            array(
                'city' => 'paris',
                'country' => 'france'
            )
        );

        $check = array('berlin', 'london', 'paris');

        $this->assertEquals($check, $this->object->getAttrValue(AbstractElement::DATA_TYPE_DOCUMENT_STRUCTURE, '[]::city', $data));
    }

    public function testGetAttributeValueWithTwoCollections()
    {
        $data = array(
            'lastname' => 'Miertsch',
            'firstname' => 'Alex',
            'articles' => array(
                array(
                    'headline' => 'eC.Connect introductioon',
                    'comments' => array(
                        array(
                            'id' => 'a1_k1',
                        ),
                        array(
                            'id' => 'a1_k2',
                        )
                    )
                ),
                array(
                    'headline' => 'eC.Connect implementation',
                    'comments' => array(
                        array(
                            'id' => 'a2_k1',
                        ),
                        array(
                            'id' => 'a2_k2',
                        ),
                        array(
                            'id' => 'a2_k3',
                        )
                    )
                ),
                array(
                    'headline' => 'eC.Connect use cases',
                    'comments' => array(
                        array(
                            'id' => 'a3_k1',
                        ),
                        array(
                            'id' => 'a3_k2',
                        )
                    )
                )
            )
        );

        $check = array(
            array(
                'a1_k1', 'a1_k2'
            ),
            array(
                'a2_k1', 'a2_k2', 'a2_k3'
            ),
            array(
                'a3_k1', 'a3_k2'
            )
        );

        $this->assertEquals($check, $this->object->getAttrValue(AbstractElement::DATA_TYPE_DOCUMENT_STRUCTURE, 'articles::[]::comments::[]::id', $data));
    }

    public function testSetAttributeValueTableStructure()
    {
        $data = array('lastname' => 'Miertsch');
        $this->object->setAttrValue(AbstractElement::DATA_TYPE_TABLE_STRUCTURE, 'firstname', 'Alex', $data);

        $check = array(
            'lastname' => 'Miertsch',
            'firstname' => 'Alex',
        );

        $this->assertEquals($check, $data);
    }

    public function testSetAttributeValueDocumentStructure()
    {
        $data = array(
            'firstname' => 'Alex',
            'lastname'  => 'Miertsch',
            'email'     => 'kontakt@codeliner.ws',
            'address'   => array(
                'street_number' => 10,
                'city'  => 'test city',
                'zip'   => '12345'
            )
        );

        $this->object->setAttrValue(AbstractElement::DATA_TYPE_DOCUMENT_STRUCTURE, 'address::street', 'main street', $data);

        $check = array(
            'firstname' => 'Alex',
            'lastname'  => 'Miertsch',
            'email'     => 'kontakt@codeliner.ws',
            'address'   => array(
                'street_number' => 10,
                'city'   => 'test city',
                'zip'    => '12345',
                'street' => 'main street',
            )
        );

        $this->assertEquals($check, $data);
    }

    public function testSetAttributeValueWithCollection()
    {
        $data = array(
            array(
                'country' => 'germany'
            ),
            array(
                'country' => 'france'
            )
        );

        $check = array(
            array(
                'country' => 'germany',
                'city' => 'berlin',
            ),
            array(
                'country' => 'france',
                'city' => 'paris',
            ),
            array(
                'city' => 'london',
            ),
        );

        $this->object->setAttrValue(AbstractElement::DATA_TYPE_DOCUMENT_STRUCTURE, '[]::city', array('berlin', 'paris', 'london'), $data);

        $this->assertEquals($check, $data);
    }

    public function testSetAttributeValueWithTwoCollections()
    {
        $data = array(
            'lastname' => 'Miertsch',
            'firstname' => 'Alex',
            'articles' => array(
                array(
                    'headline' => 'eC.Connect introductioon',
                ),
                array(
                    'headline' => 'eC.Connect implementation',
                ),
                array(
                    'headline' => 'eC.Connect use cases',
                )
            )
        );

        $check = array(
            'lastname' => 'Miertsch',
            'firstname' => 'Alex',
            'articles' => array(
                array(
                    'headline' => 'eC.Connect introductioon',
                    'comments' => array(
                        array(
                            'id' => 'a1_k1',
                        ),
                        array(
                            'id' => 'a1_k2',
                        )
                    )
                ),
                array(
                    'headline' => 'eC.Connect implementation',
                    'comments' => array(
                        array(
                            'id' => 'a2_k1',
                        ),
                        array(
                            'id' => 'a2_k2',
                        ),
                        array(
                            'id' => 'a2_k3',
                        )
                    )
                ),
                array(
                    'headline' => 'eC.Connect use cases',
                    'comments' => array(
                        array(
                            'id' => 'a3_k1',
                        ),
                        array(
                            'id' => 'a3_k2',
                        )
                    )
                )
            )
        );

        $value = array(
            array(
                'a1_k1', 'a1_k2'
            ),
            array(
                'a2_k1', 'a2_k2', 'a2_k3'
            ),
            array(
                'a3_k1', 'a3_k2'
            )
        );

        $this->object->setAttrValue(AbstractElement::DATA_TYPE_DOCUMENT_STRUCTURE, 'articles::[]::comments::[]::id', $value, $data);

        $this->assertEquals($check, $data);
    }
}

?>
