<?php

namespace Loonins\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanningHebdo
 *
 * @ORM\Table(name="planning_hebdo")
 * @ORM\Entity(repositoryClass="Loonins\CalendarBundle\Repository\PlanningHebdoRepository")
 */
class PlanningHebdo
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
     * @var int
     *
     * @ORM\Column(name="numWeek", type="integer")
     */
    private $numWeek;

    /**
     * @var int
     *
     * @ORM\Column(name="mois", type="integer")
     */
    private $mois;

    /**
     * @var int
     *
     * @ORM\Column(name="annee", type="integer")
     */
    private $annee;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

 
    /**
     * @var \Loonins\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="createdBy", referencedColumnName="id")
     * })
     */
    private $createdBy;

     /**
     * @var int
     *
     * @ORM\Column(name="del", type="integer",nullable=true)
     */
    private $del;



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
     * Set numWeek
     *
     * @param integer $numWeek
     *
     * @return PlanningHebdo
     */
    public function setNumWeek($numWeek)
    {
        $this->numWeek = $numWeek;

        return $this;
    }

    /**
     * Get numWeek
     *
     * @return integer
     */
    public function getNumWeek()
    {
        return $this->numWeek;
    }

    /**
     * Set mois
     *
     * @param integer $mois
     *
     * @return PlanningHebdo
     */
    public function setMois($mois)
    {
        $this->mois = $mois;

        return $this;
    }

    /**
     * Get mois
     *
     * @return integer
     */
    public function getMois()
    {
        return $this->mois;
    }

    /**
     * Set annee
     *
     * @param integer $annee
     *
     * @return PlanningHebdo
     */
    public function setAnnee($annee)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return integer
     */
    public function getAnnee()
    {
        return $this->annee;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return PlanningHebdo
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PlanningHebdo
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set del
     *
     * @param integer $del
     *
     * @return PlanningHebdo
     */
    public function setDel($del)
    {
        $this->del = $del;

        return $this;
    }

    /**
     * Get del
     *
     * @return integer
     */
    public function getDel()
    {
        return $this->del;
    }

    /**
     * Set createdBy
     *
     * @param \Loonins\UserBundle\Entity\User $createdBy
     *
     * @return PlanningHebdo
     */
    public function setCreatedBy(\Loonins\UserBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Loonins\UserBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
