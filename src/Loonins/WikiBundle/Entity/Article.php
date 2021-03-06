<?php

namespace Loonins\WikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table(name="article", indexes={@ORM\Index(name="art_creator", columns={"art_creator"}), @ORM\Index(name="art_rub", columns={"art_rub"})})
 * @ORM\Entity
 */
class Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="art_titre", type="string", length=255, nullable=true)
     */
    private $artTitre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="art_date_create", type="datetime", nullable=true)
     */
    private $artDateCreate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="art_editable", type="boolean", nullable=true)
     */
    private $artEditable;

    /**
     * @var integer
     *
     * @ORM\Column(name="art_del", type="integer", nullable=false)
     */
    private $artDel = '0';

    /**
     * @var \Loonins\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="art_creator", referencedColumnName="id")
     * })
     */
    private $artCreator;

    /**
     * @var \Rubrique
     *
     * @ORM\ManyToOne(targetEntity="Rubrique")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="art_rub", referencedColumnName="Id")
     * })
     */
    private $artRub;
    
    
    
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetime", nullable=false)
     */
    private $updateDate;

    
    function __construct() {
        $this->artDateCreate = new \DateTime;
        $this->updateDate = new \DateTime;
    }

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
     * Set artTitre
     *
     * @param string $artTitre
     * @return Article
     */
    public function setArtTitre($artTitre)
    {
        $this->artTitre = $artTitre;

        return $this;
    }

    /**
     * Get artTitre
     *
     * @return string 
     */
    public function getArtTitre()
    {
        return $this->artTitre;
    }

    /**
     * Set artDateCreate
     *
     * @param \DateTime $artDateCreate
     * @return Article
     */
    public function setArtDateCreate($artDateCreate)
    {
        $this->artDateCreate = $artDateCreate;

        return $this;
    }

    /**
     * Get artDateCreate
     *
     * @return \DateTime 
     */
    public function getArtDateCreate()
    {
        return $this->artDateCreate;
    }

    /**
     * Set artEditable
     *
     * @param boolean $artEditable
     * @return Article
     */
    public function setArtEditable($artEditable)
    {
        $this->artEditable = $artEditable;

        return $this;
    }

    /**
     * Get artEditable
     *
     * @return boolean 
     */
    public function getArtEditable()
    {
        return $this->artEditable;
    }

    /**
     * Set artDel
     *
     * @param integer $artDel
     * @return Article
     */
    public function setArtDel($artDel)
    {
        $this->artDel = $artDel;

        return $this;
    }

    /**
     * Get artDel
     *
     * @return integer 
     */
    public function getArtDel()
    {
        return $this->artDel;
    }

    /**
     * Set artCreator
     *
     * @param \Loonins\UserBundle\Entity\User $artCreator
     * @return Article
     */
    public function setArtCreator(\Loonins\UserBundle\Entity\User $artCreator = null)
    {
        $this->artCreator = $artCreator;

        return $this;
    }

    /**
     * Get artCreator
     *
     * @return \Loonins\UserBundle\Entity\User 
     */
    public function getArtCreator()
    {
        return $this->artCreator;
    }

    /**
     * Set artRub
     *
     * @param \Loonins\WikiBundle\Entity\Rubrique $artRub
     * @return Article
     */
    public function setArtRub(\Loonins\WikiBundle\Entity\Rubrique $artRub = null)
    {
        $this->artRub = $artRub;

        return $this;
    }

    /**
     * Get artRub
     *
     * @return \Loonins\WikiBundle\Entity\Rubrique 
     */
    public function getArtRub()
    {
        return $this->artRub;
    }
//    
//    public function getListRub()
//    {
//        return $this->artRub;
//    }
    
    public function __toString() {
        return (string) $this->getArtTitre();
    }
  

    /**
     * Set updateDate
     *
     * @param \DateTime $updateDate
     * @return Article
     */
    public function setUpdateDate($updateDate)
    {
        $this->updateDate = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return \DateTime 
     */
    public function getUpdateDate()
    {
        return $this->updateDate;
    }
}
