<?php
namespace Ginger\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\AbstractQuery;
use Ginger\Job\JobLoaderInterface;
use Ginger\Job\Job;
use Ginger\Model\Connector\Connector;
use Ginger\Model\Configuration\ConnectorConfiguration;
use Ginger\Model\Source\SourceLoaderInterface;
use Ginger\Model\Target\TargetLoaderInterface;
use Ginger\Model\Mapper\MapperLoaderInterface;
use Ginger\Model\Feature\FeatureLoaderInterface;
use Ginger\Entity\Job as JobEntity;
use Ginger\Entity\Configuration as ConfigurationEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Ginger\Job\Run\LoggerInterface;

/**
 * Description of JobRepository
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class JobRepository extends EntityRepository implements JobLoaderInterface
{
    /**
     *
     * @var LoggerInterface
     */
    protected $logger;

    /**
     *
     * @var SourceLoaderInterface
     */
    protected $sourceLoader;

    /**
     *
     * @var TargetLoaderInterface
     */
    protected $targetLoader;

    /**
     *
     * @var MapperLoaderInterface
     */
    protected $mapperLoader;

    /**
     *
     * @var FeatureLoaderInterface
     */
    protected $featureLoader;

    protected $translator;

    /**
     *
     * @var Connector
     */
    protected $connector;

    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function setConnector(Connector $connector)
    {
        $this->connector = $connector;
    }

    public function setSourceLoader($sourceLoader)
    {
        $this->sourceLoader = $sourceLoader;
    }

    public function setTargetLoader($targetLoader)
    {
        $this->targetLoader = $targetLoader;
    }

    public function setMapperLoader($mapperLoader)
    {
        $this->mapperLoader = $mapperLoader;
    }

    public function setFeatureLoader($featureLoader)
    {
        $this->featureLoader = $featureLoader;
    }

    public function setTranslator($translator)
    {
        $this->translator = $translator;
    }

    public function getJobNames()
    {
        $query = $this->createQueryBuilder('j');

        $query->select('j.name');

        $res = $query->getQuery()->getResult(AbstractQuery::HYDRATE_ARRAY);

        $names = array();

        foreach ($res as $nameArr) {
            $names[] = $nameArr['name'];
        }

        return $names;
    }

    public function getJobs()
    {
        $jobEntities = $this->findAll();

        $jobs = array();

        foreach($jobEntities as $jobEntity) {
            $jobs[] = $this->hydrateJob($jobEntity);
        }

        return $jobs;
    }

    public function loadJob($name)
    {
        $jobEntity = $this->findOneByName($name);

        if (!is_null($jobEntity)) {
            return $this->hydrateJob($jobEntity);
        } else {
            return null;
        }
    }

    public function saveJob(Job $job)
    {
        $jobEntity = $this->findOneByName($job->getName());
        $configCollection = new ArrayCollection();

        if ($jobEntity) {
            $configRepo = $this->getEntityManager()->getRepository('Ginger\Entity\Configuration');
            $newConfigIds = array();
            foreach ($job->getConfigurations() as $configuration) {
                if (is_null($configuration->getId())) {
                    $configEntity = null;
                } else {
                    $configEntity = $configRepo->find($configuration->getId());
                }

                if (is_null($configEntity)) {
                    $configEntity = new ConfigurationEntity();
                } else {
                    $newConfigIds[] = $configuration->getId();
                }

                $configEntity->setJob($jobEntity);
                $configEntity->setConfig($configuration->serialize());
                $configCollection->add($configEntity);
            }

            $configEntitiesToRemove = $jobEntity->getConfigurations()->filter(function($config) use ($newConfigIds) {
                return !in_array($config->getId(), $newConfigIds);
            });

            foreach($configEntitiesToRemove as $removedConfigEntity) {
                $this->getEntityManager()->remove($removedConfigEntity);
            }

        } else {
            $jobEntity = new JobEntity();
            $jobEntity->setName($job->getName());
            $jobEntity->setDescription($job->getDescription());

            foreach ($job->getConfigurations() as $configuration) {
                $configEntity = new ConfigurationEntity();
                $configEntity->setJob($jobEntity);
                $configEntity->setConfig($configuration->serialize());
                $configCollection->add($configEntity);
            }

            $this->getEntityManager()->persist($jobEntity);
        }

        $jobEntity->setConfigurations($configCollection);
        $jobEntity->setBreakOnFailure($job->getBreakOnFailure());
        $jobEntity->setDescription($job->getDescription());

        $this->getEntityManager()->flush();

        foreach ($job->getConfigurations() as $i => $configuration) {
            $configEntity = $configCollection[$i];
            $configuration->setId($configEntity->getId());
        }
    }

    public function hasJob($name)
    {
        $names = $this->getJobNames();

        return in_array($name, $names);
    }

    public function deleteJob($name)
    {
        $jobEntity = $this->findOneByName($name);

        if ($jobEntity) {
            $this->getEntityManager()->remove($jobEntity);
            $this->getEntityManager()->flush();
        } else {
            return false;
        }

        return true;
    }

    protected function hydrateJob(JobEntity $jobEntity)
    {
        $job = new Job($jobEntity->getName());
        $job->setDescription($jobEntity->getDescription());
        $job->setLogger($this->logger);
        $job->setConcector($this->connector);
        $job->setTranslator($this->translator);

        $configArr = array();

        foreach ($jobEntity->getConfigurations() as $configEntity) {
            $config = new ConnectorConfiguration();
            $config->setId($configEntity->getId());
            $config->setSourceLoader($this->sourceLoader);
            $config->setTargetLoader($this->targetLoader);
            $config->setMapperLoader($this->mapperLoader);
            $config->setFeatureLoader($this->featureLoader);
            $config->unserialize($configEntity->getConfig());
            $configArr[] = $config;
        }

        $job->setConfigurations($configArr);
        $job->setBreakOnFailure($jobEntity->getBreakOnFailure());

        return $job;
    }
}