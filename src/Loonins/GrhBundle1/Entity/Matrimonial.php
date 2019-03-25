<?php

namespace Loonins\GrhBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Matrimonial
 *
 * @ORM\Table(name="Matrimonial")
 * @ORM\Entity(repositoryClass="Loonins\GrhBundle\Entity\MatrimonialRepository")
 */
class Matrimonial
{
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
     * @ORM\Column(name="sitMat", type="string", length=255)
     */
    private $sitMat;


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
     * Set sitMat
     *
     * @param string $sitMat
     * @return Matrimonial
     */
    public function setSitMat($sitMat)
    {
        $this->sitMat = $sitMat;

        return $this;
    }

    /**
     * Get sitMat
     *
     * @return string 
     */
    public function getSitMat()
    {
        return $this->sitMat;
    }

    public function __toString(){
        return $this->sitMat;
    }
}
