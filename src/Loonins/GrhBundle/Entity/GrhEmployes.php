<?php

namespace Loonins\GrhBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Loonins\GrhBundle\Validator\AntiFlood;
use Loonins\GrhBundle\Validator\AntiFloodded;
use Loonins\GrhBundle\Validator\AntiNull;

/**
 * GrhEmployes
 *
 * @ORM\Table(name="grh_employes", indexes={@ORM\Index(name="departement", columns={"departement"})})
 * @ORM\Entity(repositoryClass="Loonins\GrhBundle\Entity\GrhEmployeRepository")
 * @UniqueEntity(fields="mle", message="Ce matricule n'est pas disponible.") 

 */

class GrhEmployes {

    /**
     * @var integer
     *
     * @ORM\Column(name="Id", type="integer", nullable=false, unique=true)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * @AntiNull()
     */
    private $nom = '';

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @AntiNull()
     */
    private $email = '';

    /**
     * @var string
     *
     * @ORM\Column(name="mle", type="string", length=255, nullable=true)
     * @AntiNull()
     */
    private $mle = '';

    /**
     * @var string
     *
     * @ORM\Column(name="prenoms", type="string", length=255, nullable=false)
     * @AntiNull()
     */
    private $prenoms = '';

    /**
     * @var \BirthDay
     *
     * @ORM\Column(name="dateentree", type="date", nullable=false)
     * @AntiNull()
     * @AntiFloodded()
     */
    private $dateentree = '0000-00-00';

    /**
     * @var \Birthday
     *
     * @ORM\Column(name="datenaiss", type="date", nullable=false)
     * @AntiNull()
     * @AntiFlood()
     */
    private $datenaiss = '0000-00-00';

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateajout", type="date", nullable=false)
     */
    private $dateajout = '0000-00-00';

    /**
     * @var string
     *
     * @ORM\Column(name="lieu_naiss", type="string", length=255, nullable=true)
     */
    private $lieuNaiss;


    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="competence", type="text", length=65535, nullable=true)
     */
    private $competence;

    /**
     * @var string
     *
     * @ORM\Column(name="experience", type="text", length=65535, nullable=true)
     */
    private $experience;
    
    /**
     * @var string
     *
     * @ORM\Column(name="niveau_etude", type="string", length=255, nullable=true)
     */
    private $niveauEtude;


    /**
     * @var string
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    private $commentaire;


    /**
     * @var \GrhDepartement
     *
     * @ORM\ManyToOne(targetEntity="GrhDepartement")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="departement", referencedColumnName="Id",  nullable=false)
     * })
     * @AntiNull()
     */
    private $departement;

    /**
     * @var \Sexe
     *
     * @ORM\ManyToOne(targetEntity="Sexe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sexe", referencedColumnName="id")
     * })
     */
    private $sexe;

    /**
     * @var \Matrimonial
     *
     * @ORM\ManyToOne(targetEntity="Matrimonial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sitMat", referencedColumnName="id")
     * })
     */
    private $sitMat;

    /**
     * @var boolean
     *
     * @ORM\Column(name="assurance", type="boolean", nullable=false)
     */
    private $assurance = '0';

    /**
     * @var boolean
     *
     * @ORM\Column(name="trashed", type="boolean", nullable=false)
     */
    private $trashed = '0';

    /**
    * @ORM\OneToOne(targetEntity="\Loonins\UserBundle\Entity\User", cascade={"persist", "remove"}, inversedBy="employe")
    * @ORM\JoinColumn(name="user", nullable=true, referencedColumnName="id")
    */
    protected $user;




    /**
     * @var ArrayCollection incidents
     * @ORM\OneToMany(targetEntity="\Loonins\IncidentBundle\Entity\GdpIncident", mappedBy="employe")
     * 
     */
    private $incidents;

    function __construct() {
        $this->assurance = false;
        $this->datenaiss = new \DateTime;
        $this->dateentree = new \DateTime;
        $this->dateajout = new \DateTime;
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
     * Set nom
     *
     * @param string $nom
     * @return GrhEmployes
     */
    public function setNom($nom) {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * Set prenoms
     *
     * @param string $prenoms
     * @return GrhEmployes
     */
    public function setPrenoms($prenoms) {
        $this->prenoms = $prenoms;

        return $this;
    }

    /**
     * Get prenoms
     *
     * @return string 
     */
    public function getPrenoms() {
        return $this->prenoms;
    }

    /**
     * Set datenaiss
     *
     * @param \DateTime $datenaiss
     * @return GrhEmployes
     */
    public function setDatenaiss($datenaiss) {
        $this->datenaiss = $datenaiss;

        return $this;
    }

    /**
     * Get datenaiss
     *
     * @return \DateTime 
     */
    public function getDatenaiss() {
        return $this->datenaiss;
    }

    /**
     * Set lieuNaiss
     *
     * @param string $lieuNaiss
     * @return GrhEmployes
     */
    public function setLieuNaiss($lieuNaiss) {
        $this->lieuNaiss = $lieuNaiss;

        return $this;
    }

    /**
     * Get lieuNaiss
     *
     * @return string 
     */
    public function getLieuNaiss() {
        return $this->lieuNaiss;
    }

    /**
     * Set sexe
     *
     * @param string $sexe
     * @return GrhEmployes
     */
    public function setSexe($sexe) {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * Get sexe
     *
     * @return string 
     */
    public function getSexe() {
        return $this->sexe;
    }

    /**
     * Set sitMat
     *
     * @param string $sitMat
     * @return GrhEmployes
     */
    public function setSitMat($sitMat) {
        $this->sitMat = $sitMat;

        return $this;
    }

    /**
     * Get sitMat
     *
     * @return \Loonins\GrhBundle\Entity\Matrimonial
     */
    public function getSitMat() {
        return $this->sitMat;
    }

    /**
     * Set departement
     *
     * @param \Loonins\GrhBundle\Entity\GrhDepartement $departement
     * @return GrhEmployes
     */
    public function setDepartement(\Loonins\GrhBundle\Entity\GrhDepartement $departement = null) {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return \Loonins\GrhBundle\Entity\GrhDepartement 
     */
    public function getDepartement() {
        return $this->departement;
    }

    public function __toString() {
        return "". $this->getNom() . ' ' . $this->getPrenoms();
    }

    /**
     * Set mle
     *
     * @param string $mle
     * @return GrhEmployes
     */
    public function setMle($mle) {
        $this->mle = $mle;

        return $this;
    }

    /**
     * Get mle
     *
     * @return string 
     */
    public function getMle() {
        return $this->mle;
    }

    /**
     * Set dateajout
     *
     * @param \DateTime $dateajout
     * @return GrhEmployes
     */
    public function setDateajout($dateajout) {
        $this->dateajout = $dateajout;

        return $this;
    }

    /**
     * Get dateajout
     *
     * @return \DateTime 
     */
    public function getDateajout() {
        return $this->dateajout;
    }

    public function getListeContratsEncours() {
//        $em = $this->get('doctrine.orm.entity_manager');
//        $repository = $em->getRepository('LooninsGrhBundle:GrhEmployes');
//        $em = new  GrhEmployeRepository();
        return GrhEmployeRepository::floodContrat($this);
    }

//    public function __toString(){
//        
//    }

    /**
     * Set dateentree
     *
     * @param \DateTime $dateentree
     * @return GrhEmployes
     */
    public function setDateentree($dateentree) {
        $this->dateentree = $dateentree;

        return $this;
    }

    /**
     * Get dateentree
     *
     * @return \DateTime 
     */
    public function getDateentree() {
        return $this->dateentree;
    }

    /**
     * Set assurance
     *
     * @param boolean $assurance
     * @return GrhEmployes
     */
    public function setAssurance($assurance) {
        $this->assurance = $assurance;

        return $this;
    }

    /**
     * Get assurance
     *
     * @return boolean 
     */
    public function getAssurance() {
        return $this->assurance;
    }


    /**
     * Set photo
     *
     * @param string $photo
     *
     * @return GrhEmployes
     */
    public function setPhoto($photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return string
     */
    public function getPhoto()
    {
        return $this->photo;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return GrhEmployes
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return GrhEmployes
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set trashed
     *
     * @param boolean $trashed
     *
     * @return GrhEmployes
     */
    public function setTrashed($trashed)
    {
        $this->trashed = $trashed;

        return $this;
    }

    /**
     * Get trashed
     *
     * @return boolean
     */
    public function getTrashed()
    {
        return $this->trashed;
    }

    /**
     * Add incident
     *
     * @param \Loonins\IncidentBundle\Entity\GdpIncident $incident
     *
     * @return GrhEmployes
     */
    public function addIncident(\Loonins\IncidentBundle\Entity\GdpIncident $incident)
    {
        $this->incidents[] = $incident;

        return $this;
    }

    /**
     * Remove incident
     *
     * @param \Loonins\IncidentBundle\Entity\GdpIncident $incident
     */
    public function removeIncident(\Loonins\IncidentBundle\Entity\GdpIncident $incident)
    {
        $this->incidents->removeElement($incident);
    }

    /**
     * Get incidents
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIncidents()
    {
        return $this->incidents;
    }

    public function getAbsences()
    {
        $absences = [];
        foreach ($this->incidents as $incident) {
            if( $incident->getCategorie()->getId() == 2 )
                $absences[] = $incident;
        }
        return $absences;
    }

    /**
     * Set competence
     *
     * @param string $competence
     *
     * @return GrhEmployes
     */
    public function setCompetence($competence)
    {
        $this->competence = $competence;

        return $this;
    }

    /**
     * Get competence
     *
     * @return string
     */
    public function getCompetence()
    {
        return $this->competence;
    }

    /**
     * Set experience
     *
     * @param string $experience
     *
     * @return GrhEmployes
     */
    public function setExperience($experience)
    {
        $this->experience = $experience;

        return $this;
    }

    /**
     * Get experience
     *
     * @return string
     */
    public function getExperience()
    {
        return $this->experience;
    }

    /**
     * Set niveauEtude
     *
     * @param string $niveauEtude
     *
     * @return GrhEmployes
     */
    public function setNiveauEtude($niveauEtude)
    {
        $this->niveauEtude = $niveauEtude;

        return $this;
    }

    /**
     * Get niveauEtude
     *
     * @return string
     */
    public function getNiveauEtude()
    {
        return $this->niveauEtude;
    }

    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return GrhEmployes
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
     * Set user
     *
     * @param \Loonins\UserBundle\Entity\User $user
     *
     * @return GrhEmployes
     */
    public function setUser(\Loonins\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Loonins\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }



}
