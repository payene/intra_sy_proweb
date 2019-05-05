<?php

namespace Loonins\GesCongeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeDemande
 *
 * @ORM\Table(name="type_demande")
 * @ORM\Entity(repositoryClass="Loonins\GesCongeBundle\Repository\TypeDemandeRepository")
 */
class TypeDemande
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
     * @ORM\Column(name="libelle", type="string", length=255)
     */
    private $libelle;

    /**
     * @var int
     *
     * @ORM\Column(name="del", type="integer", nullable=true)
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
     * Set libelle
     *
     * @param string $libelle
     *
     * @return TypeDemande
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set del
     *
     * @param integer $del
     *
     * @return TypeDemande
     */
    public function setDel($del)
    {
        $this->del = $del;

        return $this;
    }

    /**
     * Get del
     *
     * @return int
     */
    public function getDel()
    {
        return $this->del;
    }
}

