<?php

namespace Loonins\SuiviBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DemandeExplication
 *
 * @ORM\Table(name="demande_explication")
 * @ORM\Entity(repositoryClass="Loonins\SuiviBundle\Repository\DemandeExplicationRepository")
 */
class DemandeExplication
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateDemande", type="datetime")
     */
    private $dateDemande;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrEnvoi", type="integer")
     */
    private $nbrEnvoi;

    /**
     * @var \Loonins\SuiviBundle\Entity\Stat
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\SuiviBundle\Entity\Stat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="stat", referencedColumnName="id")
     * })
     */
    private $stat;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateDemande
     *
     * @param \DateTime $dateDemande
     *
     * @return DemandeExplication
     */
    public function setDateDemande($dateDemande)
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    /**
     * Get dateDemande
     *
     * @return \DateTime
     */
    public function getDateDemande()
    {
        return $this->dateDemande;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return DemandeExplication
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set nbrEnvoi
     *
     * @param integer $nbrEnvoi
     *
     * @return DemandeExplication
     */
    public function setNbrEnvoi($nbrEnvoi)
    {
        $this->nbrEnvoi = $nbrEnvoi;

        return $this;
    }

    /**
     * Get nbrEnvoi
     *
     * @return int
     */
    public function getNbrEnvoi()
    {
        return $this->nbrEnvoi;
    }

    /**
     * Set stat
     *
     * @param \Loonins\SuiviBundle\Entity\Stat $stat
     *
     * @return DemandeExplication
     */
    public function setStat(\Loonins\SuiviBundle\Entity\Stat $stat = null)
    {
        $this->stat = $stat;

        return $this;
    }

    /**
     * Get stat
     *
     * @return \Loonins\SuiviBundle\Entity\Stat
     */
    public function getStat()
    {
        return $this->stat;
    }
}
