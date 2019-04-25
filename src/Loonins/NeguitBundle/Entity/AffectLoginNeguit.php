<?php

namespace Loonins\NeguitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AffectLoginNeguit
 *
 * @ORM\Table(name="affect_login_neguit")
 * @ORM\Entity(repositoryClass="Loonins\NeguitBundle\Repository\AffectLoginNeguitRepository")
 */
class AffectLoginNeguit
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
     * @ORM\Column(name="debutAffectation", type="datetime")
     */
    private $debutAffectation;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finAffectation", type="date",nullable=true)
     */
    private $finAffectation;


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
     * @var \Loonins\NeguitBundle\Entity\LoginAnimNeguit
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\NeguitBundle\Entity\LoginAnimNeguit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="loginAnimNeguit", referencedColumnName="id")
     * })
     */
    private $loginAnimNeguit;



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
     * Set debutAffectation
     *
     * @param \DateTime $debutAffectation
     *
     * @return AffectLoginNeguit
     */
    public function setDebutAffectation($debutAffectation)
    {
        $this->debutAffectation = $debutAffectation;

        return $this;
    }

    /**
     * Get debutAffectation
     *
     * @return \DateTime
     */
    public function getDebutAffectation()
    {
        return $this->debutAffectation;
    }

    /**
     * Set finAffectation
     *
     * @param \DateTime $finAffectation
     *
     * @return AffectLoginNeguit
     */
    public function setFinAffectation($finAffectation)
    {
        $this->finAffectation = $finAffectation;

        return $this;
    }

    /**
     * Get finAffectation
     *
     * @return \DateTime
     */
    public function getFinAffectation()
    {
        return $this->finAffectation;
    }

    /**
     * Set employe
     *
     * @param \Loonins\GrhBundle\Entity\GrhEmployes $employe
     *
     * @return AffectLoginNeguit
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
     * Set loginAnimNeguit
     *
     * @param \Loonins\NeguitBundle\Entity\LoginAnimNeguit $loginAnimNeguit
     *
     * @return AffectLoginNeguit
     */
    public function setLoginAnimNeguit(\Loonins\NeguitBundle\Entity\LoginAnimNeguit $loginAnimNeguit = null)
    {
        $this->loginAnimNeguit = $loginAnimNeguit;

        return $this;
    }

    /**
     * Get loginAnimNeguit
     *
     * @return \Loonins\NeguitBundle\Entity\LoginAnimNeguit
     */
    public function getLoginAnimNeguit()
    {
        return $this->loginAnimNeguit;
    }

     public function __toString(){
        return  ($this->loginAnimNeguit)->getPseudo() . " / " . $this->employe ;
    }

}
