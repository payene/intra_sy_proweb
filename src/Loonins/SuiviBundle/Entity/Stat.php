<?php

namespace Loonins\SuiviBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stat
 *
 * @ORM\Table(name="Stat")
 * @ORM\Entity(repositoryClass="Loonins\SuiviBundle\Entity\StatRepository") * @ORM\HasLifecycleCallbacks() */

class Stat
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
     * @var \Loonins\SuiviBundle\Entity\Animatrice
     *
     * @ORM\ManyToOne(targetEntity="\Loonins\SuiviBundle\Entity\Animatrice")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="animatrice", referencedColumnName="id")
     * })
     */
    private $animatrice;

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
     * @var integer
     *
     * @ORM\Column(name="msgParHeure", type="integer")
     */
    private $msgParHeure;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="msgParConv", type="integer" )
     */
    private $msgParConv =0;

    /**
     * @var integer
     *
     * @ORM\Column(name="retard", type="integer")
     */
    private $retard;

    /**
     * @var integer
     *
     * @ORM\Column(name="total", type="integer")
     */
    private $total;

    /**
     * @var string
     *
     * @ORM\Column(name="programmed", type="string", length=8)
     */
    private $programmed;

    /**
     * @var string
     *
     * @ORM\Column(name="reel", type="string", length=8)
     */
    private $reel;

    /**
     * @var integer
     *
     * @ORM\Column(name="programmedSeconds", type="integer", nullable=true)
     */
    private $programmedSeconds;

   /**
     * @var integer
     *
     * @ORM\Column(name="reelSeconds", type="integer")
     */
    private $reelSeconds;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateStat", type="datetime")
     */
    private $dateStat;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateSaisie", type="datetime")
     */
    private $dateSaisie;

    /**
     * @var integer
     *
     * @ORM\Column(name="del", type="integer")
     */
    private $del;

    /**
     * @var integer
     *
     * @ORM\Column(name="prime", type="integer", nullable=true)
     */
    private $prime =0;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbCaractere", type="integer", nullable=true)
    */
    private $nbCaractere =0;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalRecu", type="integer", nullable=true)
    */
    private $totalRecu =0;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbInscTrait", type="integer", nullable=true)
    */
    private $nbInscTrait =0;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbAbonnes", type="integer", nullable=true)
    */
    private $nbAbonnes =0;

    /**
     * @var integer
     *
     * @ORM\Column(name="heureAbs", type="integer", nullable=true)
    */
    private $heureAbs =0;

    /**
     * @var integer
     *
     * @ORM\Column(name="noteCoach", type="integer", nullable=true)
    */
    private $noteCoach =0;

    /**
     * @var integer
     *
     * @ORM\Column(name="imported", type="integer", nullable=true)
    */
    private $imported =0;
    /**
     * @var text
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
    */
    private $commentaire;

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
     * Set login
     *
     * @param string $login
     * @return Stat
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set msgParHeure
     *
     * @param integer $msgParHeure
     * @return Stat
     */
    public function setMsgParHeure($msgParHeure)
    {
        $this->msgParHeure = $msgParHeure;

        return $this;
    }

    /**
     * Get msgParHeure
     *
     * @return integer 
     */
    public function getMsgParHeure()
    {
        return $this->msgParHeure;
    }

    /**
     * Set total
     *
     * @param integer $total
     * @return Stat
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return integer 
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set programmed
     *
     * @param string $programmed
     * @return Stat
     */
    public function setProgrammed($programmed)
    {
        $this->programmed = $programmed;

        return $this;
    }

    /**
     * Get programmed
     *
     * @return string 
     */
    public function getProgrammed()
    {
        return $this->programmed;
    }

    /**
     * Set reel
     *
     * @param string $reel
     * @return Stat
     */
    public function setReel($reel)
    {
        $this->reel = $reel;

        return $this;
    }

    /**
     * Get reel
     *
     * @return string 
     */
    public function getReel()
    {
        return $this->reel;
    }

    /**
     * Set dateStat
     *
     * @param \DateTime $dateStat
     * @return Stat
     */
    public function setDateStat($dateStat)
    {
        $this->dateStat = $dateStat;

        return $this;
    }

    /**
     * Get dateStat
     *
     * @return \DateTime 
     */
    public function getDateStat()
    {
        return $this->dateStat;
    }

    /**
     * Set animatrice
     *
     * @param \Loonins\SuiviBundle\Entity\Animatrice $animatrice
     * @return Stat
     */
    public function setAnimatrice(\Loonins\SuiviBundle\Entity\Animatrice $animatrice = null)
    {
        $this->animatrice = $animatrice;

        return $this;
    }

    /**
     * Get animatrice
     *
     * @return \Loonins\SuiviBundle\Entity\Animatrice 
     */
    public function getAnimatrice()
    {
        return $this->animatrice;
    }

    /**
     * Set type
     *
     * @param \Loonins\SuiviBundle\Entity\TypeTable $type
     * @return Stat
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

    /**
     * Set dateSaisie
     *
     * @param \DateTime $dateSaisie
     * @return Stat
     */
    public function setDateSaisie($dateSaisie)
    {
        $this->dateSaisie = $dateSaisie;

        return $this;
    }

    /**
     * Get dateSaisie
     *
     * @return \DateTime 
     */
    public function getDateSaisie()
    {
        return $this->dateSaisie;
    }

    /**
     * Set retard
     *
     * @param integer $retard
     * @return Stat
     */
    public function setRetard($retard)
    {
        $this->retard = $retard;

        return $this;
    }

    /**
     * Get retard
     *
     * @return integer 
     */
    public function getRetard()
    {
        return $this->retard;
    }

    /**
     * Set del
     *
     * @param integer $del
     * @return Stat
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

    public function __toStrnig(){
        return "51kjh";
    }

    /**
     * Set programmedSeconds
     *
     * @param integer $programmedSeconds
     *
     * @return Stat
     */
    public function setProgrammedSeconds($programmedSeconds)
    {
        $this->programmedSeconds = $programmedSeconds;

        return $this;
    }

    /**
     * Get programmedSeconds
     *
     * @return integer
     */
    public function getProgrammedSeconds()
    {
        return $this->programmedSeconds;
    }

    /**
     * Set reelSeconds
     *
     * @param integer $reelSeconds
     *
     * @return Stat
     */
    public function setReelSeconds($reelSeconds)
    {
        $this->reelSeconds = $reelSeconds;

        return $this;
    }

    /**
     * Get reelSeconds
     *
     * @return integer
     */
    public function getReelSeconds()
    {
        return $this->reelSeconds;
    }

    /**
     * Set msgParConv
     *
     * @param integer $msgParConv
     *
     * @return Stat
     */
    public function setMsgParConv($msgParConv)
    {
        $this->msgParConv = $msgParConv;

        return $this;
    }

    /**
     * Get msgParConv
     *
     * @return integer
     */
    public function getMsgParConv()
    {
        return $this->msgParConv;
    }

    /**
     * Set prime
     *
     * @param integer $prime
     *
     * @return Stat
     */
    public function setPrime($prime)
    {
        $this->prime = $prime;

        return $this;
    }

    /**
     * Get prime
     *
     * @return integer
     */
    public function getPrime()
    {
        return $this->prime;
    }

    /**
     * Set nbCaractere
     *
     * @param integer $nbCaractere
     *
     * @return Stat
     */
    public function setNbCaractere($nbCaractere)
    {
        $this->nbCaractere = $nbCaractere;

        return $this;
    }

    /**
     * Get nbCaractere
     *
     * @return integer
     */
    public function getNbCaractere()
    {
        return $this->nbCaractere;
    }

    /**
     * Set totalRecu
     *
     * @param integer $totalRecu
     *
     * @return Stat
     */
    public function setTotalRecu($totalRecu)
    {
        $this->totalRecu = $totalRecu;

        return $this;
    }

    /**
     * Get totalRecu
     *
     * @return integer
     */
    public function getTotalRecu()
    {
        return $this->totalRecu;
    }

    /**
     * Set nbInscTrait
     *
     * @param integer $nbInscTrait
     *
     * @return Stat
     */
    public function setNbInscTrait($nbInscTrait)
    {
        $this->nbInscTrait = $nbInscTrait;

        return $this;
    }

    /**
     * Get nbInscTrait
     *
     * @return integer
     */
    public function getNbInscTrait()
    {
        return $this->nbInscTrait;
    }

    /**
     * Set nbAbonnes
     *
     * @param integer $nbAbonnes
     *
     * @return Stat
     */
    public function setNbAbonnes($nbAbonnes)
    {
        $this->nbAbonnes = $nbAbonnes;

        return $this;
    }

    /**
     * Get nbAbonnes
     *
     * @return integer
     */
    public function getNbAbonnes()
    {
        return $this->nbAbonnes;
    }

    /**
     * Set heureAbs
     *
     * @param integer $heureAbs
     *
     * @return Stat
     */
    public function setHeureAbs($heureAbs)
    {
        $this->heureAbs = $heureAbs;

        return $this;
    }

    /**
     * Get heureAbs
     *
     * @return integer
     */
    public function getHeureAbs()
    {
        return $this->heureAbs;
    }

    /**
     * Set noteCoach
     *
     * @param integer $noteCoach
     *
     * @return Stat
     */
    public function setNoteCoach($noteCoach)
    {
        $this->noteCoach = $noteCoach;

        return $this;
    }

    /**
     * Get noteCoach
     *
     * @return integer
     */
    public function getNoteCoach()
    {
        return $this->noteCoach;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return Stat
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }


    /**
     * Set imported
     *
     * @param integer $imported
     *
     * @return Stat
     */
    public function setImported($imported)
    {
        $this->imported = $imported;

        return $this;
    }

    /**
     * Get imported
     *
     * @return integer
     */
    public function getImported()
    {
        return $this->imported;
    }
}
