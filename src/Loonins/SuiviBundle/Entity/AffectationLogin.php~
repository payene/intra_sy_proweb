<?php

namespace Loonins\SuiviBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AffectationLogin
 *
 * @ORM\Table(name="affectation_login")
 * @ORM\Entity(repositoryClass="Loonins\SuiviBundle\Repository\AffectationLoginRepository")
 */
class AffectationLogin
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
     * @ORM\Column(name="debutAffectation", type="datetime")
     */
    private $debutAffectation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finAffectation", type="datetime", nullable=true)
     */
    private $finAffectation;

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
     * @var \Loonins\SuiviBundle\Entity\LoginAnim
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\SuiviBundle\Entity\LoginAnim")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="loginAnim", referencedColumnName="id")
     * })
     */
    private $loginAnim;


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
     * Set Id
     *
     * @param integer $id
     *
     * @return AffectationLogin
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }


   

    /**
     * Set debutAffectation
     *
     * @param \DateTime $debutAffectation
     *
     * @return AffectationLogin
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
     * @return AffectationLogin
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
     * Set employe
     *
     * @param \Loonins\GrhBundle\Entity\GrhEmployes $employe
     *
     * @return AffectationLogin
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
     * Set loginAnim
     *
     * @param \Loonins\SuiviBundle\Entity\LoginAnim $loginAnim
     *
     * @return AffectationLogin
     */
    public function setLoginAnim(\Loonins\SuiviBundle\Entity\LoginAnim $loginAnim = null)
    {
        $this->loginAnim = $loginAnim;

        return $this;
    }

    /**
     * Get loginAnim
     *
     * @return \Loonins\SuiviBundle\Entity\LoginAnim
     */
    public function getLoginAnim()
    {
        return $this->loginAnim;
    }

    public function __toString(){
        return  $this->loginAnim . " / ". $this->employe;
    }
}
