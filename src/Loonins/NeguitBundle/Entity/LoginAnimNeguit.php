<?php

namespace Loonins\NeguitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LoginAnimNeguit
 *
 * @ORM\Table(name="login_anim_neguit")
 * @ORM\Entity(repositoryClass="Loonins\NeguitBundle\Repository\LoginAnimNeguitRepository")
 */
class LoginAnimNeguit
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
     * @ORM\Column(name="pseudo", type="string", length=255, unique=true)
     */
    private $pseudo;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetimetz")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="del", type="integer")
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
     * Set pseudo
     *
     * @param string $pseudo
     *
     * @return LoginAnimNeguit
     */
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get pseudo
     *
     * @return string
     */
    public function getPseudo()
    {
        return $this->pseudo;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return LoginAnimNeguit
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set del
     *
     * @param integer $del
     *
     * @return LoginAnimNeguit
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
