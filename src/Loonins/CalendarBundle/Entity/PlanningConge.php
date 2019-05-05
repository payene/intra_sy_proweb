<?php

namespace Loonins\CalendarBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanningConge
 *
 * @ORM\Table(name="planning_conge")
 * @ORM\Entity(repositoryClass="Loonins\CalendarBundle\Repository\PlanningCongeRepository")
 */
class PlanningConge
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
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var int
     *
     * @ORM\Column(name="del", type="integer",nullable=true)
     */
    private $del;


    /**
     * @var \Loonins\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="createdBy", referencedColumnName="id")
     * })
     */
    private $createdBy;


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
     * Set source
     *
     * @param string $source
     *
     * @return PlanningConge
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return PlanningConge
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
     * @return PlanningConge
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
     * Set createdBy
     *
     * @param \Loonins\UserBundle\Entity\User $createdBy
     *
     * @return PlanningConge
     */
    public function setCreatedBy(\Loonins\UserBundle\Entity\User $createdBy = null)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy
     *
     * @return \Loonins\UserBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
