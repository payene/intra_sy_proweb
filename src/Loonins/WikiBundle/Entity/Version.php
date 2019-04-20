<?php

namespace Loonins\WikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Version
 *
 * @ORM\Table(name="version", indexes={@ORM\Index(name="ver_article", columns={"ver_article"}), @ORM\Index(name="ver_creator", columns={"ver_creator"})})
 * @ORM\Entity * @ORM\HasLifecycleCallbacks() */
class Version {

    /**     * @ORM\PreUpdate */
    public function updateDate() {
        $this->setVerDate(new \Datetime);
        //$this->setAttachement($this->getAttachement());
    }

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
     * @ORM\Column(name="ver_date", type="datetime", nullable=false)
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
     * @ORM\OneToMany(targetEntity="Loonins\WikiBundle\Entity\Versions", mappedBy="Version", cascade={"persist","remove"})
     */
    private $versions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attachement;

    function __construct() {
        $this->verDate = new \DateTime;
        $this->verEditable = true;
        $this->verDel = 0;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set verTitre
     *
     * @param string $verTitre
     * @return Version
     */
    public function setVerTitre($verTitre) {
        $this->verTitre = $verTitre;

        return $this;
    }

    /**
     * Get verTitre
     *
     * @return string 
     */
    public function getVerTitre() {
        return $this->verTitre;
    }

    /**
     * Set verContent
     *
     * @param string $verContent
     * @return Version
     */
    public function setVerContent($verContent) {
        $this->verContent = $verContent;

        return $this;
    }

    /**
     * Get verContent
     *
     * @return string 
     */
    public function getVerContent() {
        return $this->verContent;
    }

    /**
     * Set verDate
     *
     * @param \DateTime $verDate
     * @return Version
     */
    public function setVerDate($verDate) {
        $this->verDate = $verDate;

        return $this;
    }

    /**
     * Get verDate
     *
     * @return \DateTime 
     */
    public function getVerDate() {
        return $this->verDate;
    }

    /**
     * Set verEditable
     *
     * @param boolean $verEditable
     * @return Version
     */
    public function setVerEditable($verEditable) {
        $this->verEditable = $verEditable;

        return $this;
    }

    /**
     * Get verEditable
     *
     * @return boolean 
     */
    public function getVerEditable() {
        return $this->verEditable;
    }

    /**
     * Set verDel
     *
     * @param integer $verDel
     * @return Version
     */
    public function setVerDel($verDel) {
        $this->verDel = $verDel;

        return $this;
    }

    /**
     * Get verDel
     *
     * @return integer 
     */
    public function getVerDel() {
        return $this->verDel;
    }

    /**
     * Set verCreator
     *
     * @param \Loonins\UserBundle\Entity\User $verCreator
     * @return Version
     */
    public function setVerCreator(\Loonins\UserBundle\Entity\User $verCreator = null) {
        $this->verCreator = $verCreator;

        return $this;
    }

    /**
     * Get verCreator
     *
     * @return \Loonins\UserBundle\Entity\User 
     */
    public function getVerCreator() {
        return $this->verCreator;
    }

    /**
     * Set verArticle
     *
     * @param \Loonins\WikiBundle\Entity\Article $verArticle
     * @return Version
     */
    public function setVerArticle(\Loonins\WikiBundle\Entity\Article $verArticle = null) {
        $this->verArticle = $verArticle;

        return $this;
    }

    /**
     * Get verArticle
     *
     * @return \Loonins\WikiBundle\Entity\Article 
     */
    public function getVerArticle() {
        return $this->verArticle;
    }

    /**
     * Get Versions
     *
     * @return \Loonins\WikiBundle\Entity\Versions 
     */
    public function archivage() {
        $archive = new \Loonins\WikiBundle\Entity\Versions();
        $archive->setVerArticle($this->getVerArticle());
        $archive->setVerContent($this->getVerContent());
        $archive->setVerCreator($this->getVerCreator());
        $archive->setVerDate($this->getVerDate());
        $archive->setVerDel($this->getVerDel());
        $archive->setVerEditable($this->getVerEditable());
        $archive->setVerTitre($this->getVerTitre());
        $archive->setAttachement($this->getAttachement());
        $archive->setVerVersion($this);
        return $archive;
    }

    /**
     * Add versions
     *
     * @param \Loonins\WikiBundle\Entity\Versions $versions
     * @return Version
     */
    public function addVersion(\Loonins\WikiBundle\Entity\Versions $versions) {
        $this->versions[] = $versions;

        return $this;
    }

    /**
     * Remove versions
     *
     * @param \Loonins\WikiBundle\Entity\Versions $versions
     */
    public function removeVersion(\Loonins\WikiBundle\Entity\Versions $versions) {
        $this->versions->removeElement($versions);
    }

    /**
     * Get versions
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getVersions() {
        return $this->versions;
    }

    /**
     * Set attachement
     *
     * @param string $attachement
     * @return Version
     */
    public function setAttachement($attachement) {
        $this->attachement = $attachement;

        return $this;
    }

    /**
     * Get attachement
     *
     * @return string 
     */
    public function getAttachement() {
        return $this->attachement;
    }

}
