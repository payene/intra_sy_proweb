<?php

namespace Loonins\SuiviBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * TypeTable
 *
 * @ORM\Table(name="TypeTable")
 * @ORM\Entity(repositoryClass="Loonins\SuiviBundle\Entity\TypeTableRepository")
 */
class TypeTable
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
     * @var string
     *
     * @ORM\Column(name="typeTable", type="string", length=25)
     */
    private $typeTable;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=25)
     */
    private $code;

    /**
     * @var boolean
     *
     * @ORM\Column(name="msgByConvRequired", type="boolean", nullable=false)
     */
    private $msgByConvRequired = false;
    //getMsgByConvRequired


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
     * Set typeTable
     *
     * @param string $typeTable
     * @return TypeTable
     */
    public function setTypeTable($typeTable)
    {
        $this->typeTable = $typeTable;

        return $this;
    }

    /**
     * Get typeTable
     *
     * @return string 
     */
    public function getTypeTable()
    {
        return $this->typeTable;
    }

    public function __toString(){
        return $this->typeTable;
    }

    

    /**
     * Set msgByConvRequired
     *
     * @param boolean $msgByConvRequired
     *
     * @return TypeTable
     */
    public function setMsgByConvRequired($msgByConvRequired)
    {
        $this->msgByConvRequired = $msgByConvRequired;

        return $this;
    }

    /**
     * Get msgByConvRequired
     *
     * @return boolean
     */
    public function getMsgByConvRequired()
    {
        return $this->msgByConvRequired;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return TypeTable
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }
}
