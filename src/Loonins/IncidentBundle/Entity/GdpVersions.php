<?php

namespace Loonins\IncidentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GdpVersions
 *
 * @ORM\Table(name="gdp_versions", indexes={@ORM\Index(name="index2", columns={"proprio"}), @ORM\Index(name="index3", columns={"incident"})})
 * @ORM\Entity * @ORM\HasLifecycleCallbacks()
 */
class GdpVersions
{
    /**     * @ORM\PreUpdate */
    public function updateDate() {
        $this->setUpdateDate(new \Datetime);
    }

    /**     * @ORM\PrePersist */
    public function updateDatePre() {
        $this->setUpdateDate($this->getDate());
    }
    
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime", nullable=true)
     */
    private $date;
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @var \GdpIncident
     *
     * @ORM\ManyToOne(targetEntity="GdpIncident", inversedBy="versions")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="incident", referencedColumnName="id")
     * })
     */
    private $incident;

    /**
     * @var \Loonins\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proprio", referencedColumnName="id")
     * })
     */
    private $proprio;

    
    function __construct() {
        $this->date = new \DateTime;
    }

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
     * Set description
     *
     * @param string $description
     * @return GdpVersions
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return GdpVersions
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set incident
     *
     * @param \Loonins\IncidentBundle\Entity\GdpIncident $incident
     * @return GdpVersions
     */
    public function setIncident(\Loonins\IncidentBundle\Entity\GdpIncident $incident = null)
    {
        $this->incident = $incident;

        return $this;
    }

    /**
     * Get incident
     *
     * @return \Loonins\IncidentBundle\Entity\GdpIncident 
     */
    public function getIncident()
    {
        return $this->incident;
    }

    /**
     * Set proprio
     *
     * @param  \Loonins\UserBundle\Entity\User $proprio
     * @return GdpVersions
     */
    public function setProprio( \Loonins\UserBundle\Entity\User $proprio = null)
    {
        $this->proprio = $proprio;

        return $this;
    }

    /**
     * Get proprio
     *
     * @return  \Loonins\UserBundle\Entity\User
     */
    public function getProprio()
    {
        return $this->proprio;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return GdpVersions
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }
}
