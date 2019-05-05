<?php

namespace Loonins\GesCongeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Solde
 *
 * @ORM\Table(name="solde")
 * @ORM\Entity(repositoryClass="Loonins\GesCongeBundle\Repository\SoldeRepository")
 */
class Solde
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
     * @ORM\Column(name="solde", type="integer")
     */
    private $solde;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="derniereMaj", type="datetime")
     */
    private $derniereMaj;


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
     * @var \Loonins\GesCongeBundle\Entity\TypeDemande
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\GesCongeBundle\Entity\TypeDemande")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="typeDemande", referencedColumnName="id")
     * })
     */
    private $typeDemande;



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
     * Set solde
     *
     * @param integer $solde
     *
     * @return Solde
     */
    public function setSolde($solde)
    {
        $this->solde = $solde;

        return $this;
    }

    /**
     * Get solde
     *
     * @return int
     */
    public function getSolde()
    {
        return $this->solde;
    }

    /**
     * Set derniereMaj
     *
     * @param \DateTime $derniereMaj
     *
     * @return Solde
     */
    public function setDerniereMaj($derniereMaj)
    {
        $this->derniereMaj = $derniereMaj;

        return $this;
    }

    /**
     * Get derniereMaj
     *
     * @return \DateTime
     */
    public function getDerniereMaj()
    {
        return $this->derniereMaj;
    }

    /**
     * Set employe
     *
     * @param \Loonins\GrhBundle\Entity\GrhEmployes $employe
     *
     * @return Solde
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
     * Set typeDemande
     *
     * @param \Loonins\GesCongeBundle\Entity\TypeDemande $typeDemande
     *
     * @return Solde
     */
    public function setTypeDemande(\Loonins\GesCongeBundle\Entity\TypeDemande $typeDemande = null)
    {
        $this->typeDemande = $typeDemande;

        return $this;
    }

    /**
     * Get typeDemande
     *
     * @return \Loonins\GesCongeBundle\Entity\TypeDemande
     */
    public function getTypeDemande()
    {
        return $this->typeDemande;
    }
}
