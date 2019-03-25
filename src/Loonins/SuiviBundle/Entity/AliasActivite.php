<?php

namespace Loonins\SuiviBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AliasActivite
 *
 * @ORM\Table(name="alias_activite")
 * @ORM\Entity(repositoryClass="Loonins\SuiviBundle\Repository\AliasActiviteRepository")
 */
class AliasActivite
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
     * @ORM\Column(name="alias", type="string", length=255, unique=true)
     */
    private $alias;

    /**
     * @var \Loonins\SuiviBundle\Entity\TypeTable
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\SuiviBundle\Entity\TypeTable")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="type", referencedColumnName="id")
     * })
     */
    private $type;

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
     * Set alias
     *
     * @param string $alias
     *
     * @return AliasActivite
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set type
     *
     * @param \Loonins\SuiviBundle\Entity\TypeTable $type
     *
     * @return AliasActivite
     */
    public function setType(\Loonins\SuiviBundle\Entity\TypeTable $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Loonins\SuiviBundle\Entity\TypeTable
     */
    public function getType()
    {
        return $this->type;
    }
}
