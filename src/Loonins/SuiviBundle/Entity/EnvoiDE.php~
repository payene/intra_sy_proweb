<?php

namespace Loonins\SuiviBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EnvoiDE
 *
 * @ORM\Table(name="envoi_d_e")
 * @ORM\Entity(repositoryClass="Loonins\SuiviBundle\Repository\EnvoiDERepository")
 */
class EnvoiDE
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
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var \Loonins\SuiviBundle\Entity\Stat
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\SuiviBundle\Entity\Stat")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="stat", referencedColumnName="id")
     * })
     */
    private $stat;

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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return EnvoiDE
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set stat
     *
     * @param \Loonins\SuiviBundle\Entity\Stat $stat
     *
     * @return EnvoiDE
     */
    public function setStat(\Loonins\SuiviBundle\Entity\Stat $stat = null)
    {
        $this->stat = $stat;

        return $this;
    }

    /**
     * Get stat
     *
     * @return \Loonins\SuiviBundle\Entity\Stat
     */
    public function getStat()
    {
        return $this->stat;
    }
}
