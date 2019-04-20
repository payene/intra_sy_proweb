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
}






// // src/LooninsUserBundle/Entity/User.php

// namespace LooninsUserBundle\Entity;

// use FOS\UserBundle\Model\User as BaseUser;
// use Doctrine\ORM\Mapping as ORM;

// /**
//  *User
//  *
//  * @ORM\Table()
//  * @ORM\Entity
//  */
// class User extends BaseUser
// {
//     /**
//      * @ORM\Id
//      * @ORM\Column(type="integer")
//      * @ORM\GeneratedValue(strategy="AUTO")
//      */
//     protected $id;

//     public function __construct()
//     {
//         parent::__construct();
//         // your own logic
//     }
// }