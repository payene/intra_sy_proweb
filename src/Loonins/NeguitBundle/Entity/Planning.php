<?php

namespace Loonins\NeguitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Planning
 *
 * @ORM\Table(name="planning")
 * @ORM\Entity(repositoryClass="Loonins\NeguitBundle\Repository\PlanningRepository")
 */
class Planning
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
     * @var string
     *
     * @ORM\Column(name="heureDebut", type="string", length=5)
     */
    private $heureDebut;

    /**
     * @var string
     *
     * @ORM\Column(name="heureFin", type="string", length=5)
     */
    private $heureFin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePlanning", type="datetime")
     */
    private $datePlanning;

    /**
     * @var ArrayCollection planification
     * @ORM\OneToMany(targetEntity="\Loonins\NeguitBundle\Entity\Plan", mappedBy="planning")
     * 
     */
    private $planification;


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
     * Set heureDebut
     *
     * @param string $heureDebut
     *
     * @return Planning
     */
    public function setHeureDebut($heureDebut)
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    /**
     * Get heureDebut
     *
     * @return string
     */
    public function getHeureDebut()
    {
        return $this->heureDebut;
    }

    /**
     * Set heureFin
     *
     * @param string $heureFin
     *
     * @return Planning
     */
    public function setHeureFin($heureFin)
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    /**
     * Get heureFin
     *
     * @return string
     */
    public function getHeureFin()
    {
        return $this->heureFin;
    }

    /**
     * Set typeAnim
     *
     * @param integer $typeAnim
     *
     * @return Planning
     */
    public function setTypeAnim($typeAnim)
    {
        $this->typeAnim = $typeAnim;

        return $this;
    }

    /**
     * Get typeAnim
     *
     * @return int
     */
    public function getTypeAnim()
    {
        return $this->typeAnim;
    }

    /**
     * Set datePlanning
     *
     * @param \DateTime $datePlanning
     *
     * @return Planning
     */
    public function setDatePlanning($datePlanning)
    {
        $this->datePlanning = $datePlanning;

        return $this;
    }

    /**
     * Get datePlanning
     *
     * @return \DateTime
     */
    public function getDatePlanning()
    {
        return $this->datePlanning;
    }
}

