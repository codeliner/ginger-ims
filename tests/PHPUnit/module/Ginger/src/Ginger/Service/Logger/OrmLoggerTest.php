<?php
namespace Ginger\Service\Logger;

use Cl\Test\DoctrineTestCase;
use Ginger\Job\Job;
use Ginger\Job\Run\Message;
use Ginger\Job\Task\JobTask;
/**
 * Test class for OrmLogger.
 * Generated by PHPUnit on 2013-04-08 at 07:14:28.
 */
class OrmLoggerTest extends DoctrineTestCase
{

    /**
     * @var OrmLogger
     */
    protected $object;

    protected function setUp()
    {
        $this->createEntitySchema('Ginger\Entity', 'module/Ginger/src/Ginger/Entity');
        $this->object = new OrmLogger;
        $this->object->setEntityManager($this->getTestEntityManager());

        $job = new Job('testjob');
        $task = new JobTask();
        $task->setId(1);
        $job->addTask($task);

        $this->getTestEntityManager()->getRepository('Ginger\Entity\Job')->saveJob($job);
    }

    /**
     * @covers Ginger\Service\Logger\OrmLogger::startJobRun
     */
    public function testStartJobRun()
    {
        $id = $this->object->startJobRun('testjob');

        $jobRun = $this->object->getJobRun($id);

        $this->assertInstanceOf('Ginger\Job\Run\JobRun', $jobRun);
        $this->assertInstanceOf('DateTime', $jobRun->getStartTime());
        $this->assertNull($jobRun->getEndTime());
        $this->assertFalse($jobRun->getSuccess());
    }

    /**
     * @covers Ginger\Service\Logger\OrmLogger::stopJobRun
     */
    public function testStopJobRun()
    {
        $id = $this->object->startJobRun('testjob');

        $this->object->stopJobRun($id, true);

        $jobRun = $this->object->getJobRun($id);

        $this->assertInstanceOf('Ginger\Job\Run\JobRun', $jobRun);
        $this->assertInstanceOf('DateTime', $jobRun->getStartTime());
        $this->assertInstanceOf('DateTime', $jobRun->getEndTime());
        $this->assertTrue($jobRun->getSuccess());
    }

    /**
     * @covers Ginger\Service\Logger\OrmLogger::getJobRuns
     */
    public function testGetJobRuns()
    {
        $ids = array();
        $ids[] = $this->object->startJobRun('testjob');
        $ids[] = $this->object->startJobRun('testjob');
        $ids[] = $this->object->startJobRun('testjob');

        $jobRuns = $this->object->getJobRuns('testjob');

        $checkIds = array();
        foreach ($jobRuns as $jobRun) {
            $checkIds[] = $jobRun->getId();
        }

        $this->assertEquals($ids, $checkIds);

        $maxIds = array($ids[0], $ids[1]);

        $jobRuns = $this->object->getJobRuns('testjob', 2);

        $checkIds = array();
        foreach ($jobRuns as $jobRun) {
            $checkIds[] = $jobRun->getId();
        }

        $this->assertEquals($maxIds, $checkIds);

        $maxAndSkipIds = array($ids[1], $ids[2]);

        $jobRuns = $this->object->getJobRuns('testjob', 2, 1);

        $checkIds = array();
        foreach ($jobRuns as $jobRun) {
            $checkIds[] = $jobRun->getId();
        }

        $this->assertEquals($maxAndSkipIds, $checkIds);
    }

    /**
     * @covers Ginger\Service\Logger\OrmLogger::startTaskRun
     */
    public function testStartTaskRun()
    {
        $jobRunId = $this->object->startJobRun('testjob');
        $taskRunId = $this->object->startTaskRun($jobRunId, 1, 3);

        $jobRun = $this->object->getJobRun($jobRunId);

        $taskRuns = $jobRun->getTaskRuns();

        $taskRun = $taskRuns[0];

        $this->assertInstanceOf('Ginger\Job\Run\TaskRun', $taskRun);
        $this->assertInstanceOf('DateTime', $taskRun->getStartTime());
        $this->assertEquals($taskRunId, $taskRun->getId());
        $this->assertEquals(1, $taskRun->getTaskId());
        $this->assertEquals(3, $taskRun->getTotalItemCount());
        $this->assertEquals(0, $taskRun->getInsertedItemCount());
        $this->assertNull($taskRun->getEndTime());
    }

    /**
     * @covers Ginger\Service\Logger\OrmLogger::logMessage
     */
    public function testLogMessage()
    {
        $jobRunId = $this->object->startJobRun('testjob');
        $taskRunId = $this->object->startTaskRun($jobRunId, 1, 3);
        $this->object->logMessage($taskRunId, new Message('info', 'a test message'));

        $jobRun = $this->object->getJobRun($jobRunId);

        $taskRuns = $jobRun->getTaskRuns();

        $taskRun = $taskRuns[0];

        $message = $taskRun->getMessages()[0];

        $this->assertEquals('a test message', $message->getText());
        $this->assertEquals('info', $message->getType());
        $this->assertInstanceOf('DateTime', $message->getTimestamp());
    }

    /**
     * @covers Ginger\Service\Logger\OrmLogger::stopTaskRun
     */
    public function testStopTaskRun()
    {
        $jobRunId = $this->object->startJobRun('testjob');
        $taskRunId = $this->object->startTaskRun($jobRunId, 1, 3);
        $this->object->stopTaskRun($taskRunId, true, 2);

        $jobRun = $this->object->getJobRun($jobRunId);

        $taskRuns = $jobRun->getTaskRuns();

        $taskRun = $taskRuns[0];

        $this->assertEquals($taskRunId, $taskRun->getId());
        $this->assertEquals(1, $taskRun->getTaskId());
        $this->assertEquals(3, $taskRun->getTotalItemCount());
        $this->assertEquals(2, $taskRun->getInsertedItemCount());
        $this->assertInstanceOf('DateTime', $taskRun->getEndTime());
    }

    /**
     * @covers Ginger\Service\Logger\OrmLogger::getLatestJobRuns
     */
    public function testGetLatestJobRuns()
    {
        $job = new Job('latestjob');
        $task = new JobTask();
        $task->setId(2);
        $job->addTask($task);

        $this->getTestEntityManager()->getRepository('Ginger\Entity\Job')->saveJob($job);

        $ids = array();
        $ids[] = $this->object->startJobRun('testjob');
        sleep(1);
        $ids[] = $this->object->startJobRun('testjob');
        sleep(1);
        $ids[] = $this->object->startJobRun('latestjob');

        //last id is the one of the latest jobRun
        $ids = array_reverse($ids);

        $jobRuns = $this->object->getLatestJobRuns();

        $checkIds = array();
        foreach ($jobRuns as $jobRun) {
            $checkIds[] = $jobRun->getId();
        }

        $this->assertEquals($ids, $checkIds);
    }
}

?>
