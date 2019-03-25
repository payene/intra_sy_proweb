<?php

// namespace Loonins\SuiviBundle\Entity;

// use Doctrine\ORM\Mapping as ORM;
// use Loonins\GrhBundle\Entity\GrhEmployes;
// use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
// /**
//  * Animatrice
//  *
//  * @ORM\Table(name="Animatrice")
//  * @ORM\Entity(repositoryClass="Loonins\SuiviBundle\Entity\AnimatriceRepository")
//  * 
//  */

// //@UniqueEntity(fields="login", message="Ce login animatrice n'est pas disponible.") 
// class Animatrice
// {
//     /**
//      * @var integer
//      *
//      * @ORM\Column(name="id", type="integer")
//      * @ORM\Id
//      * @ORM\GeneratedValue(strategy="AUTO")
//      */
//     private $id;

//     /**
//      * @var string
//      *
//      * @ORM\Column(name="login", type="string", length=25)
//     */
//     private $login;

//     /**
//      * @var \Loonins\GrhBundle\Entity\GrhEmployes
//      *
//      * @ORM\ManyToOne(targetEntity="\Loonins\GrhBundle\Entity\GrhEmployes")
//      * @ORM\JoinColumns({
//      *   @ORM\JoinColumn(name="employe", referencedColumnName="Id")
//      * })
//     **/
//     private $employe;

//     /**
//      * @var integer
//      *
//      * @ORM\Column(name="del", type="integer")
//      */
//     private $del;


//     /**
//      * Get id
//      *
//      * @return integer 
//      */
//     public function getId()
//     {
//         return $this->id;
//     }

//     /**
//      * Set login
//      *
//      * @param string $login
//      * @return Animatrice
//      */
//     public function setLogin($login)
//     {
//         $this->login = $login;

//         return $this;
//     }

//     /**
//      * Get login
//      *
//      * @return string 
//      */
//     public function getLogin()
//     {
//         return $this->login;
//     }


//     /**
//      * Set employe
//      *
//      * @param \Loonins\GrhBundle\Entity\GrhEmployes $employe
//      * @return Animatrice
//      */
//     public function setEmploye(\Loonins\GrhBundle\Entity\GrhEmployes $employe = null)
//     {
//         $this->employe = $employe;

//         return $this;
//     }

//     /**
//      * Get employe
//      *
//      * @return \Loonins\GrhBundle\Entity\GrhEmployes 
//      */
//     public function getEmploye()
//     {
//         return $this->employe;
//     }

//     public function __toString(){
//         return $this->login . " / ". $this->employe;
//     }

//     /**
//      * Set del
//      *
//      * @param integer $del
//      * @return Animatrice
//      */
//     public function setDel($del)
//     {
//         $this->del = $del;

//         return $this;
//     }

//     /**
//      * Get del
//      *
//      * @return integer 
//      */
//     public function getDel()
//     {
//         return $this->del;
//     }
// }
