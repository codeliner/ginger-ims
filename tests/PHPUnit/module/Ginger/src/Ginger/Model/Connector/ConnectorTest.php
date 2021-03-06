<?php
namespace Ginger\Model\Connector;

use Zend\EventManager\EventManager;
use Zend\EventManager\StaticEventManager;
use MockObject\Source;
use MockObject\Target;
use MockObject\Mapper;
/**
 * Test class for Connector.
 * Generated by PHPUnit on 2013-04-03 at 22:42:35.
 */
class ConnectorTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Connector
     */
    protected $object;

    /**
     *
     * @var Source
     */
    protected $source;

    /**
     *
     * @var Target
     */
    protected $target;

    /**
     *
     * @var Mapper
     */
    protected $mapper;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new Connector;

        $em = new EventManager();
        $this->object->setEventManager($em);

        $this->source = new Source(1, "testsource", "/testsource", "MockObject");
        $this->target = new Target(1, "testtarget", "/testtarget", "MockObject");
        $this->mapper = new Mapper(1, "testmapper", "/testmapper", "MockObject");
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        StaticEventManager::resetInstance();
    }

    protected function getMessageTexts($messages)
    {
        $texts = array();

        foreach ($messages as $message) {
            $texts[] = $message->getText();
        }

        return $texts;
    }

    public function testInsertItems()
    {
        $response = $this->object->insert($this->source, $this->target);

        $this->assertTrue($response['success']);
        $this->assertEquals(3, $response['count']);
        $this->assertEquals(3, count($this->target->getItems()));
    }

    public function testAttachListeners()
    {
        $response = $this->object->insert($this->source, $this->target, $this->mapper);

        $this->assertTrue($this->source->isStartTriggered());
        $this->assertTrue($this->target->isFinishInsertTriggered());

        $check = array(
            'item1MappedAndWritten',
            'item2MappedAndWritten',
            'item3MappedAndWritten'
        );

        $this->assertEquals($check, $this->target->getItems());
    }

    public function testCollectMessages()
    {
        $messageCheck = array(
            'insert is started',
            'item: item1Mapped is written',
            'item: item2Mapped is written',
            'item: item3Mapped is written',
            'insert is finished'
        );

        $response = $this->object->insert($this->source, $this->target, $this->mapper);

        $this->assertEquals($messageCheck, $this->getMessageTexts($response['messages']));
    }

    public function testAbortStartInsertOnFailure()
    {
        $this->object->getEventManager()->attach('start_insert', function($e) {
            $e->stopPropagation();
            return "insert is aborted on start";
        }, 100);

        $messageCheck = array(
            'insert is aborted on start',
        );

        $response = $this->object->insert($this->source, $this->target);

        $this->assertFalse($response['success']);
        $this->assertEquals(0, $response['count']);
        $this->assertEquals($messageCheck, $this->getMessageTexts($response['messages']));
        $this->assertEquals(0, count($this->target->getItems()));
    }

    public function testAbortMapItemOnFailure()
    {
        $this->object->getEventManager()->attach('map_item', function($e) {
            $item = $e->getItem();

            if ($item == 'item2') {
                $e->stopPropagation();
                return "insert is aborted on item2";
            }

        }, 100);

        $messageCheck = array(
            'insert is started',
            'item: item1 is written',
            'insert is aborted on item2',
        );

        $response = $this->object->insert($this->source, $this->target);

        $this->assertFalse($response['success']);
        $this->assertEquals(1, $response['count']);
        $this->assertEquals($messageCheck, $this->getMessageTexts($response['messages']));
        $this->assertEquals(1, count($this->target->getItems()));
    }

    public function testAbortPostWriteTargetItemOnFailure()
    {
        $this->object->getEventManager()->attach('post_write_target_item', function($e) {
            $item = $e->getItem();

            if ($item == 'item2Mapped') {
                $e->stopPropagation();
                return "insert is aborted on item2 post check";
            }

        }, 100);

        $messageCheck = array(
            'insert is started',
            'item: item1Mapped is written',
            'insert is aborted on item2 post check',
        );

        $response = $this->object->insert($this->source, $this->target, $this->mapper);

        $this->assertFalse($response['success']);
        $this->assertEquals(2, $response['count']);
        $this->assertEquals($messageCheck, $this->getMessageTexts($response['messages']));
        $this->assertEquals(2, count($this->target->getItems()));

        $check = array(
            'item1MappedAndWritten',
            'item2Mapped',
        );

        $this->assertEquals($check, $this->target->getItems());
    }

    public function testAbortFinishInsertOnFailure()
    {
        $this->object->getEventManager()->attach('finish_insert', function($e) {
            $e->stopPropagation();
            return "insert is aborted on finish";
        }, 100);

        $messageCheck = array(
            'insert is started',
            'item: item1 is written',
            'item: item2 is written',
            'item: item3 is written',
            'insert is aborted on finish'
        );

        $response = $this->object->insert($this->source, $this->target);

        $this->assertFalse($response['success']);
        $this->assertEquals(3, $response['count']);
        $this->assertEquals($messageCheck, $this->getMessageTexts($response['messages']));
        $this->assertEquals(3, count($this->target->getItems()));

        $this->assertFalse($this->target->isFinishInsertTriggered());
    }

    public function testDetachListenersOnSuccess()
    {
        $response = $this->object->insert($this->source, $this->target, $this->mapper);

        $check = array(
            'item1MappedAndWritten',
            'item2MappedAndWritten',
            'item3MappedAndWritten'
        );

        $this->assertEquals($check, $this->target->getItems());

        $easySource = new \MockObject\EasySource(2, "easysource", "/easysource", "MockObject");
        $easyTarget = new \MockObject\EasyTarget(2, "easytarget", "/easytarget", "MockObject");

        $this->object->insert($easySource, $easyTarget);

        $check = array(
            'item1',
            'item2',
            'item3',
        );

        $this->assertEquals($check, $easyTarget->getItems());
    }

    public function testCatchExceptionRolebackAndProvideInformation()
    {
        $this->object->getEventManager()->attach('post_write_target_item', function($e) {
            $item = $e->getItem();

            if ($item == 'item2Mapped') {
                throw new \Exception("insert is aborted after inserting item2");
            }

        }, 100);

        $this->object->getEventManager()->attach('roleback', function($e) {
            $target = $e->getTarget();

            $target->resetItems();

            return "target items reset";
        });


        $response = $this->object->insert($this->source, $this->target, $this->mapper);

        $messageCheck = array(
            'insert is started',
            'item: item1Mapped is written',
            'insert is aborted after inserting item2',
            'target items reset'
        );

        $this->assertFalse($response['success']);
        $this->assertEquals(0, count($this->target->getItems()));
        $this->assertEquals(2, $response['count']);

        error_log(print_r($response, true));

        $messages = $this->getMessageTexts($response['messages']);

        foreach ($messages as $i => $message) {
            $checkMsg = $messageCheck[$i];

            $this->assertTrue(strpos($message, $checkMsg) !== false, 'Haystack: ' . $message . ' - Needle: ' . $checkMsg );
        }
    }
}