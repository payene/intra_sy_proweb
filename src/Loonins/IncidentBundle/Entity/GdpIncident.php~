<?php

namespace Loonins\IncidentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Loonins\GrhBundle\Entity\GrhEmployes;

/**
 * GdpIncident
 *
 * @ORM\Table(name="gdp_incident", indexes={@ORM\Index(name="index2", columns={"proprio"}), @ORM\Index(name="status", columns={"status"})})
 * @ORM\Entity * @ORM\HasLifecycleCallbacks()
 */
class GdpIncident {

    /**     * @ORM\PreUpdate */
    public function updateDate() {
        $this->setUpdateDate(new \Datetime);
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
     * @ORM\Column(name="titre", type="string", length=45, nullable=true)
     */
    private $titre;

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
     * @ORM\Column(name="dateEnreg", type="datetime", nullable=true)
     */
    private $dateEnreg;

    /**
     * @var string
     *
     * @ORM\Column(name="duree", type="text", nullable=true)
     */
    private $duree = "08:00";

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=true)
     */
    private $updateDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="cloture_date", type="datetime", nullable=true)
     */
    private $clotureDate;

    /**
     * @var \GdpStatus
     *
     * @ORM\ManyToOne(targetEntity="GdpStatus")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="status", referencedColumnName="id")
     * })
     */
    private $status;

    /**
     * @var \Loonins\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proprio", referencedColumnName="id")
     * })
     */
    private $proprio;

    /**
     * @var \Loonins\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="closer", referencedColumnName="id")
     * })
     */
    private $closer;

    /**
     * @var \Loonins\IncidentBundle\Entity\TypeIncident
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\IncidentBundle\Entity\TypeIncident")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="categorie", referencedColumnName="id")
     * })
     */
    private $categorie;

    /**
     * @var \Loonins\GrhBundle\Entity\GrhEmployes
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\GrhBundle\Entity\GrhEmployes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employe", referencedColumnName="Id")
     * })
     */
    private $employe;

    /**
     * @ORM\OneToMany(targetEntity="Loonins\IncidentBundle\Entity\GdpVersions", mappedBy="GdpIncident", cascade={"persist"})
     */
    private $versions;

    function __construct() {
        $this->date = new \DateTime;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return GdpIncident
     */
    public function setTitre($titre) {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return GdpIncident
     */
    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return GdpIncident
     */
    public function setDate($date) {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Set status
     *
     * @param \Loonins\IncidentBundle\Entity\GdpStatus $status
     * @return GdpIncident
     */
    public function setStatus(\Loonins\IncidentBundle\Entity\GdpStatus $status = null) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return \Loonins\IncidentBundle\Entity\GdpStatus 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set proprio
     *
     * @param \Loonins\UserBundle\Entity\User $proprio
     * @return GdpIncident
     */
    public function setProprio(\Loonins\UserBundle\Entity\User $proprio = null) {
        $this->proprio = $proprio;

        return $this;
    }

    /**
     * Get proprio
     *
     * @return \Loonins\UserBundle\Entity\User
     */
    public function getProprio() {
        return $this->proprio;
    }

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return GdpIncident
     */
    public function setUpdateDate($updateDate) {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime 
     */
    public function getUpdateDate() {
        return $this->updateDate;
    }

    /**
     * Add versions
     *
     * @param \Loonins\IncidentBundle\Entity\GdpVersions $versions
     * @return GdpIncident
     */
    public function addVersion(\Loonins\IncidentBundle\Entity\GdpVersions $versions) {
        $this->versions[] = $versions;

        return $this;
    }

    /**
     * Remove versions
     *
     * @param \Loonins\IncidentBundle\Entity\GdpVersions $versions
     */
    public function removeVersion(\Loonins\IncidentBundle\Entity\GdpVersions $versions) {
        $this->versions->removeElement($versions);
    }

    /**
     * Get versions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVersions() {
        return $this->versions;
    }

    public function __toString() {
        return (string) $this->getId();
    }

    /**
     * Set categorie
     *
     * @param \Loonins\IncidentBundle\Entity\TypeIncident $categorie
     * @return GdpIncident
     */
    public function setCategorie(\Loonins\IncidentBundle\Entity\TypeIncident $categorie = null) {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * Get categorie
     *
     * @return \Loonins\IncidentBundle\Entity\TypeIncident 
     */
    public function getCategorie() {
        return $this->categorie;
    }

    /**
     * Set clotureDate
     *
     * @param \DateTime $clotureDate
     * @return GdpIncident
     */
    public function setClotureDate($clotureDate) {
        $this->clotureDate = $clotureDate;

        return $this;
    }

    /**
     * Get clotureDate
     *
     * @return \DateTime 
     */
    public function getClotureDate() {
        return $this->clotureDate;
    }

    /**
     * Set closer
     *
     * @param \Loonins\UserBundle\Entity\User $closer
     * @return GdpIncident
     */
    public function setCloser(\Loonins\UserBundle\Entity\User $closer = null) {
        $this->closer = $closer;

        return $this;
    }

    /**
     * Get closer
     *
     * @return \Loonins\UserBundle\Entity\User 
     */
    public function getCloser() {
        return $this->closer;
    }


    /**
     * Set employe
     *
     * @param \Loonins\GrhBundle\Entity\GrhEmployes $employe
     * @return GdpIncident
     */
    public function setEmploye(\Loonins\GrhBundle\Entity\GrhEmployes $employe = null)
    {
        $this->employe = $employe;

        return $this;
    }

    /**
     * Get employe
     *
     * @return \Loonins\GrhBundle\Entity\GrhEmployes 
     */
    public function getEmploye()
    {
        return $this->employe;
    }

    /**
     * Set dateEnreg
     *
     * @param \DateTime $dateEnreg
     *
     * @return GdpIncident
     */
    public function setDateEnreg($dateEnreg)
    {
        $this->dateEnreg = $dateEnreg;

        return $this;
    }

    /**
     * Get dateEnreg
     *
     * @return \DateTime
     */
    public function getDateEnreg()
    {
        return $this->dateEnreg;
    }

    /**
     * Set duree
     *
     * @param string $duree
     *
     * @return GdpIncident
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return string
     */
    public function getDuree()
    {
        return $this->duree;
    }
}
