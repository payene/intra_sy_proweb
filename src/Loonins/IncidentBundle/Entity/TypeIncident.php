<?php

namespace Loonins\IncidentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeIncident
 *
 * @ORM\Table(name="TypeIncident")
 * @ORM\Entity(repositoryClass="Loonins\IncidentBundle\Entity\TypeIncidentRepository")
 */
class TypeIncident
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;
    
    
    /**
     * 
     * @var boolean
     *
     * @ORM\Column(name="requireEmployee", type="boolean")
     */
    private $requireEmployee;


    /**
     * 
     * @var boolean
     *
     * @ORM\Column(name="requireTime", type="boolean")
     */
    private $requireTime;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return TypeIncident
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set requireEmployee
     *
     * @param boolean $requireEmployee
     * @return TypeIncident
     */
    public function setRequireEmployee($requireEmployee)
    {
        $this->requireEmployee = $requireEmployee;

        return $this;
    }

    /**
     * Get requireEmployee
     *
     * @return boolean 
     */
    public function getRequireEmployee()
    {
        return $this->requireEmployee;
    }

    public function __toString(){
        return $this->type;
    }

    /**
     * Set requireTime
     *
     * @param boolean $requireTime
     *
     * @return TypeIncident
     */
    public function setRequireTime($requireTime)
    {
        $this->requireTime = $requireTime;

        return $this;
    }

    /**
     * Get requireTime
     *
     * @return boolean
     */
    public function getRequireTime()
    {
        return $this->requireTime;
    }
}
