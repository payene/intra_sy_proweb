<?php

namespace Loonins\SuiviBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Planning
 *
 * @ORM\Table(name="planning")
 * @ORM\Entity(repositoryClass="Loonins\SuiviBundle\Repository\PlanningRepository")
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
     * @ORM\Column(name="heureDebut", type="string")
     */
    private $heureDebut;

    /**
     * @var string
     *
     * @ORM\Column(name="heureFin", type="string")
     */
    private $heureFin;

    /**
     * @var int
     *
     * @ORM\Column(name="semaine", type="integer")
     */
    private $semaine;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var \Loonins\SuiviBundle\Entity\Animatrice
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\SuiviBundle\Entity\Animatrice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="animatrice", referencedColumnName="id")
     * })
     */
    private $animatrice;

    /**
     * @var \Loonins\SuiviBundle\Entity\TypeTable
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\SuiviBundle\Entity\TypeTable")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="activite", referencedColumnName="id")
     * })
     */
    private $activite;

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
     * @param \DateTime $heureDebut
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
     * @return \DateTime
     */
    public function getHeureDebut()
    {
        return $this->heureDebut;
    }

    /**
     * Set heureFin
     *
     * @param \DateTime $heureFin
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
     * @return \DateTime
     */
    public function getHeureFin()
    {
        return $this->heureFin;
    }

    /**
     * Set semaine
     *
     * @param integer $semaine
     *
     * @return Planning
     */
    public function setSemaine($semaine)
    {
        $this->semaine = $semaine;

        return $this;
    }

    /**
     * Get semaine
     *
     * @return int
     */
    public function getSemaine()
    {
        return $this->semaine;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Planning
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
     * Set animatrice
     *
     * @param \Loonins\SuiviBundle\Entity\Animatrice $animatrice
     *
     * @return Planning
     */
    public function setAnimatrice(\Loonins\SuiviBundle\Entity\Animatrice $animatrice = null)
    {
        $this->animatrice = $animatrice;

        return $this;
    }

    /**
     * Get animatrice
     *
     * @return \Loonins\SuiviBundle\Entity\Animatrice
     */
    public function getAnimatrice()
    {
        return $this->animatrice;
    }

    /**
     * Set activite
     *
     * @param \Loonins\SuiviBundle\Entity\TypeTable $activite
     *
     * @return Planning
     */
    public function setActivite(\Loonins\SuiviBundle\Entity\TypeTable $activite = null)
    {
        $this->activite = $activite;

        return $this;
    }

    /**
     * Get activite
     *
     * @return \Loonins\SuiviBundle\Entity\TypeTable
     */
    public function getActivite()
    {
        return $this->activite;
    }
}
