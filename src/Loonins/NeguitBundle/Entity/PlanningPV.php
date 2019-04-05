<?php

namespace Loonins\NeguitBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PlanningPV
 *
 * @ORM\Table(name="planning_p_v")
 * @ORM\Entity(repositoryClass="Loonins\NeguitBundle\Repository\PlanningPVRepository")
 */
class PlanningPV
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
     * @var \Planning
     *
     * @ORM\ManyToOne(targetEntity="Planning")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="planning", referencedColumnName="Id", inversedBy="planification")
     * })
     */
    private $planning;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}

