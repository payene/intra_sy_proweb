<?php

namespace Loonins\GrhBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * GrhType
 *
 * @ORM\Table(name="grh_type")
 * @ORM\Entity
 */
class GrhType
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @var integer
     *
     * @ORM\Column(name="duree", type="integer", nullable=true)
     */
    private $duree;

        
    /**
     * @var ArrayCollection contrats
     * @ORM\OneToMany(targetEntity="GrhContrats", mappedBy="type")
     * 
     */
    private $contrats;

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
     * Set type
     *
     * @param string $type
     * @return GrhType
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set duree
     *
     * @param integer $duree
     * @return GrhType
     */
    public function setDuree($duree)
    {
        $this->duree = $duree;

        return $this;
    }

    /**
     * Get duree
     *
     * @return integer 
     */
    public function getDuree()
    {
        return $this->duree;
    }
    
    public function __toString() {
        return (string)  $this->getType();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->contrats = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add contrat
     *
     * @param \Loonins\GrhBundle\Entity\GrhContrats $contrat
     *
     * @return GrhType
     */
    public function addContrat(\Loonins\GrhBundle\Entity\GrhContrats $contrat)
    {
        if( $contrat->getFinReel() <= (new \DateTime( date('Y-m-d') )) )
            $this->contrats[] = $contrat;

        return $this;
    }

    /**
     * Remove contrat
     *
     * @param \Loonins\GrhBundle\Entity\GrhContrats $contrat
     */
    public function removeContrat(\Loonins\GrhBundle\Entity\GrhContrats $contrat)
    {
        $this->contrats->removeElement($contrat);
    }

    /**
     * Get contrats
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getContrats()
    {
        $realcontrats = [];
        foreach ( $this->contrats as $contrat ) {
            if( $contrat->getStatus() == 1 )
                $realcontrats[] = $contrat;
        }

        return $realcontrats;
    }
}
