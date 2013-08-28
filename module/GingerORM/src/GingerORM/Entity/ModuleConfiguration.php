<?php
namespace GingerORM\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Description of ModuleConfiguration
 *
 * @ORM\Entity(repositoryClass="GingerORM\Repository\ModuleConfigurationRepository")
 * @ORM\Table(name="module_configuration")
 *
 * @author Alexander Miertsch <kontakt@codeliner.ws>
 * @copyright (c) 2013, Alexander Miertsch
 */
class ModuleConfiguration
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200, unique=true, nullable=false)
     */
    private $module;

    /**
     * @ORM\Column(type="string", nullable=false)
     */
    private $configuration;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getModule()
    {
        return $this->module;
    }

    public function setModule($module)
    {
        $this->module = $module;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }
}