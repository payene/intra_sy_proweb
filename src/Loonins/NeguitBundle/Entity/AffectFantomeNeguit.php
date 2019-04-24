<?php

namespace Loonins\NeguitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AffectFantomeNeguit
 *
 * @ORM\Table(name="affect_fantome_neguit")
 * @ORM\Entity(repositoryClass="Loonins\NeguitBundle\Repository\AffectFantomeNeguitRepository")
 */
class AffectFantomeNeguit
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
     * @ORM\Column(name="debutAffect", type="date")
     */
    private $debutAffect;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finAffect", type="date")
     */
    private $finAffect;


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
     * Set debutAffect
     *
     * @param \DateTime $debutAffect
     *
     * @return AffectFantomeNeguit
     */
    public function setDebutAffect($debutAffect)
    {
        $this->debutAffect = $debutAffect;

        return $this;
    }

    /**
     * Get debutAffect
     *
     * @return \DateTime
     */
    public function getDebutAffect()
    {
        return $this->debutAffect;
    }

    /**
     * Set finAffect
     *
     * @param \DateTime $finAffect
     *
     * @return AffectFantomeNeguit
     */
    public function setFinAffect($finAffect)
    {
        $this->finAffect = $finAffect;

        return $this;
    }

    /**
     * Get finAffect
     *
     * @return \DateTime
     */
    public function getFinAffect()
    {
        return $this->finAffect;
    }
}

