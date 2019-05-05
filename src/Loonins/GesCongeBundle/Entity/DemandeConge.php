<?php

namespace Loonins\GesCongeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * DemandeConge
 *
 * @ORM\Table(name="demande_conge")
 * @ORM\Entity(repositoryClass="Loonins\GesCongeBundle\Repository\DemandeCongeRepository")
 */
class DemandeConge
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
     * @ORM\Column(name="debut", type="date")
     */
    private $debut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fin", type="date",nullable=true)
     */
    private $fin;

    /**
     * @var int
     *
     * @ORM\Column(name="statut", type="integer")
     */
    private $statut;

    /**
     * @var int
     *
     * @ORM\Column(name="del", type="integer",nullable=true)
     */
    private $del;

    /**
     * @var string
     *
     * @ORM\Column(name="motif", type="string", length=255)
     */
    private $motif;


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
     * @var \Loonins\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="validateur", referencedColumnName="id")
     * })
     */
    private $validateur;


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
     * Set debut
     *
     * @param \DateTime $debut
     *
     * @return DemandeConge
     */
    public function setDebut($debut)
    {
        $this->debut = $debut;

        return $this;
    }

    /**
     * Get debut
     *
     * @return \DateTime
     */
    public function getDebut()
    {
        return $this->debut;
    }

    /**
     * Set fin
     *
     * @param \DateTime $fin
     *
     * @return DemandeConge
     */
    public function setFin($fin)
    {
        $this->fin = $fin;

        return $this;
    }

    /**
     * Get fin
     *
     * @return \DateTime
     */
    public function getFin()
    {
        return $this->fin;
    }

    /**
     * Set statut
     *
     * @param integer $statut
     *
     * @return DemandeConge
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return int
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set motif
     *
     * @param string $motif
     *
     * @return DemandeConge
     */
    public function setMotif($motif)
    {
        $this->motif = $motif;

        return $this;
    }

    /**
     * Get motif
     *
     * @return string
     */
    public function getMotif()
    {
        return $this->motif;
    }

    /**
     * Set employe
     *
     * @param \Loonins\GrhBundle\Entity\GrhEmployes $employe
     *
     * @return DemandeConge
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
     * Set validateur
     *
     * @param \Loonins\UserBundle\Entity\User $validateur
     *
     * @return DemandeConge
     */
    public function setValidateur(\Loonins\UserBundle\Entity\User $validateur = null)
    {
        $this->validateur = $validateur;

        return $this;
    }

    /**
     * Get validateur
     *
     * @return \Loonins\UserBundle\Entity\User
     */
    public function getValidateur()
    {
        return $this->validateur;
    }

    /**
     * Set typeDemande
     *
     * @param \Loonins\GesCongeBundle\Entity\TypeDemande $typeDemande
     *
     * @return DemandeConge
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

    /**
     * Set del
     *
     * @param integer $del
     *
     * @return DemandeConge
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
}
