<?php

namespace Loonins\WikiBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
/**
 * Rubrique
 *
 * @ORM\Table(name="Rubrique", indexes={@ORM\Index(name="rub_cat", columns={"rub_cat"}), @ORM\Index(name="rub_creator", columns={"rub_creator", "rub_cat"}), @ORM\Index(name="IDX_8FA4097C2506957", columns={"rub_creator"})})
 * @ORM\Entity
 */


class Rubrique {

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
     * @ORM\Column(name="titre", type="string", length=255, nullable=true)
     */
    private $titre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rub_date", type="datetime", nullable=false)
     */
    private $rubDate = '0000-00-00 00:00:00';

     /**     
     * @ORM\OneToMany(targetEntity="Loonins\WikiBundle\Entity\Article", mappedBy="Rubrique")
    */
    private $articles;


    /**
     * @var \Categorie
     *
     * @ORM\ManyToOne(targetEntity="Categorie")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rub_cat", referencedColumnName="Id")
     * })
     */
    private $rubCat;

    /**
     * @var \Loonins\UserBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\UserBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="rub_creator", referencedColumnName="id")
     * })
     */
    private $rubCreator;
    


    function __construct() {
        $this->rubDate = new \DateTime;
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
     * Set titre
     *
     * @param string $titre
     * @return Rubrique
     */
    public function setTitre($titre) {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre() {
        return $this->titre;
    }

    /**
     * Set rubDate
     *
     * @param \DateTime $rubDate
     * @return Rubrique
     */
    public function setRubDate($rubDate) {
        $this->rubDate = $rubDate;

        return $this;
    }

    /**
     * Get rubDate
     *
     * @return \DateTime 
     */
    public function getRubDate() {
        return $this->rubDate;
    }

    /**
     * Set rubCat
     *
     * @param \Loonins\WikiBundle\Entity\Categorie $rubCat
     * @return Rubrique
     */
    public function setRubCat(\Loonins\WikiBundle\Entity\Categorie $rubCat = null) {
        $this->rubCat = $rubCat;

        return $this;
    }

    /**
     * Get rubCat
     *
     * @return \Loonins\WikiBundle\Entity\Categorie 
     */
    public function getRubCat() {
        return $this->rubCat;
    }

    /**
     * Set rubCreator
     *
     * @param \Loonins\UserBundle\Entity\User $rubCreator
     * @return Rubrique
     */
    public function setRubCreator(\Loonins\UserBundle\Entity\User $rubCreator = null) {
        $this->rubCreator = $rubCreator;

        return $this;
    }

    /**
     * Get rubCreator
     *
     * @return \Loonins\UserBundle\Entity\User 
     */
    public function getRubCreator() {
        return $this->rubCreator;
    }

    public function getArticles() {
        return $this->articles;
    }

    public function setArticles(Collection $articles) {
        $this->articles = $articles;
    }
    
    public function addArticle(Article $article){
        $this->getArticles()->add($article);
    }



    /**
     * Remove articles
     *
     * @param \Loonins\WikiBundle\Entity\Article $articles
     */
    public function removeArticle(\Loonins\WikiBundle\Entity\Article $articles)
    {
        $this->articles->removeElement($articles);
    }

    public function __toString() {
        return (string) $this->getTitre().' - '. $this->getRubCat()->getCat();
    }
}