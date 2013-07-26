<?php
namespace Ginger\Model\Feature;

use Cl\Test\PHPUnitTestCase;
use Ginger\Model\Connector\ConnectorEvent;
use MockObject\TableSource;
use MockObject\DummyTableTarget;

/**
 * Test class for AttributeMapFeature.
 * Generated by PHPUnit on 2013-04-26 at 22:26:38.
 */
class AttributeMapFeatureTest extends PHPUnitTestCase
{

    /**
     * @var AttributeMapFeature
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new AttributeMapFeature(1, 'AttributeMap', '/', 'Ginger.Application');
        $this->object->setServiceLocator($this->getApplication()->getServiceManager());
    }

    /**
     * @covers Ginger\Model\Feature\AttributeMapFeature::getType
     */
    public function testGetType()
    {
        $this->assertEquals('mapper', $this->object->getType());
    }

    /**
     * @covers Ginger\Model\Feature\AttributeMapFeature::alterValue
     */
    public function testAlterValue()
    {
        $source = new TableSource(1, 'TableSource', '/', 'MockObject');

        $source->setData(array(
            array('name' => 'nameAndLink')
        ));

        $target = new DummyTableTarget(1, 'dummyTable', '#', 'MockObject');

        $this->object->setOptions(array(
            'attributes_to_alter' => array(
                'name'
            ),
            'site_to_alter' => 'source',
            'attribute_map' => array(
                'name' => 'link'
            ),
        ));

        $e = new ConnectorEvent(ConnectorEvent::EVENT_MAP_ITEM);
        $e->setSource($source);
        $e->setTarget($target);
        $e->setItem(array('name' => 'nameAndLink'));

        $this->object->onMapItemPre($e);
        $response = $this->object->onMapItemPost($e);

        $this->assertNull($response);
        $this->assertEquals('nameAndLink', $e->getItem()['link']);
    }
}

?>
