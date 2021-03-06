<?php
namespace Ginger\Model\File;

use Ginger\Model\Directory\Inbox;
use Ginger\Model\Directory\Outbox;
/**
 * Test class for File.
 * Generated by PHPUnit on 2013-05-28 at 22:50:45.
 */
class FileTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var File
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        file_put_contents(Outbox::DIR . '/test.json', json_encode(array(
            array(
                'id' => 1,
                'name' => 'first entry'
            ),
            array(
                'id' => 2,
                'name' => 'second entry'
            ),
        )));
        $this->object = new File(Outbox::DIR, 'test.json');
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        Inbox::deleteFiles('/^test\.json$/');
        Outbox::deleteFiles();
    }

    /**
     * @covers Ginger\Model\File\File::getFilename
     */
    public function testGetFilename()
    {
        $this->assertEquals('test.json', $this->object->getFilename());
    }

    /**
     * @covers Ginger\Model\File\File::getDir
     */
    public function testGetDir()
    {
        $this->assertEquals(Outbox::DIR, $this->object->getDir());
    }

    /**
     * @covers Ginger\Model\File\File::getPath
     */
    public function testGetPath()
    {
        $this->assertEquals(Outbox::DIR . '/test.json', $this->object->getPath());
    }

    /**
     * @covers Ginger\Model\File\File::getData
     */
    public function testGetData()
    {
        $check = array(
            array(
                'id' => 1,
                'name' => 'first entry'
            ),
            array(
                'id' => 2,
                'name' => 'second entry'
            ),
        );

        $this->assertEquals($check, $this->object->getData());
    }

    /**
     * @covers Ginger\Model\File\File::writeData
     */
    public function testWriteData()
    {
        $this->object->writeData(array(
            array(
                'id' => 1,
                'name' => 'new entry'
            )
        ));

        $check = array(
            array(
                'id' => 1,
                'name' => 'new entry'
            )
        );

        $this->assertEquals($check, $this->object->getData());
    }

    /**
     * @covers Ginger\Model\File\File::appendElement
     */
    public function testAppendElement()
    {
        $this->object->appendElement(
            array(
                'id' => 3,
                'name' => 'third entry'
            )
        );

        $check = array(
            array(
                'id' => 1,
                'name' => 'first entry'
            ),
            array(
                'id' => 2,
                'name' => 'second entry'
            ),
            array(
                'id' => 3,
                'name' => 'third entry'
            )
        );

        $this->assertEquals($check, $this->object->getData());
    }

    /**
     * @covers Ginger\Model\File\File::mergeData
     */
    public function testMergeData()
    {
        $this->object->writeData(array(
            'id' => 1,
            'name' => 'a name'
        ));

        $check = array(
            'id' => 1,
            'name' => 'a name'
        );

        $this->assertEquals($check, $this->object->getData());

        $this->object->mergeData(array('name' => 'another name'));

        $check = array(
            'id' => 1,
            'name' => 'another name'
        );

        $this->assertEquals($check, $this->object->getData());
    }

    /**
     * @covers Ginger\Model\File\File::rename
     */
    public function testRename()
    {
        $this->object->rename('renamed.json');

        $this->assertTrue(is_file(Outbox::DIR . '/renamed.json'));
        $this->assertEquals('renamed.json', $this->object->getFilename());
    }

    /**
     * @covers Ginger\Model\File\File::move
     */
    public function testMove()
    {
        $file = $this->object->move(Inbox::DIR);

        $this->assertTrue(is_file(Inbox::DIR . '/test.json'));
        $this->assertFalse(is_file(Outbox::DIR . '/test.json'));
        $this->assertEquals(Inbox::DIR, $file->getDir());
        $this->assertEquals(Inbox::DIR, $this->object->getDir());
    }

    /**
     * @covers Ginger\Model\File\File::copy
     */
    public function testCopy()
    {
        $file = $this->object->copy(Inbox::DIR);

        $this->assertTrue(is_file(Inbox::DIR . '/test.json'));
        $this->assertTrue(is_file(Outbox::DIR . '/test.json'));
        $this->assertEquals(Inbox::DIR, $file->getDir());
        $this->assertEquals(Outbox::DIR, $this->object->getDir());
    }

    /**
     * @covers Ginger\Model\File\File::remove
     */
    public function testRemove()
    {
        $this->object->remove();
        $this->assertFalse(is_file(Outbox::DIR . '/test.json'));
    }

}

?>
