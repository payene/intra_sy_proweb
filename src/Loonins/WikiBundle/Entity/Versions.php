<?php

namespace Loonins\WikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Versions
 *
 * @ORM\Table(name="versions", indexes={@ORM\Index(name="ver_version", columns={"ver_version"}),@ORM\Index(name="ver_article", columns={"ver_article"}), @ORM\Index(name="ver_creator", columns={"ver_creator"})})
 * @ORM\Entity
 */
class Versions {

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
     * @ORM\Column(name="ver_titre", type="string", length=255, nullable=true)
     */
    private $verTitre;

    /**
     * @var string
     *
     * @ORM\Column(name="ver_content", type="text", nullable=true)
     */
    private $verContent;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ver_date", type="datetime", nullable=true)
     */
    private $verDate;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ver_editable", type="boolean", nullable=true)
     */
    private $verEditable;

    /**
     * @var integer
     *
     * @ORM\Column(name="ver_del", type="integer", nullable=false)
     */
    private $verDel;

      /**
     * @var \Loonins\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ver_creator", referencedColumnName="id")
     * })
     */
    private $verCreator;

    /**
     * @var \Article
     *
     * @ORM\ManyToOne(targetEntity="Article")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ver_article", referencedColumnName="Id")
     * })
     */
    private $verArticle;
    
    /**
     * @var \Versions
     *
     * @ORM\ManyToOne(targetEntity="Version")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="ver_version", referencedColumnName="Id")
     * })
     */
    private $verVersion;
    
     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attachement;

    function __construct() {

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
     * Set verTitre
     *
     * @param string $verTitre
     * @return Versions
     */
    public function setVerTitre($verTitre)
    {
        $this->verTitre = $verTitre;

        return $this;
    }

    /**
     * Get verTitre
     *
     * @return string 
     */
    public function getVerTitre()
    {
        return $this->verTitre;
    }

    /**
     * Set verContent
     *
     * @param string $verContent
     * @return Versions
     */
    public function setVerContent($verContent)
    {
        $this->verContent = $verContent;

        return $this;
    }

    /**
     * Get verContent
     *
     * @return string 
     */
    public function getVerContent()
    {
        return $this->verContent;
    }

    /**
     * Set verDate
     *
     * @param \DateTime $verDate
     * @return Versions
     */
    public function setVerDate($verDate)
    {
        $this->verDate = $verDate;

        return $this;
    }

    /**
     * Get verDate
     *
     * @return \DateTime 
     */
    public function getVerDate()
    {
        return $this->verDate;
    }

    /**
     * Set verEditable
     *
     * @param boolean $verEditable
     * @return Versions
     */
    public function setVerEditable($verEditable)
    {
        $this->verEditable = $verEditable;

        return $this;
    }

    /**
     * Get verEditable
     *
     * @return boolean 
     */
    public function getVerEditable()
    {
        return $this->verEditable;
    }

    /**
     * Set verDel
     *
     * @param integer $verDel
     * @return Versions
     */
    public function setVerDel($verDel)
    {
        $this->verDel = $verDel;

        return $this;
    }

    /**
     * Get verDel
     *
     * @return integer 
     */
    public function getVerDel()
    {
        return $this->verDel;
    }

    /**
     * Set verCreator
     *
     * @param \Loonins\UserBundle\Entity\User $verCreator
     * @return Versions
     */
    public function setVerCreator(\Loonins\UserBundle\Entity\User $verCreator = null)
    {
        $this->verCreator = $verCreator;

        return $this;
    }

    /**
     * Get verCreator
     *
     * @return \Loonins\UserBundle\Entity\User 
     */
    public function getVerCreator()
    {
        return $this->verCreator;
    }

    /**
     * Set verArticle
     *
     * @param \Loonins\WikiBundle\Entity\Article $verArticle
     * @return Versions
     */
    public function setVerArticle(\Loonins\WikiBundle\Entity\Article $verArticle = null)
    {
        $this->verArticle = $verArticle;

        return $this;
    }

    /**
     * Get verArticle
     *
     * @return \Loonins\WikiBundle\Entity\Article 
     */
    public function getVerArticle()
    {
        return $this->verArticle;
    }

    /**
     * Set verVersion
     *
     * @param \Loonins\WikiBundle\Entity\Version $verVersion
     * @return Versions
     */
    public function setVerVersion(\Loonins\WikiBundle\Entity\Version $verVersion = null)
    {
        $this->verVersion = $verVersion;

        return $this;
    }

    /**
     * Get verVersion
     *
     * @return \Loonins\WikiBundle\Entity\Version 
     */
    public function getVerVersion()
    {
        return $this->verVersion;
    }
    
       /**
     * @return Array
     */
    public function restauration(){
        $version = $this->getVerVersion();
        $backup = $version->archivage();
        $version->setVerArticle($this->getVerArticle());
        $version->setVerContent($this->getVerContent());
        $version->setVerCreator($this->getVerCreator());
        $version->setVerDate($this->getVerDate());
        $version->setVerDel($this->getVerDel());
        $version->setVerEditable($this->getVerEditable());
        $version->setVerTitre($this->getVerTitre());
        $version->setAttachement($this->getAttachement());
        return array('archive' =>$backup, 'section'=>$version);
    }

    /**
     * Set attachement
     *
     * @param string $attachement
     * @return Versions
     */
    public function setAttachement($attachement)
    {
        $this->attachement = $attachement;

        return $this;
    }

    /**
     * Get attachement
     *
     * @return string 
     */
    public function getAttachement()
    {
        return $this->attachement;
    }
}
