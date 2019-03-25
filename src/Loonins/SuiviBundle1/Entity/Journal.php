<?php

namespace Loonins\SuiviBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Journal
 *
 * @ORM\Table(name="Journal")
 * @ORM\Entity(repositoryClass="Loonins\SuiviBundle\Entity\JournalRepository")
 */
class Journal
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
     * @var \Loonins\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="proprio", referencedColumnName="id")
     * })
     */
    private $auteur;

    /**
     * @var \Loonins\SuiviBundle\Entity\Stat
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\SuiviBundle\Entity\Stat",cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ligneStat", referencedColumnName="id")
     * })
     
     
     */
    private $ligneStat;

    /**
     * @var string
     *
     * @ORM\Column(name="action", type="string", length=10)
    */
    private $action;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateModif", type="datetime", nullable=true)
     */
    private $dateModif;



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
     * Set action
     *
     * @param string $action
     *
     * @return Journal
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set auteur
     *
     * @param \Loonins\UserBundle\Entity\User $auteur
     *
     * @return Journal
     */
    public function setAuteur(\Loonins\UserBundle\Entity\User $auteur = null)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return \Loonins\UserBundle\Entity\User
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set ligneStat
     *
     * @param \Loonins\SuiviBundle\Entity\Stat $ligneStat
     *
     * @return Journal
     */
    public function setLigneStat(\Loonins\SuiviBundle\Entity\Stat $ligneStat = null)
    {
        $this->ligneStat = $ligneStat;

        return $this;
    }

    /**
     * Get ligneStat
     *
     * @return \Loonins\SuiviBundle\Entity\Stat
     */
    public function getLigneStat()
    {
        return $this->ligneStat;
    }

    /**
     * Set dateModif
     *
     * @param \DateTime $dateModif
     *
     * @return Journal
     */
    public function setDateModif($dateModif)
    {
        $this->dateModif = $dateModif;

        return $this;
    }

    /**
     * Get dateModif
     *
     * @return \DateTime
     */
    public function getDateModif()
    {
        return $this->dateModif;
    }
}
