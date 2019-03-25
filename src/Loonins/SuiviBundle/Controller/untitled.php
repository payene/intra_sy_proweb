dump($request->getHeader());
dump($request->headers->get('referer'));

<?php

namespace Loonins\SuiviBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\SuiviBundle\Entity\Stat;
use Loonins\SuiviBundle\Entity\Journal;
use Loonins\SuiviBundle\Entity\TypeTable;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Stat controller.
 *
 * @Route("/stat")
 */
class StatController extends Controller
{

    /**
     * Lists all Stat entities.
     *
     * @Route("/export", name="stat_export")
     * @Method("GET")
     * @Template()
    **/
    public function exportAction()
    {
        $session = $this->get('session');
        $str = $session->get('export_str');


        $response = new Response($str);
        $response->headers->set('Content-Type', 'application/xls');
        return $response;
    }


    public function createSearchForm(){
        $formBuilder = $this->createFormBuilder();
        $today = new \DateTime();
        $tomorrow = (new \DateTime())->add( new \DateInterval('P1D'));
        $formBuilder
                ->add('debut',  DateType::class, array('widget' => 'single_text', 'data' =>  $today ))
                ->add('fin', DateType::class, array('widget' => 'single_text', 'data' => $tomorrow) )
                ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false))
                ->add('animatrice', EntityType::class, array('class' => 'LooninsSuiviBundle:Animatrice',
                    'required' => false,
                    'query_builder' => function ($er) {
                        return $er->createQueryBuilder('a')
                                ->orderBy('a.login', 'ASC')
                                ->where('a.del = :del')
                                ->setParameter('del', 0);
                    }
                ))
                ->add('submit',SubmitType::class, array('label' => 'Filtrer'))
                ;
        return $formStat = $formBuilder->getForm();
    }


    // public function getCumul(){
    //     $em = $this->getDoctrine()->getManager();
    //     $qb = $em->createQueryBuilder();
    //     $qb ->add('select', new \Expr\Select(array('a')))
    //         ->add('from', new \Expr\From('LooninsSuiviBundle:Stat', 's'))
    //         ->add(
    //             'where', $qb->expr()->orX(
    //                 $qb->expr()->substring('s.programmed', 0, 2);
    //             )
    //         )
    //     ;
    //     $query = $qb->getQuery();
    //     $result = $query->getResult();
    //     return $result;
    // }


    /**
     * Lists all Stat entities.
     *
     * @Route("/rech", name="stat_search")
     * @Method({"GET","POST"})
     * @Template()
    **/
    public function searchAction(Request $request)
    {
        
        dump($request);
        // die(' - ');
        $session = $request->getSession();
        // dump($session->get('cumul'));
        $em = $this->getDoctrine()->getManager();
        $formBuilder = $this->createFormBuilder();
        $newFormBuilder = $this->createFormBuilder();
            // On ajoute les champs de l'entité que l'on veut à notre formulaire  
        $today = new \DateTime();
        $tomorrow = (new \DateTime())->add( new \DateInterval('P1D'));
       
        $formStat = $this->createSearchForm();
        
        $newFormBuilder
                ->add('dateStat', DateType::class, array('widget' => 'single_text'))
                ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false))
                // ->add('submit')
                ;
        $newForm = $newFormBuilder->getForm();

        if ($request->getMethod() == 'POST') {
            $data = $request->get('form');
            $busDtDebut = $data['debut'];
            $busDtFin = $data['fin'];
            $login = $data['animatrice'];
            $table = $data['type'];
            $typeTable = $em->getRepository('LooninsSuiviBundle:TypeTable')->find(intval($table));
            $animatrice = $em->getRepository('LooninsSuiviBundle:Animatrice')->find(intval($login));
            
            $query = $em->createQueryBuilder();
            //Recherche pour une seule animatrice avec cumul
            if(!empty($login))    {

                $query
                        ->select('s')
                        ->from('LooninsSuiviBundle:Stat', 's')
                        ->leftJoin('s.animatrice', 'a')
                        ->where("s.type = :type")
                        ->andwhere("s.dateStat BETWEEN :debut AND :fin")
                        ->andwhere("s.del = 0")
                        ->andwhere("a.del = 0")
                        ->andwhere("s.animatrice = :animatrice")
                        ->setParameter('type', $typeTable->getId())
                        ->setParameter('debut', $busDtDebut)
                        ->setParameter('fin', $busDtFin)
                        ->setParameter('animatrice', $login)
                        ->orderBy('s.dateStat', 'DESC')
                    ;
                $cumul = null;

                $stat = $query->getQuery()->getResult();
                $session->set('stat', $stat);
            }
            else{
                //Recherche pour toutes les animatrices avec cumul seule animatrice
                $query
                        ->select('s as SS,AVG(s.msgParHeure) as AVG_MPH , SUM(s.programmedSeconds) as PRG , SUM(s.total) as TT, SUM(s.reelSeconds) as RL , SUM(s.msgParConv) as MPC, SUM(s.prime) as SUM_PRM')
                        ->from('LooninsSuiviBundle:Stat', 's')
                        ->leftJoin('s.animatrice', 'a')
                        ->where("s.type = :type")
                        ->andwhere("s.dateStat BETWEEN :debut AND :fin")
                        ->andwhere("s.del = 0")
                        ->andwhere("a.del = 0")
                        ->setParameter('type', $typeTable->getId())
                        ->setParameter('debut', $busDtDebut)
                        ->setParameter('fin', $busDtFin)
                        ->groupBy('a.id')
                        ->orderBy('a.login', 'ASC')
                    ;

                $stat = null;
                $session = $request->getSession();

                $cumul = $query->getQuery()->getResult();
                $session->set('cumul', $cumul);
                $session->set('busDtFin', $busDtFin);
                $session->set('busDtDebut', $busDtDebut);
            }
            $params = array(
                'notif' => "",
                'form'   => $newForm->createView(),
                'formRech'   => $formStat->createView(),    
                'table'   => $typeTable,
                'busDtDebut'   => $busDtDebut,
                'busDtFin'   => $busDtFin,
                'stats'   => $stat,
                'cumul'   => $cumul,
                'animatrice'   => $animatrice,
            );
            $params['typeTable'] = $typeTable;

            //dump($params['formRech']);
            return $this->render('LooninsSuiviBundle:Stat:search.html.twig',  $params);
           
        }
        //return $this->redirect($this->generateUrl('stat'));   
        $params =array();
        $params['formRech'] = $formStat->createView();
        $params['form'] = $newForm->createView();
        $cumul = $session->get('cumul');
        $stat = $session->get('stat');
        $animatrice = $session->get('animatrice');
        $busDtDebut = $session->get('busDtDebut');
        $busDtFin = $session->get('busDtFin');
        
        //die();
        // dump($cumul);
        //if( )
        dump($uri = $request);
        $edit = strpos($uri, '/edit');
        if($edit == false){
        }
        else{
            if(!empty($stat) ){
                $params['stats'] = $stat;
                $params['animatrice'] = $animatrice;

            }
            if(!empty($cumul)){
                $params['cumul'] = $cumul;
                $params['table'] = $cumul[0]['SS']->getType()->getTypeTable();
                $params['typeTable'] = $cumul[0]['SS']->getType();
                $params['animatrice'] = null;
            }

            if(!empty($busDtFin)){
                $params['busDtFin'] = $busDtFin;
            }

            if(!empty($busDtDebut)){
                $params['busDtDebut'] = $busDtDebut;
            }
        }
        dump($edit);
        // die();


        return $params;
    }
    /**
     * Lists all Stat entities.
     *
     * @Route("/stat", name="stat")
     * @Method({"GET","POST"})
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $formBuilder = $this->createFormBuilder();
// On ajoute les champs de l'entité que l'on veut à notre formulaire  
        $formBuilder
                ->add('dateStat', DateType::class, array('widget' => 'single_text'))
                ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false))
                // ->add('submit')
                ;
        $formStat = $formBuilder->getForm();

        if ($request->getMethod() == 'POST') {
            $data = $request->get('form');
            $dateStat = $data['dateStat'];
            $table = $data['type'];
            $session = $this->get('session');
            $session->set('type',$table);
            $session->set('date',$dateStat);
            
            $typeTable = $em->getRepository('LooninsSuiviBundle:TypeTable')->find(intval($table));
            $user = $this->getUser();

            $existJournal = $em->getRepository('LooninsSuiviBundle:Journal')->findOneBy(['dateStat'=> new \DateTime($dateStat)]);
            if(empty($existJournal)){
                $journal = new Journal();
                $journal->setDateModif(new \DateTime());
                $journal->setDateStat(new \DateTime($dateStat));
                $journal->setTypeTable($typeTable);
                $journal->setAuteur($user);
                $em->persist($journal);
                $em->flush();
            }

            return $this->redirect($this->generateUrl('stat_new'));
        }

        $results = array();
        return array(
            // 'row' => $row,
            'form'   => $formStat->createView(),
            'formRech'   => $formStat->createView(),
            'stats'   => array()
        );
    }
    /**
     * Creates a new Stat entity.
     *
     * @Route("/", name="stat_create")
     * @Method("POST")
     * @Template("LooninsSuiviBundle:Stat:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Stat();
        $session = $this->get('session');
        $idTable = $session->get('type');

        $em = $this->getDoctrine()->getManager();
        $table = $em->getRepository('LooninsSuiviBundle:TypeTable')->find($idTable);

        $form = $this->createCreateForm($entity,$table);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $retard = $entity->getRetard();
            if($retard == 'red'){
                $entity->setRetard(1);
            }
            else{
                $entity->setRetard(0);
            }
            // var_dump();

            // die('-/-');

            
            $dstat =  $session->get('date');
            //var_dump($ds);
            // $ds = new \DateTime($dstat);
            
            if( $dstat instanceOf \DateTime){
                $entity->setDateStat($dstat);
            }
            else{
                $entity->setDateStat(new \DateTime($dstat));
            }
            $entity->setDel(0);
            $entity->setDateSaisie(new \DateTime());
            $entity->setType($table);
            
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('stat_new'));
           
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Stat entity.
    *
    * @param Stat $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Stat $entity, $table)
    {
        if($table->getMsgByConvRequired()){
            $statType = 'Loonins\SuiviBundle\Form\StatMsgConvType';
        }
        else{
            $statType = 'Loonins\SuiviBundle\Form\StatType';
        }

        $form = $this->createForm($statType, $entity, array(
            'action' => $this->generateUrl('stat_create'),
            'method' => 'POST',
        ));
        $form->add('submit', SubmitType::class, array('label' => 'Enregistrer'));
        return $form;
    }

    /**
     * Displays a form to create a new Stat entity.
     *
     * @Route("/new", name="stat_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Stat();

        $session = $this->get('session');
        $idTable = $session->get('type');
        $dstat =  $session->get('date');
        $stats = array();
        if($idTable == null || $dstat == null){
            $this->get('session')->getFlashBag()->add('error', 'Cliquez sur le boutton Nouvelle entree statistique');
            return $this->redirect($this->generateUrl('stat'));
        }

        $ds = $dstat;

        $em = $this->getDoctrine()->getManager();

        $table = $em->getRepository('LooninsSuiviBundle:TypeTable')->find($idTable);
        
        $query = $em->createQueryBuilder();
        $query
            ->select('s')
            ->from('LooninsSuiviBundle:Stat', 's')
            ->leftJoin('s.animatrice', 'a')
            ->where("s.dateStat = :ds")
            ->andwhere("s.type = :t")
            ->andwhere("a.del = :del")
            ->orderBy('s.dateSaisie', 'DESC')
            ->setParameter('ds', $ds)
            ->setParameter('del', 0)
            ->setParameter('t', $table)
        ;
        $stats = $query->getQuery()->getResult();
       
        $form   = $this->createCreateForm($entity, $table);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'stats'   => $stats,
            'dateStat'   => $dstat,
            'typeTable'   => $table,
        );
    }

    /**
     * Finds and displays a Stat entity.
     *
     * @Route("/{table}/{id}", name="stat_show")
     * @Method("GET")
     * @Template()
    **/
    public function showAction($id,TypeTable $table)
    {
        $em = $this->getDoctrine()->getManager();
        $ds = new \DateTime($id);
        $entities = $em->getRepository('LooninsSuiviBundle:Stat')->findBy(['dateStat'=>$ds]);

        $entities = $em->getRepository('LooninsSuiviBundle:Stat')->createQueryBuilder('s')
            // On joint sur l'attribut employe
            ->leftJoin('s.animatrice', 'a')
            ->where('s.dateStat = :ds')
            ->andWhere('a.del = :del')
            ->andWhere('s.del = :del')
            ->setParameter('ds', $ds)
            ->setParameter('del', 0)
            ->orderBy('a.login','ASC')
            ->getQuery()->getResult();


        $journal = $em->getRepository('LooninsSuiviBundle:Journal')->findOneBy(['dateStat'=>$ds]);
        if(!empty($journal)){
            $session = $this->get('session');
            $session->set('type',$table->getId());
            $session->set('date',$ds);            
        }
        
        $formBuilder = $this->createFormBuilder();
        // $formBuilder
        //     ->add('dateStat', DateType::class, array('widget' => 'single_text'))
        //     ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false));
        // $formStat = $formBuilder->getForm();
        $formStat = $this->createSearchForm();

        // if (!$entities) {
        //     throw $this->createNotFoundException('Unable to find Stat entity.');
        // }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'stats'      => $entities,
            'dateStat'      => $id,
            'table'      => $table,
            'journal'      => $journal,
            'formRech'   => $formStat->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Stat entity.
     *
     * @Route("/{id}/view", name="stat_view")
     * @Method("GET")
     * @Template()
     */
    public function viewAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Stat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stat entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);
        
        return $this->render('LooninsSuiviBundle:Stat:view.html.twig',  array(
            'entity'   => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }



    /**
     * Displays a form to edit an existing Stat entity.
     *
     * @Route("/{id}/edit/{from}", name="stat_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id, $from)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Stat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stat entity.');
        }

        $editForm = $this->createEditForm($entity, $entity->getType());
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'rech' => $from,
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Stat entity.
    *
    * @param Stat $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Stat $entity)
    {
        $table = $entity->getType();
        if($table->getMsgByConvRequired()){
            $statType = 'Loonins\SuiviBundle\Form\StatMsgConvType';
        }
        else{
            $statType = 'Loonins\SuiviBundle\Form\StatType';
        }

        $form = $this->createForm($statType, $entity, array(
            'action' => $this->generateUrl('stat_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit',  SubmitType::class, array('label' => 'Sauvegarder'));

        return $form;
    }
    /**
     * Edits an existing Stat entity.
     *
     * @Route("/{id}", name="stat_update")
     * @Method("PUT")
     * @Template("LooninsSuiviBundle:Stat:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Stat')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Stat entity.');
        }
      

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity, $entity->getType());
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            dump($request->getHeader());
            die(' - ');
            $retard = $entity->getRetard();
            if($retard == 'red'){
                $entity->setRetard(1);
            }
            else{
                $entity->setRetard(0);
            }

            $em->flush();
            $this->addToJournal($em, $stat = $entity, $author = $this->getUser() , $action = "edit");

            $this->get('session')->getFlashBag()->add('info', 'Entree statistique modifiée avec success');


            /*
            $params =array();
            $session = $this->get('session');
            $newFormBuilder = $this->createFormBuilder();
            $newFormBuilder
                ->add('dateStat', DateType::class, array('widget' => 'single_text'))
                ->add('type', EntityType::class, array('class' => 'LooninsSuiviBundle:TypeTable', 'multiple' => false))
                ;
            $newForm = $newFormBuilder->getForm();
            $formStat = $this->createSearchForm();
            $params['formRech'] = $formStat->createView();
            $params['form'] = $newForm->createView();
            $cumul = $session->get('cumul');
            $stat = $session->get('stat');
            $animatrice = $session->get('animatrice');
            $busDtDebut = $session->get('busDtDebut');
            $busDtFin = $session->get('busDtFin');
            dump($uri = $request);
            $edit = strpos($uri, '/rech');
            if($edit == false){
            }
            else{
                if(!empty($stat) ){
                    $params['stats'] = $stat;
                    $params['animatrice'] = $animatrice;

                }
                if(!empty($cumul)){
                    $params['cumul'] = $cumul;
                    $params['table'] = $cumul[0]['SS']->getType()->getTypeTable();
                    $params['animatrice'] = null;
                }

                if(!empty($busDtFin)){
                    $params['busDtFin'] = $busDtFin;
                }

                if(!empty($busDtDebut)){
                    $params['busDtDebut'] = $busDtDebut;
                }
                dump($params);
                // die('----');
                return $this->redirect($this->generateUrl('stat_search'));
            }
            dump($edit);
            die('');

            */

            // return $this->redirect($this->generateUrl('stat_new'));
            // return $this->redirect($this->generateUrl('stat_show',
            //     array(
            //     'id'      => $entity->getDateStat()->format('Ymd'),
            //     'table'      => $entity->getType()->getId(),
            // )));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Deletes a Stat entity.
     *
     * @Route("/{id}", name="stat_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsSuiviBundle:Stat')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Stat entity.');
            }

            $entity->setDel(1);
            
            $em->merge($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('stat_show',
                array(
                    'id' => $entity->getDateStat()->format('Ymd'),
                    'table' => $entity->getType()->getId(),
                )
            ));
        }
        
        // return $this->redirect($this->generateUrl('stat'));

    }

    /**
     * Creates a form to delete a Stat entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('stat_delete', array('id' => $id)))
            ->setMethod('POST')
            ->add('submit', SubmitType::class, array('label' => 'Supprimer cette statistique'))
            ->getForm()
        ;
    }

    public function addToJournal($em, $stat, $author, $action){
        $journal = new Journal();
        $journal->setLigneStat($stat);
        $journal->setAuteur($author);
        $journal->setAction($action);
        $em->persist($journal);
        $em->flush();
    }
}
