<?php
namespace Ginger\Job;

use Ginger\Job\Run\LoggerInterface;
use Ginger\Job\Run\Message;
use Ginger\Model\Configuration\ConnectorConfiguration;
use Ginger\Model\Connector\Connector;
use Zend\I18n\Translator\Translator;
use Zend\Permissions\Acl\Resource\ResourceInterface;
/**
 * Description of Job
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class Job implements ResourceInterface
{
    protected $name;

    protected $description;

    /**
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     *
     * @var Translator
     */
    protected $translator;

    protected $configurations = array();

    protected $breakOnFailure = true;

    /**
     *
     * @var Connector
     */
    protected $connector;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function getResourceId()
    {
        return $this->getName();
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getConcector()
    {
        return $this->connector;
    }

    public function setConcector($concector)
    {
        $this->connector = $concector;
    }

    public function getLogger()
    {
        return $this->logger;
    }

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    public function getConfigurations()
    {
        return $this->configurations;
    }

    public function setConfigurations($configurations)
    {
        $this->configurations = $configurations;
    }

    public function addConfiguration(ConnectorConfiguration $config)
    {
        $this->configurations[] = $config;
    }

    public function getBreakOnFailure()
    {
        return $this->breakOnFailure;
    }

    public function setBreakOnFailure($breakOnFailure)
    {
        $this->breakOnFailure = $breakOnFailure;
    }

    public function getArrayCopy($withRelations = true, $maxRuns = 10, $skipRuns = 0)
    {
        $copy = array(
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'break_on_failure' => $this->getBreakOnFailure(),
            'configurations' => array(),
            'jobruns' => array(),
            'jobrun_count' => null,
            'jobrun_count_success' => null,
            'jobrun_count_failed' => null,
        );

        if ($withRelations) {
            foreach ($this->configurations as $config) {
                /* @var $config ConnectorConfiguration */
                $copy['configurations'][] = $config->getArrayCopy();
            }

            $jobruns = $this->logger->getJobRuns($this->getName(), $maxRuns, $skipRuns);

            foreach ($jobruns as $jobRun) {
                $copy['jobruns'][] = $jobRun->getArrayCopy();
            }

            $copy['jobrun_count'] = $this->logger->countJobRuns($this->getName());
            $copy['jobrun_count_success'] = $this->logger->countJobRuns($this->getName(), true);
            $copy['jobrun_count_failed'] = $this->logger->countJobRuns($this->getName(), false);
        }

        return $copy;
    }

    /**
     * Create a new jobrun, without starting the job
     * 
     * @return integer The jobrun id
     */
    public function createRun()
    {
        return $this->logger->startJobRun($this->name);
    }

    /**
     * Do the job
     * 
     * If a jobrun id is provided use this jobrun, otherwise create a new jobrun
     * 
     * @param integer $jobRunId
     * @return bool
     */
    public function run($jobRunId = null)
    {
        if (is_null($jobRunId)) {
            $jobRunId = $this->logger->startJobRun($this->name);
        }
        
        //We assume that the jobrun will be successful
        $success = true;

        foreach ($this->configurations as $config) {

            $configItemCount = 0;
            
            //We reset the job success flag on every configuration run
            //If there was an error befor, than the job is configured to continue
            //the process and as long as the last configuration run is successful
            //the hole jobrun is treated as successful
            $success = true;

            try {
                $configItemCount = $config->getSource()->getItemCount();

                $configRunId = $this->logger->startConfigurationRun(
                    $jobRunId,
                    $config->getId(),
                    $configItemCount
                    );
            } catch (\Exception $e) {
                error_log($e->__toString());
                
                //The source cann't return the number of items
                //so we start the configuration run but with an item count of zero
                $configRunId = $this->logger->startConfigurationRun(
                    $jobRunId,
                    $config->getId(),
                    0
                    );

                //Log the exception as an error message of the configuration run
                $this->log(
                    $configRunId,
                    sprintf(
                        $this->translator->translate('JOBS::JOBRUN::ERROR::START_CONFIGURATION'),
                        $config->getId(),
                        $e->__toString()
                        ),
                    Message::TYPE_ERROR);

                //Fake a response
                $response = array(
                    'success' => false,
                    'count' => 0
                );

                //Jumb over the hole process and stop the configuration run directly
                goto STOP_CONFIGRUN;
            }

            //Every connector element must implement \Zend\EventManager\ListenerAggregateInterface
            //to get the possibility to register on a connector event
            foreach ($config->getFeatures() as $feature) {
                $feature->attach($this->connector->getEventManager());
            }

            //Trigger the data transfer from source to target
            //The transfer is managed by the connector
            $response = $this->connector->insert(
                $config->getSource(),
                $config->getTarget(),
                $config->getMapper()
                );

            //The transfer is done, so let the features stop listining 
            //on connector events
            foreach ($config->getFeatures() as $feature) {
                $feature->detach($this->connector->getEventManager());
            }

            //Log all messages of the data transfer
            $this->log($configRunId, $response['messages']);


            STOP_CONFIGRUN:
                $insertedItemCount = $response['count'];

                //If the source hasn't provided an item count, 
                //we also log no result count (unknown items mode)
                if ($configItemCount == 0) {
                    $insertedItemCount = 0;
                }

                //Wasn't the configuration run successful and we should stop the job?
                if (!$response['success'] && $this->breakOnFailure) {
                    //then log a appropriate message
                    $this->log(
                        $configRunId,
                        $this->translator->translate('JOBS::JOBRUN::ERROR::JOB_ABORTED'),
                        Message::TYPE_ERROR
                        );
                    //stop the active configuration run
                    $this->logger->stopConfigurationRun($configRunId, $response['success'], $insertedItemCount);
                    //set the jobrun success flag to false
                    $success = false;
                    
                    //and exit the configuration run loop 
                    break;
                }

                //The last configuration run is the crucial factor 
                //for the job success flag
                if (!$response['success']) {
                    $success = false;
                }

                $this->logger->stopConfigurationRun($configRunId, $response['success'], $insertedItemCount);
        }

        STOP_JOBRUN:
            $this->logger->stopJobRun($jobRunId, $success);

            return $success;
    }

    protected function log($configRunId, $messageOrResponse, $type = null)
    {
        if (is_string($messageOrResponse)) {
            if (is_null($type)) {
                $type = Message::TYPE_INFO;
            }
            $message = new Message($type);
            $message->setText($messageOrResponse);

            $this->logger->logMessage($configRunId, $message);
        } else if (is_array($messageOrResponse)) {
            foreach ($messageOrResponse as $message) {
                $this->logger->logMessage($configRunId, $message);
            }
        }
    }
}