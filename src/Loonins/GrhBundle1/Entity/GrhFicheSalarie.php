<?php

namespace Loonins\GrhBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrhFicheSalarie
 *
 * @ORM\Table(name="GrhFicheSalarie")
 * @ORM\Entity(repositoryClass="Loonins\GrhBundle\Entity\GrhFicheSalarieRepository")
 */
class GrhFicheSalarie {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text")
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \GrhTypeFiche
     *
     * @ORM\ManyToOne(targetEntity="GrhTypeFiche")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id",  nullable=false)
     * })
     */
    private $type;
    
    
    /**
     * @var \GrhEmployes
     *
     * @ORM\ManyToOne(targetEntity="GrhEmployes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employe", referencedColumnName="Id")
     * })
     */
    private $employe;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return GrhFicheSalarie
     */
    public function setTitre($titre) {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return GrhFicheSalarie
     */
    public function setContenu($contenu) {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu() {
        return $this->contenu;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return GrhFicheSalarie
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
     * @param \Loonins\GrhBundle\Entity\GrhTypeFiche $type
     * @return GrhFicheSalarie
     */
    public function setType(\Loonins\GrhBundle\Entity\GrhTypeFiche $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Loonins\GrhBundle\Entity\GrhTypeFiche 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set employe
     *
     * @param \Loonins\GrhBundle\Entity\GrhEmployes $employe
     * @return GrhFicheSalarie
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
}
