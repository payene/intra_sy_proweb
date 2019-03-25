<?php

namespace Loonins\GrhBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Loonins\GrhBundle\Validator\AntiNull;

/**
 * GrhContrats
 *
 * @ORM\Table(name="grh_contrats", indexes={@ORM\Index(name="type", columns={"type"}), @ORM\Index(name="employe", columns={"employe"})})
 * @ORM\Entity
 */
class GrhContrats {
//    /**
//     * @var integer
//     *
//     * @ORM\Column(name="type", type="integer", nullable=false)
//     */
//    private $type = '0';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date", nullable=false)
     */
    private $date = '0000-00-00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="debut", type="date", nullable=false)
     */
    private $debut = '0000-00-00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fin_reel", type="date", nullable=false)
     */
    private $finReel = '0000-00-00';

    /**
     * @var string
     *
     * @ORM\Column(name="motif_rupture", type="text", nullable=true)
     */
    private $motifRupture;

    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;

    /**
     * @var integer
     *
     * @ORM\Column(name="status", type="integer", nullable=false)
     */
    private $status = '0';


    /**
     * @var \GrhType
     *
     * @ORM\ManyToOne(targetEntity="GrhType", inversedBy="contrats")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="Id")
     * })
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var \GrhEmployes
     *
     * @ORM\ManyToOne(targetEntity="GrhEmployes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employe", referencedColumnName="Id")
     * })
     * @AntiNull()
     */
    private $employe;

    function __construct() {
        $this->date = new \DateTime;
        $this->debut = new \DateTime;
        $this->finReel = new \DateTime;
    }

//        /**
//     * Set type
//     *
//     * @param integer $type
//     * @return GrhContrats
//     */
//    public function setType($type)
//    {
//        $this->type = $type;
//
//        return $this;
//    }
//
//    /**
//     * Get type
//     *
//     * @return integer 
//     */
//    public function getType()
//    {
//        return $this->type;
//    }

    /**
     * Set debut
     *
     * @param \DateTime $debut
     * @return GrhContrats
     */
    public function setDebut($debut) {
        $this->debut = $debut;

        return $this;
    }

    /**
     * Get debut
     *
     * @return \DateTime 
     */
    public function getDebut() {
        return $this->debut;
    }

    /**
     * Set finReel
     *
     * @param \DateTime $finReel
     * @return GrhContrats
     */
    public function setFinReel($finReel) {
        $this->finReel = $finReel;

        return $this;
    }

    /**
     * Get finReel
     *
     * @return \DateTime 
     */
    public function getFinReel() {
        return $this->finReel;
    }

    /**
     * Set motifRupture
     *
     * @param string $motifRupture
     * @return GrhContrats
     */
    public function setMotifRupture($motifRupture) {
        $this->motifRupture = $motifRupture;

        return $this;
    }

    /**
     * Get motifRupture
     *
     * @return string 
     */
    public function getMotifRupture() {
        return $this->motifRupture;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     * @return GrhContrats
     */
    public function setCommentaire($commentaire) {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string 
     */
    public function getCommentaire() {
        return $this->commentaire;
    }

    /**
     * Set status
     *
     * @param integer $status
     * @return GrhContrats
     */
    public function setStatus($status) {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer 
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Set id
     *
     * @param \Loonins\GrhBundle\Entity\GrhType $id
     * @return GrhContrats
     */
    public function setId(\Loonins\GrhBundle\Entity\GrhType $id) {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return \Loonins\GrhBundle\Entity\GrhType 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set employe
     *
     * @param \Loonins\GrhBundle\Entity\GrhEmployes $employe
     * @return GrhContrats
     */
    public function setEmploye(\Loonins\GrhBundle\Entity\GrhEmployes $employe = null) {
        $this->employe = $employe;

        return $this;
    }

    /**
     * Get employe
     *
     * @return \Loonins\GrhBundle\Entity\GrhEmployes 
     */
    public function getEmploye() {
        return $this->employe;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return GrhContrats
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
     * Set type
     *
     * @param \Loonins\GrhBundle\Entity\GrhType $type
     * @return GrhContrats
     */
    public function setType(\Loonins\GrhBundle\Entity\GrhType $type = null) {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Loonins\GrhBundle\Entity\GrhType 
     */
    public function getType() {
        return $this->type;
    }

    public function __toString() {
        return $this->type . ' - ' . $this->employe;
    }

}
