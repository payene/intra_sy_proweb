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
     * @ORM\Column(name="finAffect", type="date",nullable=true)
     */
    private $finAffect;

    /**
     * @var \Loonins\NeguitBundle\Entity\AffectLoginNeguit
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\NeguitBundle\Entity\AffectLoginNeguit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="affectLogintNeguit", referencedColumnName="id")
     * })
     */
    private $affectLogintNeguit;


    /**
     * @var \Loonins\NeguitBundle\Entity\ProfilVirtuel
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\NeguitBundle\Entity\ProfilVirtuel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="profilVirtuel", referencedColumnName="id")
     * })
     */
    private $profilVirtuel;


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

    /**
     * Set affectLogintNeguit
     *
     * @param \Loonins\NeguitBundle\Entity\AffectLoginNeguit $affectLogintNeguit
     *
     * @return AffectFantomeNeguit
     */
    public function setAffectLogintNeguit(\Loonins\NeguitBundle\Entity\AffectLoginNeguit $affectLogintNeguit = null)
    {
        $this->affectLogintNeguit = $affectLogintNeguit;

        return $this;
    }

    /**
     * Get affectLogintNeguit
     *
     * @return \Loonins\NeguitBundle\Entity\AffectLoginNeguit
     */
    public function getAffectLogintNeguit()
    {
        return $this->affectLogintNeguit;
    }

    /**
     * Set profilVirtuel
     *
     * @param \Loonins\NeguitBundle\Entity\ProfilVirtuel $profilVirtuel
     *
     * @return AffectFantomeNeguit
     */
    public function setProfilVirtuel(\Loonins\NeguitBundle\Entity\ProfilVirtuel $profilVirtuel = null)
    {
        $this->profilVirtuel = $profilVirtuel;

        return $this;
    }

    /**
     * Get profilVirtuel
     *
     * @return \Loonins\NeguitBundle\Entity\ProfilVirtuel
     */
    public function getProfilVirtuel()
    {
        return $this->profilVirtuel;
    }



    public function __toString(){
        return  ($this->affectLogintNeguit)->getloginAnimNeguit()->getPseudo() . " / " . $this->profilVirtuel->getPseudo() ;
    }
}
