<?php

namespace Loonins\NeguitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Planning
 *
 * @ORM\Table(name="planning_neguit")
 * @ORM\Entity(repositoryClass="Loonins\NeguitBundle\Repository\PlanningRepository")
 */
class Planning
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
     * @ORM\Column(name="heureDebut", type="string", length=5)
     */
    private $heureDebut;

    /**
     * @var string
     *
     * @ORM\Column(name="heureFin", type="string", length=5)
     */
    private $heureFin;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datePlanning", type="datetime")
     */
    private $datePlanning;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetimetz")
     */
    private $createdAt;

    
    /**
     * @var \Loonins\NeguitBundle\Entity\AffectLoginNeguit
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\NeguitBundle\Entity\AffectLoginNeguit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="login", referencedColumnName="id")
     * })
     */
    private $login;

    /**
     * @var \Loonins\NeguitBundle\Entity\ProfilVirtuel
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\NeguitBundle\Entity\ProfilVirtuel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="fantome", referencedColumnName="id")
     * })
     */
    private $fantome;

    /**
     * @var \Loonins\NeguitBundle\Entity\TypeAnimNeguit
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\NeguitBundle\Entity\TypeAnimNeguit")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type_anim", referencedColumnName="id")
     * })
     */
    private $typeAnim;


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
     * Set heureDebut
     *
     * @param string $heureDebut
     *
     * @return Planning
     */
    public function setHeureDebut($heureDebut)
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    /**
     * Get heureDebut
     *
     * @return string
     */
    public function getHeureDebut()
    {
        return $this->heureDebut;
    }

    /**
     * Set heureFin
     *
     * @param string $heureFin
     *
     * @return Planning
     */
    public function setHeureFin($heureFin)
    {
        $this->heureFin = $heureFin;

        return $this;
    }

    /**
     * Get heureFin
     *
     * @return string
     */
    public function getHeureFin()
    {
        return $this->heureFin;
    }

    /**
     * Set datePlanning
     *
     * @param \DateTime $datePlanning
     *
     * @return Planning
     */
    public function setDatePlanning($datePlanning)
    {
        $this->datePlanning = $datePlanning;

        return $this;
    }

    /**
     * Get datePlanning
     *
     * @return \DateTime
     */
    public function getDatePlanning()
    {
        return $this->datePlanning;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Planning
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
     * Set login
     *
     * @param \Loonins\NeguitBundle\Entity\AffectLoginNeguit $login
     *
     * @return Planning
     */
    public function setLogin(\Loonins\NeguitBundle\Entity\AffectLoginNeguit $login = null)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return \Loonins\NeguitBundle\Entity\AffectLoginNeguit
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set fantome
     *
     * @param \Loonins\NeguitBundle\Entity\ProfilVirtuel $fantome
     *
     * @return Planning
     */
    public function setFantome(\Loonins\NeguitBundle\Entity\ProfilVirtuel $fantome = null)
    {
        $this->fantome = $fantome;

        return $this;
    }

    /**
     * Get fantome
     *
     * @return \Loonins\NeguitBundle\Entity\ProfilVirtuel
     */
    public function getFantome()
    {
        return $this->fantome;
    }

    /**
     * Set typeAnim
     *
     * @param \Loonins\NeguitBundle\Entity\TypeAnimNeguit $typeAnim
     *
     * @return Planning
     */
    public function setTypeAnim(\Loonins\NeguitBundle\Entity\TypeAnimNeguit $typeAnim = null)
    {
        $this->typeAnim = $typeAnim;

        return $this;
    }

    /**
     * Get typeAnim
     *
     * @return \Loonins\NeguitBundle\Entity\TypeAnimNeguit
     */
    public function getTypeAnim()
    {
        return $this->typeAnim;
    }
}
