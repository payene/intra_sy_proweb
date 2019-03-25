<?php

namespace Loonins\GrhBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\GrhBundle\Entity\GrhContrats;
use Loonins\GrhBundle\Entity\GrhEmployes;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Loonins\GrhBundle\Form\GrhContratsType;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Response;

/**9
 * GrhContrats controller.
 *
 * @Route("/grhcontrats")
 */
class GrhContratsController extends Controller {

    /**
     * Lists all GrhContrats entities.
     *
     * @Route("/grhcontrats/employe/all/contrats/{employe}", name="grhcontrats_from_employe")
     * @Method("GET")
     * @Template()
     */
    public function employeAction(GrhEmployes $employe) {
        //dump($employe);
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder()
                ->select('c')
                ->from('LooninsGrhBundle:GrhContrats', 'c')
                ->join('c.employe', 'e')
                ->where('e.id = :emp')
                ->andWhere('e.trashed = 0')
                ->setParameter('emp', $employe)
                ->orderBy('c.debut', 'DESC')
                ;
        $entities = $query->getQuery()->getResult();

        return $this->render('LooninsGrhBundle:GrhContrats:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Lists all GrhContrats entities.
     *
     * @Route("/", name="grhcontrats")
     * @Method("GET")
     * @Template()
     */
    public function indexAction($page = null) {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder()
                ->select('c')
                ->from('LooninsGrhBundle:GrhContrats', 'c')
                ->join('c.employe', 'e')
                ->where('c.status = 1')
                ->andWhere('e.trashed = 0')
                ->orwhere('c.status = 4')
                ->orderBy('e.prenoms', 'ASC')
                ->orderBy('e.nom', 'ASC');
        $entities = $query->getQuery()->getResult();
        
        
        $newFormBuilder = $this->createFormBuilder();

        $newFormBuilder
                ->add('comp', ChoiceType::class, array( 'choices' => array(
                                                                            '<' => 'Inf', 
                                                                            '>' => 'Supp', 
                                                                            '<=' => 'InfE', 
                                                                            '>=' => 'SuppE', 
                                                                            '=' => 'E'),))
                ->add('nb', ChoiceType::class, array( 'choices' => array(
                                                                            '1 semaine' => '1', 
                                                                            '2 semaines' => '2', 
                                                                            '1 mois' => '4', 
                                                                            '3 mois' => '12'),))
                ->add('submit', SubmitType::class, array('label'=>'rechercher'))
                ;
        $newForm = $newFormBuilder->getForm();
        
        $typesContrat = $em->getRepository("LooninsGrhBundle:GrhType")->findAll();
        //Le nombre de demandes d'explication
        $NbAvert = [];
        $employes = $em->getRepository('LooninsGrhBundle:GrhEmployes')->findAll();
        foreach ($employes as $employe) {
            //dump($this->getNbrAvert($employe->getId())); exit;
            $NbAvert[$employe->getId()] = ($this->getNbrAvert($employe->getId()))[0]["nbrAvert"];
        }

        //les employés n'ayant jamais eu de contrat
        $empN = $em->createQuery('select e'
                . ' from LooninsGrhBundle:GrhEmployes e'
                . ' where e.id not in('
                . ' select m.id'
                . ' from LooninsGrhBundle:GrhContrats c'
                . ' join c.employe m'
                . ')'
                . ' and e.trashed = 0'
                )->getResult();
        
        return ['entities' => $entities, //,
                'typesContrat' => $typesContrat,
                'empN' => $empN,
                'searchForm' => $newForm->createView(),
                'NbAvert' => $NbAvert,
        ];
    }

    function getNbrAvert($id)
    {
        $em = $this->getDoctrine()->getManager();
        $values = $em->createQuery('select count(f.id) as nbrAvert'
                . ' from LooninsGrhBundle:GrhFicheSalarie f'
                . ' join f.employe e'
                . ' where e.id = :id'
                )
                ->setParameter('id', $id)
                ->getScalarResult();

        return $values;
    }

    public function getContrats($nombreParPage, $page) {
        $em = $this->getDoctrine()->getManager();
        if ($page < 1) {
            //throw new \InvalidArgumentException('L\'argument $page ne peut être inférieur à 1 (valeur : "' . $page . '").');
            $page = 1;
        }
        $query = $em->createQueryBuilder()
                ->select('c')
                ->from('LooninsGrhBundle:GrhContrats', 'c')
                ->getQuery();
        $query->setFirstResult(($page - 1) * $nombreParPage)
                ->setMaxResults($nombreParPage);
        return new Paginator($query);
    }
    
    /**
     *
     * @Route("/alert/mail/contrat/delai/{comp}/{nb}", name="grhcontrats_search")
     * @Template("LooninsGrhBundle:GrhContrats:search.html.twig")
     */    
    public function getContratsByAction($comp, $nb) {
        $em = $this->getDoctrine()->getManager();
        $signe="=";

        $today = new \DateTime( date('Y-m-d') );

        $nbjour = $nb;
        $di = new \DateInterval('P'.$nbjour.'D');
        
        $compareDate= NULL;
        $compareDate =  $today->add($di);
        
        $dans = "";
        
        switch ($comp){
            case 'Supp': $signe=">";    $dans = "plus de";    break;
            case 'SuppE': $signe=">=";  $dans = "plus de";    break;
            case 'Inf': $signe="<";     $dans = "moins de";    break;
            case 'InfE': $signe="<=";   $dans = "moins de";    break;
            case 'E': $signe="=";       $dans = "exactement";    break;
        }
        

        $today = new \DateTime( date('Y-m-d') );


        $contratsQuery = $em->createQuery("select c"
                . " from LooninsGrhBundle:GrhContrats c"
                . " join c.employe e"
                . " where c.finReel ".$signe." :comp"
                . " and c.finReel >= :today"
                . " and e.trashed = 0")
                ->setParameter('comp', $compareDate)
                ->setParameter('today', new \DateTime());
        
        //$contrats = $contratsQuery->getScalarResult();
        $contrats = $contratsQuery->getResult();
        
        //envoi du mail
        $nombre = count($contrats);
        $mail = "<h2>".$nombre. " contrat(s) prendront fin dans ". $dans. " ".$nbjour. " jours"."</h2>";
        foreach ($contrats as $contrat) {
            //dump($contrat->getFinReel());    die('+++');
            $employe = $em->getRepository('LooninsGrhBundle:GrhEmployes')->find($contrat->getEmploye()->getId());
            $mail .= "<br/> " . $employe->getNom()." ".$employe->getPrenoms() . " le " .$contrat->getFinReel()->format('d/m/Y')."<br/>";
        }

        if( $nombre > 0 ){

            $grhmails = $em->getRepository("LooninsGrhBundle:GrhMail")->findAll();
            $resp_array=[];
            foreach ($grhmails as $grhmail) {
                $resp_array[] = $grhmail->getEmail();                
            }

                $this->payeneSfMailer( $resp_array , 'test@test.com', $today->format('d M Y')." : ".'Etat des contrats', $mail);
        }
        


        $response = new Response(json_encode($contrats));
        $response->headers->set('Content-Type', 'application/json');

        return $response;


        /*$response = new JsonResponse();
        return $response->setData( $contrats );*/
    }
    
    /**
     *
     * @Route("/contrat/recherche/notif", name="grhcontrats_search")
     * @Template("LooninsGrhBundle:GrhContrats:search.html.twig")
     */    
    public function trouvContratByAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        
        $newFormBuilder = $this->createFormBuilder();

        $newFormBuilder
                ->add('comp', ChoiceType::class, array( 'choices' => array(
                                                                            '<' => 'Inf', 
                                                                            '>' => 'Supp', 
                                                                            '<=' => 'InfE', 
                                                                            '>=' => 'SuppE', 
                                                                            '=' => 'E'),))
                ->add('nb', ChoiceType::class, array( 'choices' => array(
                                                                            '1 semaine' => '1', 
                                                                            '2 semaines' => '2', 
                                                                            '1 mois' => '4', 
                                                                            '3 mois' => '12'),))
                ->add('submit', SubmitType::class, array('label'=>'rechercher'))
                ;
        $newForm = $newFormBuilder->getForm();
        $newForm->handleRequest($request);
        $comp = "Inf";
        $nb = 1;
        
        if($newForm->isValid()){
            $comp = $newForm['comp']->getData();
            $nb = $newForm['nb']->getData();
            //dump($comp); exit;
        }
        
        $signe="=";
        $today = new \DateTime();
        $nbjour = $nb*7;
        $di = new \DateInterval('P'.$nbjour.'D');
        $compareDate= NULL;
        $compareDate =  $today->add($di);
        
        switch ($comp){
            case 'Supp': $signe=">";       break;
            case 'SuppE': $signe=">=";     break;
            case 'Inf': $signe="<";        break;
            case 'InfE': $signe="<=";      break;
            case 'E': $signe="=";          break;
        }
        $today = new \DateTime();
        $contratsQuery = $em->createQuery("select c"
                . " from LooninsGrhBundle:GrhContrats c"
                . " join c.employe e"
                . " where c.finReel ".$signe." :comp"
                . " and e.trashed = 0"
                . " and c.finReel >= :today")
                ->setParameter('comp', $compareDate)
                ->setParameter('today', new \DateTime());
        
        $contrats = $contratsQuery->getResult();
        
        return $this->render('LooninsGrhBundle:GrhContrats:search.html.twig', array('entities' => $contrats,
            'searchForm' => $newForm->createView()));
    }

    /**
     * Creates a new GrhContrats entity.
     *
     * @Route("/", name="grhcontrats_create")
     * @Method("POST")
     * @Template("LooninsGrhBundle:GrhContrats:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new GrhContrats();
        $entity->setDate(new \DateTime());
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
//        $fin = $entity->getFinReel();
        $debut = $entity->getDebut();
        $type = $entity->getType();
        $duree = $type->getDuree();
//        dump($debut < $fin);
        $db = $debut->format("Y-m-d");
//        $fn = $fin->format("Y-m-d");
        $entity->setDate(new \DateTime());
        $fn = date("Y-m-d", strtotime("+$duree months", strtotime($db)));
        $tab = explode("-", $fn);
        $dfn = new \DateTime;
        $dfn->setDate($tab[0], $tab[1], $tab[2]);
        $dfn->setTime("00", "00", "00");
        $entity->setFinReel($dfn);
        $entity->setStatus(1);

        $employe = $entity->getEmploye();

        $today = date("Y-m-d", time());

        $query = $em->createQueryBuilder()
                ->select('c')
                ->from('LooninsGrhBundle:GrhContrats', 'c')
                ->where('c.status != 4')
                ->andwhere('c.status != 5')
                ->andwhere('c.status != 3')
                ->andwhere('c.status != 2')
                ->andwhere('c.employe = :id')
                ->setParameter('id', $employe->getId());
        //dump($query->getQuery());
        $encours = $query->getQuery()->getResult();
        $no_doublon = 0;
        foreach ($encours as $contrat) {
            if ($contrat->getFinReel()->format("Y-m-d") >= $today) {
                $no_doublon++;
            }
        }
        if ($form->isValid() && $no_doublon == 0) {

            $em->persist($entity);
            $query = $em->createQueryBuilder()
                    ->select('c')
                    ->from('LooninsGrhBundle:GrhContrats', 'c')
                    ->where('c.status = 4')
                    ->andwhere('c.employe = :id')
                    ->setParameter('id', $employe->getId());
            $expired = $query->getQuery()->getResult();

//            $expired = $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->findBy(['status' => 4, 'employe' => $entity->getEmploye()->getId()]);
//            var_dump($expired);
//            die('');
            foreach ($expired as $expire) {
                if (!empty($expire)) {
                    $expire->setStatus(5);
                }

                $em->merge($expire);
            }
            
            $this->payeneSfMailer([$employe->getEmail()], 'test@test.com', 'Renoulement de votre contrat', 'Votre contrat a été renouvelé');
            
            $em->flush();
            return $this->redirect($this->generateUrl('grhcontrats_show', array('id' => $entity->getId())));
        }
        $msg_doublon = ($no_doublon > 0) ? "Vous ne pouvez pas assigner un double contrat à cet employé." : "";
//die('No valid');
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'err_doublon' => $msg_doublon,
        );
    }

    /**
     * Creates a form to create a GrhContrats entity.
     *
     * @param GrhContrats $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(GrhContrats $entity) {
        $form = $this->createForm('Loonins\GrhBundle\Form\GrhContratsType', $entity, array(
            'action' => $this->generateUrl('grhcontrats_create'),
            'method' => 'POST',
        ));

        $form->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Displays a form to create a new GrhContrats entity.
     *
     * @Route("/new/{employe}", name="grhcontrats_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($employe) {
        $entity = new GrhContrats();
        $em = $this->getDoctrine()->getManager();
        if( $employe != '' )
            $entity->setEmploye($em->getRepository('LooninsGrhBundle:GrhEmployes')->find($employe) );
        $form = $this->createCreateForm($entity);
        
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'err_doublon' => "",
        );
    }

    /**
     * Displays a form to create a new GrhContrats entity.
     *
     * @Route("/renew/{employee}", name="grh_renouvellement")
     * @Method("GET")
     * @Template()
     */
    public function renewAction($employee) {
        $em = $this->getDoctrine()->getManager();
        $entity = new GrhContrats();
        $employe = $em->getRepository('LooninsGrhBundle:GrhEmployes')->find($employee);
        $entity->setEmploye($employe);
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
            'err_doublon' => "",
        );
    }

    /**
     * Finds and displays a GrhContrats entity.
     *
     * @Route("/{id}", name="grhcontrats_show")
     * @Method({"GET", "POST"})
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find($id);

        $debut = $entity->getDebut();
        $type = $entity->getType();
//        $duree = $type->getDuree();
////        var_dump($debut < $fin);
//        $db = $debut->format("d-m-Y");
//        $fn = $fin->format("Y-m-d");
//        $fn = date("d-m-Y", strtotime("+$duree months", strtotime($db)));
        $fn = $entity->getFinReel()->format("d-m-Y");

        if (!$entity) {
            throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'finPrevu' => $fn,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing GrhContrats entity.
     *
     * @Route("/{id}/edit", name="grhcontrats_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a GrhContrats entity.
     *
     * @param GrhContrats $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(GrhContrats $entity) {
        $form = $this->createForm('Loonins\GrhBundle\Form\GrhContratsType', $entity, array(
            'action' => $this->generateUrl('grhcontrats_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer les modifications'));

        return $form;
    }

    /**
     * Edits an existing GrhContrats entity.
     *
     * @Route("/{id}", name="grhcontrats_update")
     * @Method("PUT")
     * @Template("LooninsGrhBundle:GrhContrats:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find($id);
        //var_dump($entity);
        if (!$entity) {
            throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $debut = $entity->getDebut();
            $type = $entity->getType();
            $duree = $type->getDuree();
            $db = $debut->format("Y-m-d");
            if ($duree > 0) {
                $fn = date("Y-m-d", strtotime("+$duree months", strtotime($db)));

                $tab = explode("-", $fn);
                $dfn = new \DateTime;
                $dfn->setDate($tab[0], $tab[1], $tab[2]);
                $dfn->setTime("00", "00", "00");
                $entity->setFinReel($dfn);
                $today = new \DateTime;
                $today->setTime("00", "00", "00");
                if ($dfn < $today) {
                    $entity->setStatus(4);
                } else {
//                    die('WAWAWAW ');
                }
            } else {
                $entity->setFinReel(null);
            }

            $em->flush();
            return $this->redirect($this->generateUrl('grhcontrats_show', array('id' => $id)));
        }
//        var_dump($entity);
//        die('');
        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a GrhContrats entity.
     *
     * @Route("/{id}", name="grhcontrats_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
            }

//            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('grhcontrats'));
    }

    /**
     * Creates a form to delete a GrhContrats entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('grhcontrats_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Supprimer'))
                        ->getForm()
        ;
    }

    /**
     *
     * @Route("/", name="grhcontrats_dispo")
     * @Method("GET")
     * @Template()
     */
    public function dispoAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
        }
        $status = $entity->getStatus();
        if ($status == 2) {
            $entity->setStatus(1);
        }
        if ($status == 1) {
            $entity->setStatus(2);
        }

        $em->flush($entity);
        return $this->redirect($this->generateUrl('grhcontrats_show', array('id' => $id)));
    }

    /**
     *
     * @Route("/", name="grhcontrats_rupture")
     * @Method("GET")
     * @Template()
     */
    public function ruptureAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
        }
        $entity->setStatus(3);
        $entity->setFinReel(new \DateTime); //(3);
        $em->flush($entity);
        return $this->redirect($this->generateUrl('grhcontrats_show', array('id' => $id)));
    }

    /**
     *
     * @Route("/", name="grhparams")
     * @Method("GET")
     * @Template()
     */
    public function paramsAction() {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LooninsGrhBundle:GrhContrats')->find(2);
        if (!$entity) {
            throw $this->createNotFoundException('L\'entité   GrhContrats  n\'existe  pas ou plus .');
        }
        $entity->setStatus(3);
        $em->flush($entity);
//        return $this->redirect($this->generateUrl('grhparams'));
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Lists all GrhContrats alerts.
     *
     * @Route("/", name="grh_terminate_contrat")
     * @Method("GET")
     * @Template()
     */
    public function terminateAction() {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder();
      

        $rtn = $this->regularisationDateFin($query);
        exit();
        return $rtn;
        // $this->payeneSfMailer($receivers, $senderMail, "PSfMailer TEST", '$messag' . date('d-m-Y H:i;s', time()) . '');
    }

    public function getAlertes($query, $intv) {
        return $query
                        ->select("c")
                        ->from('LooninsGrhBundle:GrhContrats', 'c')
                        ->where('c.finReel = :intv_3')
                        ->setParameter('intv_3', "$intv 00:00:00")
        ;
    }

    public function regularisationDateFin($query) {
        $today = date('Y-m-d', time());
        $query
                ->select("c")
                ->from('LooninsGrhBundle:GrhContrats', 'c')
                ->join('c.type', 't')
                ->where('c.finReel < :today')
                ->andwhere('c.status < 3')
                ->andwhere('t.duree > 0')
                ->setParameter('today', "$today 00:00:00")
        ;
        $finSup = $query->getQuery()->getResult();
        if (count($finSup) >= 1):
            $em = $this->getDoctrine()->getManager();
            foreach ($finSup as $fin) {
                $fin->setStatus(4);
                $em->merge($fin);
            }
            $em->flush($finSup);
        endif;
        return count($finSup);
    }

    public function payeneSfMailer(array $receivers, $senderMail, $subject, $messageBody) {
        $from = array("$senderMail" => $subject);
        $to = array();
        foreach ($receivers as $receiver) {
            if (!in_array($receiver, $to)) {
                $to[] = $receiver;
//                // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
//                $headers  = 'MIME-Version: 1.0' . "\r\n";
//                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//                // En-têtes additionnels
//                $headers .= 'From: Proweb' . "\r\n";
//                mail ( $receiver , $subject , $messageBody, $headers);
            }
        }

        
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('no-reply@prowebgroupe.com')
                ->setTo($to)
                ->setCharset('utf-8')
                ->setBody($messageBody);
        $message->setContentType("text/html");
        $mailer = $this->get('mailer')->send($message);
        //dump($mailer);
        
    }

    public static function payeneSfMailerStatic(array $receivers, $senderMail, $subject, $messageBody, $controller, $bcc = null) {
        $from = array("$senderMail" => $subject);
        $to = array();
        foreach ($receivers as $receiver) {
            if (!in_array($receiver, $to)) {
                $to[] = $receiver;
//                 // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
//                $headers  = 'MIME-Version: 1.0' . "\r\n";
//                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
//                // En-têtes additionnels
//                $headers .= 'From: Proweb' . "\r\n";
//                mail ( $receiver , $subject , $messageBody, $headers);
            }
        }

         $mail = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom('no-reply@prowebgroupe.com')
                ->setTo($to)
                ->setCharset('utf-8')
                ->setBody($messageBody);

        if( $bcc != NULL ){
            $mail->setBcc( $bcc );
        }

        $mail->setContentType("text/html");

        $controller->get('mailer')->send($mail);

        
//        $message = \Swift_Message::newInstance()
//                ->setSubject($subject)
//                ->setFrom($from)->setTo($to)
//                ->setBody($messageBody);
//        $mailer = $controller->get('mailer')->send($message);
//        dump($mailer);
        
    }

}
