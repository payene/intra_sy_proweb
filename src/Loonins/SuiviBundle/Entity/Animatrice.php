<?php

namespace Loonins\SuiviBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Loonins\GrhBundle\Entity\GrhEmployes;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Animatrice
 *
 * @ORM\Table(name="Animatrice")
 * @ORM\Entity(repositoryClass="Loonins\SuiviBundle\Entity\AnimatriceRepository")
 * 
 */

//@UniqueEntity(fields="login", message="Ce login animatrice n'est pas disponible.") 
class Animatrice
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
     * @var \DateTime
     *
     * @ORM\Column(name="debutAffectation", type="datetime", nullable=true)
     */
    private $debutAffectation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finAffectation", type="datetime", nullable=true)
     */
    private $finAffectation;

    /**
     * @var \Loonins\GrhBundle\Entity\LoginAnim
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\SuiviBundle\Entity\LoginAnim")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="login", referencedColumnName="id")
     * })
    **/
    private $login;

    /**
     * @var \Loonins\GrhBundle\Entity\GrhEmployes
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\GrhBundle\Entity\GrhEmployes")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="employe", referencedColumnName="Id")
     * })
    **/
    private $employe;

    /**
     * @var integer
     *
     * @ORM\Column(name="del", type="integer")
     */
    private $del;


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
     * Set employe
     *
     * @param \Loonins\GrhBundle\Entity\GrhEmployes $employe
     * @return Animatrice
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

    public function __toString(){
        // return "". $this->employe;
        return $this->login . " / ". $this->employe;
    }

    /**
     * Set del
     *
     * @param integer $del
     * @return Animatrice
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

    /**
     * Set debutAffectation
     *
     * @param \DateTime $debutAffectation
     *
     * @return Animatrice
     */
    public function setDebutAffectation($debutAffectation)
    {
        $this->debutAffectation = $debutAffectation;

        return $this;
    }

    /**
     * Get debutAffectation
     *
     * @return \DateTime
     */
    public function getDebutAffectation()
    {
        return $this->debutAffectation;
    }

    /**
     * Set finAffectation
     *
     * @param \DateTime $finAffectation
     *
     * @return Animatrice
     */
    public function setFinAffectation($finAffectation)
    {
        $this->finAffectation = $finAffectation;

        return $this;
    }

    /**
     * Get finAffectation
     *
     * @return \DateTime
     */
    public function getFinAffectation()
    {
        return $this->finAffectation;
    }


    /**
     * Set login
     *
     * @param \Loonins\SuiviBundle\Entity\LoginAnim $login
     *
     * @return Animatrice
     */
    public function setLogin(\Loonins\SuiviBundle\Entity\LoginAnim $login = null)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return \Loonins\SuiviBundle\Entity\LoginAnim
     */
    public function getLogin()
    {
        return $this->login;
    }
}
