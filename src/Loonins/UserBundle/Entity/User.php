<?php

namespace Loonins\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use FOS\UserBundle\Model\User as BaseUser;
//use FOS\UserBundle\Entity\User as BaseUser;

/**
 * User
 *
 * @ORM\Table(name="User")
 * @ORM\Entity(repositoryClass="Loonins\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
    * @ORM\OneToOne(targetEntity="\Loonins\GrhBundle\Entity\GrhEmployes", cascade={"persist", "remove"}, mappedBy="user")
    */
    protected $employe;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    public function __toString()
    {
        return (string) $this->getId();
    }

    /**
     * Set employe
     *
     * @param \Loonins\GrhBundle\Entity\GrhEmployes $employe
     *
     * @return User
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
