<?php

namespace Loonins\IncidentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GdpStatus
 *
 * @ORM\Table(name="gdp_status")
 * @ORM\Entity
 */
class GdpStatus
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=45, nullable=true)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="num", type="integer", nullable=true)
     */
    private $num;

     /**     
     * @ORM\OneToMany(targetEntity="Loonins\IncidentBundle\Entity\GdpIncident", mappedBy="GdpStatus", cascade={"persist"})
    */
    private $incidents;


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
     * Set status
     *
     * @param string $status
     * @return GdpStatus
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set num
     *
     * @param integer $num
     * @return GdpStatus
     */
    public function setNum($num)
    {
        $this->num = $num;

        return $this;
    }

    /**
     * Get num
     *
     * @return integer 
     */
    public function getNum()
    {
        return $this->num;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->incidents = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add incidents
     *
     * @param \Loonins\IncidentBundle\Entity\GdpIncident $incidents
     * @return GdpStatus
     */
    public function addIncident(\Loonins\IncidentBundle\Entity\GdpIncident $incidents)
    {
        $this->incidents[] = $incidents;

        return $this;
    }

    /**
     * Remove incidents
     *
     * @param \Loonins\IncidentBundle\Entity\GdpIncident $incidents
     */
    public function removeIncident(\Loonins\IncidentBundle\Entity\GdpIncident $incidents)
    {
        $this->incidents->removeElement($incidents);
    }

    /**
     * Get incidents
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIncidents()
    {
        return $this->incidents;
    }

    public function __toString(){
        return $this->status;
    }
}
