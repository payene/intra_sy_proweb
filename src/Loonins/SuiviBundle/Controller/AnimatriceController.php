<?php

namespace Loonins\SuiviBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\SuiviBundle\Entity\Animatrice;
use Loonins\SuiviBundle\Entity\Planning;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Loonins\SuiviBundle\Form\AnimatriceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Response;


/**
 * Animatrice controller.
 *
 * @Route("/animatrice")
 */
class AnimatriceController extends Controller
{

    /**
     * Lists all Animatrice entities.
     *
     * @Route("/anim/all", name="animatrice")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->createQueryBuilder();
        // dump($query);
        $animatrices = [];
        $query  ->select('a')
            ->from('LooninsSuiviBundle:Animatrice', 'a')
            ->join('a.employe', 'e')
            ->join('a.login', 'l')
            ->where('a.finAffectation IS  NULL')
            ->orwhere('a.finAffectation  =  :null')
            ->andwhere('e.trashed  =  0')
            ->setParameter('null', '0000-00-00 00:00:00')
            ->orderBy('e.prenoms', 'ASC')
            ->orderBy('e.nom', 'ASC')
            ->orderBy('a.debutAffectation', 'DESC')
            ->orderBy('l.login', 'ASC')
        ;
        $animatrices = $query->getQuery()->getResult();

        // $animatrices = $em->getRepository('LooninsSuiviBundle:Animatrice')->findBy(['finAffectation'=> null],['login'=>'asc']);

        return array(
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'entities' => $animatrices,
        );
    }

    public function deletePlannAction(Request $request){
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        $planning = $em->getRepository('LooninsSuiviBundle:Planning')->find($id);
        $em->remove( $planning );
        $em->flush();
        return new Response('OK');
    }

    public function editPlannAction( Request $request ){
        $id = $request->request->get('id');
        $em = $this->getDoctrine()->getManager();
        
        $code_activite = $request->request->get('activite');
        $heureDeb = $request->request->get('heureDeb');
        $heureFin = $request->request->get('heureFin');

        $activite = $em->getRepository('LooninsSuiviBundle:TypeTable')->findOneByCode( $code_activite );
        $p = $em->getRepository('LooninsSuiviBundle:Planning')->find($id);
        $p->setHeureDebut( trim($heureDeb) );
        $p->setHeureFin(  trim($heureFin)  );
        $p->setActivite(  $activite  );    

        $em->persist( $p );
        $em->flush();

        return new Response('OK');
    }

    public function addPlannAction(Request $request){
        $code_activite = $request->request->get('activite');
        $heureDeb = $request->request->get('heureDeb');
        $heureFin = $request->request->get('heureFin');
        $semaine = $request->request->get('semaine');
        $annee = $request->request->get('annee');
        $jour = $request->request->get('jour');
        $animatrice_id = $request->request->get('animatrice');
        
        $em = $this->getDoctrine()->getManager();
        $activite = $em->getRepository('LooninsSuiviBundle:TypeTable')->findOneByCode( $code_activite );
        $animatrice = $em->getRepository('LooninsSuiviBundle:Animatrice')->find( $animatrice_id );

        $week_start = new \DateTime();
        $week_start->setISODate($annee, $semaine);

        $date = $week_start->add( new \DateInterval('P'.$jour.'D') );
        $p = new Planning();
        $p->setHeureDebut( trim($heureDeb) );
        $p->setHeureFin(  trim($heureFin)  );
        $p->setSemaine(   $semaine  );
        $p->setAnimatrice(   $animatrice   );
        $p->setActivite(  $activite  );
        $p->setDate(  $date  );
        
        $em->persist( $p );
        $em->flush();

        return new Response( $p->getId() );

    }

    public function plannAction(Request $request){

        $week = date('W');
        $year = date('Y');
        $debut = new \DateTime();
        $debut->setISODate($year,$week);

        $em = $this->getDoctrine()->getManager();

        $query = $em->createQueryBuilder();

        $query ->select('a')
            ->from('LooninsSuiviBundle:Animatrice', 'a')
            ->join('a.employe', 'e')
            ->join('a.login', 'l')
            ->where('a.finAffectation IS  NULL')
            ->orwhere('a.finAffectation  =  :null')
            ->andwhere('e.trashed  =  0')
            ->setParameter('null', '0000-00-00 00:00:00')
            ->orderBy('e.prenoms', 'ASC')
            ->orderBy('e.nom', 'ASC')
            ->orderBy('a.debutAffectation', 'DESC')
            ->orderBy('l.login', 'ASC')
        ;
        $animatrices = $query->getQuery()->getResult();

        $tab = [];

        if( $request->isMethod("POST") ){

            $semaine = $request->request->get('semaine');
            $debut_semaine = substr($semaine, 0, 10);
            $debut = \DateTime::createFromFormat('d/m/Y', $debut_semaine);
            $week = $debut->format("W");
            $year = $debut->format("Y");

        }

        foreach ($animatrices as $animatrice) {
            $tab[$animatrice->getId()] = [];
            $week_start = new \DateTime();
            $week_start->setISODate($year,$week);
            //récupération des planning du lundi au dimanche
            for ($i=0; $i < 7; $i++) { 
                $plannings = $em->createQuery('select s ' .
                                             ' from LooninsSuiviBundle:Planning s' .
                                             ' where s.animatrice = :animatrice' .
                                             ' and s.semaine = :week' .
                                             ' and s.date = :date'
                                            )
                                ->setParameter('animatrice', $animatrice)
                                ->setParameter('week', $week)
                                ->setParameter('date', $week_start->format('Y-m-d'))
                                ->getResult();
                                //dump($plannings); exit;
                $tab[$animatrice->getId()][ $i ] = [];
                foreach ($plannings as $planning) {
                    $tab[$animatrice->getId()][ $i ][] = $planning;
                }
                $week_start->add( new \DateInterval('P1D') );
            }
        }

        $activites = $em->getRepository('LooninsSuiviBundle:TypeTable')->findAll();

        return $this->render('LooninsSuiviBundle:Animatrice:plann.html.twig',  array( 
                    'animatrices' => $animatrices, 
                    'activites' => $activites,
                    'week' => $week,
                    'year' => $year,
                    'tab' => $tab,
                    'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
                    'debut' =>$debut,
                    )
                );

    }

    /**
     * Creates a new Animatrice entity.
     *
     * @Route("/create/anim", name="animatrice_create")
     * @Method("POST")
     * @Template("LooninsSuiviBundle:Animatrice:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Animatrice();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();

            $anim = $em->getRepository('LooninsSuiviBundle:Animatrice')->findOneBy(['finAffectation' => null, 'login'=> $entity->getLogin(),'del'=>0]);
            if(empty($anim)){
                $entity->setDel(0);
                $entity->setDebutAffectation( new \DateTime() );
                $em->persist($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', 'Affectation de login effectué avec success');
                return $this->redirect($this->generateUrl('animatrice'));
            }
            else{
                $this->get('session')->getFlashBag()->add('error', 'Ce login n\'est pas disponible');
            }
        }

        return array(
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Animatrice entity.
    *
    * @param Animatrice $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Animatrice $entity)
    {
        $form = $this->createForm('Loonins\SuiviBundle\Form\AnimatriceType', $entity, array(
            'action' => $this->generateUrl('animatrice_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Displays a form to create a new Animatrice entity.
     *
     * @Route("/new", name="animatrice_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Animatrice();
        $form   = $this->createCreateForm($entity);

        return array(
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Animatrice entity.
     *
     * @Route("/show/one/anim/{id}", name="animatrice_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Animatrice')->find($id);


    

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Animatrice entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Animatrice entity.
     *
     * @Route("/anim/edit/{id}t", name="animatrice_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Animatrice')->find($id);

        $constraint = $em->getRepository('LooninsSuiviBundle:Stat')->findBy(['animatrice' => $entity->getId()]);
        if(empty($constraint)){
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Animatrice entity.');
            }

            $editForm = $this->createEditForm($entity);
            $deleteForm = $this->createDeleteForm($id);

            return array(
                'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
                'entity'      => $entity,
                'edit_form'   => $editForm->createView(),
                'delete_form' => $deleteForm->createView(),
            );
        }
        else{
            return $this->redirect($this->generateUrl('animatrice_show', array('id' => $id)));
        }

        

    }

    /**
    * Creates a form to edit a Animatrice entity.
    *
    * @param Animatrice $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Animatrice $entity)
    {
        $form = $this->createForm( 'Loonins\SuiviBundle\Form\AnimatriceType', $entity, array(
            'action' => $this->generateUrl('animatrice_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Sauvgarder'));

        return $form;
    }
    /**
     * Edits an existing Animatrice entity.
     *
     * @Route("/update/{id}", name="animatrice_update")
     * @Method("PUT")
     * @Template("LooninsSuiviBundle:Animatrice:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Animatrice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Animatrice entity.');
        }
        $oldLogin = $entity->getLogin();
        dump($oldLogin);

        $anim = $em->getRepository('LooninsSuiviBundle:Animatrice')->findOneBy(['finAffectation' => null, 'login'=> $entity->getLogin()->getId(),'del'=>0]);


        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            if(empty($anim)  || $anim->getId() == $oldLogin->getId()){
                $em->flush();
                return $this->redirect($this->generateUrl('animatrice_edit', array('id' => $id)));
            }
            else{
                $this->get('session')->getFlashBag()->add('error', 'Ce login n\'est pas disponible');
            }
        }
        else{
            // die();
        }

        return array(
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Animatrice entity.
     *
     * @Route("/delete/{id}", name="animatrice_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $data = $request->get('form');
            $em = $this->getDoctrine()->getManager();
            $finAffectation = $data['finAffectation'];
            $overflow = [];
            var_dump($finAffectation);
            $finAffectation =  new \DateTime($finAffectation);
            // die('');
            $query = $em->createQueryBuilder();

             $query  ->select('s')
                ->from('LooninsSuiviBundle:Stat', 's')
                ->where('s.dateStat  > :fin')
                ->andwhere('s.animatrice  > :anim')
                ->setParameter('fin', $finAffectation)
                ->setParameter('anim', $id)
            ;
            $overflow = $query->getQuery()->getResult();
            $entity = $em->getRepository('LooninsSuiviBundle:Animatrice')->find($id);

            // dump($overflow);
            // die('');
            if(empty($overflow) && $finAffectation > $entity->getDebutAffectation() ){

                if (!$entity) {
                    throw $this->createNotFoundException('Unable to find Animatrice entity.');
                }
                $entity->setDel(1);
                $finAffectation->setTime(date('H'),date('i'), date('s'));
                $entity->setFinAffectation( $finAffectation);
                // dump($entity);
                $em->merge($entity);
                $em->flush();
                $this->get('session')->getFlashBag()->add('error', 'Affection terminé.');
                return $this->redirect($this->generateUrl('animatrice'));
            }
            else{
                $this->get('session')->getFlashBag()->add('error', 'Date fin affectation il existe des statistiques outre celle ci');                
            }

            // var_dump($entity);
            // die();
        }
        else{
            $this->get('session')->getFlashBag()->add('error', 'Erreur');
            // var_dump($id);
            // die('');
        }

        return $this->redirect($this->generateUrl('animatrice_show', array('id' => $id)));
    }

    /**
     * Creates a form to delete a Animatrice entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('animatrice_delete', array('id' => $id)))
            ->setMethod('POST')
            ->add('finAffectation', DateType::class, array('html5' => false,'widget' => 'single_text','format' => 'dd MM yyyy'))
            ->add('submit',  SubmitType::class, array('label' => 'Mettre fin a cette affectation'))
            ->getForm()
        ;
    }
}
