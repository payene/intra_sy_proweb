<?php

namespace Loonins\NeguitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\NeguitBundle\Entity\Planning;
use Loonins\NeguitBundle\Entity\AffectLoginAnim;
use Loonins\NeguitBundle\Form\PlanningType;
use Symfony\Component\Form\FormError;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * Planning controller.
 *
 * @Route("/neguit/planning")
 */
class PlanningController extends Controller
{
    /**
     * Lists all Planning entities.
     *
     * @Route("/", name="planning_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $planning = new Planning();
        $strToday = (new \DateTime())->format('Y-m-d') . ' 00:00:00';  
        $today  = new \DateTime($strToday);
        $employe = $this->getUser()->getEmploye();
        $affectNeguit = NULL;
        if(!empty($employe)){
            $affectNeguit = $em->getRepository('LooninsNeguitBundle:AffectLoginNeguit')->findOneBy(['employe' => $employe->getId(),'finAffectation' => NULL]);            
        }

        $formTwig = NULL;
        if(!empty($affectNeguit)){
            // $planning->setHeureDebut('09:15');
            // $planning->setHeureDebut('12:30');
            $datePlanning = $today->format('Y-m-d') . ' 00:00:00';
            $planning->setDatePlanning($today);
            // $planning->setHeureDebut("10:00");
            $form = $this->createForm('Loonins\NeguitBundle\Form\PlanningType', $planning);
            $form->handleRequest($request);
            $planning->setLogin( $affectNeguit );
            // dump($datePlanning); exit();
            if ($form->isSubmitted()){

                if($planning->getDatePlanning()->format('Y-m-d') != date('Y-m-d')){
                    $form->get('datePlanning')->addError(new FormError('Date incorrecte'));
                }

                if(empty($planning->getTypeAnim())){
                    $form->get('typeAnim')->addError(new FormError('Type animation requis'));
                }

                if(empty($planning->getFantome())){
                    $form->get('fantome')->addError(new FormError('Fantome requis'));
                }

                $heureDebut = $planning->getHeureDebut();
                $tabHeure = explode(":", $heureDebut);
                if(count($tabHeure) != 2){
                    $form->get('heureDebut')->addError(new FormError('Format heure incorrect .'));
                }
                else{
                    if(intval($tabHeure[0] > 24) || intval($tabHeure[0] < 0 )){
                        $form->get('heureDebut')->addError(new FormError('Format heure incorrect ..'));
                    }
                    if(intval($tabHeure[1] > 59) || intval($tabHeure[1] < 0 )){
                        $form->get('heureDebut')->addError(new FormError('Format heure incorrect'));
                    }
                }


                $heureFin = $planning->getHeureFin();
                $tabHeure = explode(":", $heureDebut);
                if(count($tabHeure) != 2){
                    $form->get('heureFin')->addError(new FormError('Format heure incorrect'));
                }
                else{
                    if(intval($tabHeure[0] > 24) || intval($tabHeure[0] < 0 )){
                        $form->get('heureFin')->addError(new FormError('Format heure incorrect'));
                    }
                    if(intval($tabHeure[1] > 59) || intval($tabHeure[1] < 0 )){
                        $form->get('heureFin')->addError(new FormError('Format heure incorrect'));
                    }

                    $dateHeureDebut = new \DateTime($planning->getDatePlanning()->format('Y-m-d') . " ". $heureDebut);
                    $dateHeureFin = new \DateTime($planning->getDatePlanning()->format('Y-m-d') . " ". $heureFin);

                    if($dateHeureFin < $dateHeureDebut){
                        $form->get('heureDebut')->addError(new FormError('Interval d\'heure incorrect incorrect'));
                    }
                }




                if($form->isValid()) {
                    $planning->setCreatedAt(new \DateTime());
                    $em->persist($planning);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', 'Login crée avec succes');
                    return $this->redirectToRoute('planning_index');
                }
            }

            $formTwig = $form->createView();
        }
        $plannings = $em->getRepository('LooninsNeguitBundle:Planning')->findBy(['datePlanning' => $today]);
        // exit;

        return $this->render('LooninsNeguitBundle:planning:index.html.twig', array(
            'plannings' => $plannings,
            'planning' => $planning,
            'form' => $formTwig,
        ));
        // return $this->render('planning/index.html.twig', array(
        //     'plannings' => $plannings,
        // ));
    }


      /**
     * Lists all Planning entities.
     *
     * @Route("/history", name="planning_history")
     * @Method({"GET", "POST"})
     */
    public function historyAction(Request $request)
    {
        
        $em = $this->getDoctrine()->getManager();
        $formBuilder = $this->createFormBuilder();
        $action = $this->generateUrl('grhsearch_advanced');

        $formBuilder
            ->add('datePlanning', DateType::class, array('widget' => 'single_text'))
            ->add('fantome', LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'),array('class' => 'LooninsNeguitBundle:ProfilVirtuel', 'required' => false, 'multiple' => false, 'query_builder' => function ($er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.pseudo', 'ASC')
                        ;
                }))
            ->add('typeAnim', LegacyFormHelper::getType('Symfony\Bridge\Doctrine\Form\Type\EntityType'),array('class' => 'LooninsNeguitBundle:TypeAnimNeguit', 'required' => false, 'multiple' => false, 'query_builder' => function ($er) {
                    return $er->createQueryBuilder('t')
                        ->orderBy('t.libelle', 'ASC')
                        ;
                }))
        ;
        $plannings = [];
        $formRech = $formBuilder->getForm();
        $formRech->handleRequest($request);
        $plannings = [];
        if ($formRech->isSubmitted()){

            $data = $request->get('form');
            $datePlanning = $data['datePlanning'];
            $typeAnim = $data['typeAnim'];
            $fantome = $data['fantome'];

            // dump($datePlanning);
            $datePlanning = new \DateTime($datePlanning . ' '. '00:00:00s');
            $criteras['datePlanning'] = $datePlanning;
            if(!empty($fantome)){
                $criteras['fantome'] = $fantome;
            }

            if(!empty($typeAnim)){
                $criteras['typeAnim'] = $typeAnim;
            }

            $plannings = $em->getRepository('LooninsNeguitBundle:Planning')->findBy($criteras);    // dump($datePlanning); exit();

            if($formRech->isValid()) {
                // $em->getRepository('LooninsNeguitBundle:Planning')->findBy(['debut' => $planning->getHeureDebut() ]);
            }
        }

         //$em->getRepository('LooninsNeguitBundle:Planning')->findBy(['datePlanning' => $today]);
        // exit;

        return $this->render('LooninsNeguitBundle:planning:search.html.twig', array(
            'plannings' => $plannings,
            'form' => $formRech->createView(),
        ));
        // return $this->render('planning/index.html.twig', array(
        //     'plannings' => $plannings,
        // ));
    }



    /**
     * Creates a new Planning entity.
     *
     * @Route("/new", name="planning_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $planning = new Planning();
        $form = $this->createForm('Loonins\NeguitBundle\Form\PlanningType', $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($planning);
            $em->flush();

            return $this->redirectToRoute('planning_new');
        }

        return $this->render('planning/new.html.twig', array(
            'planning' => $planning,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Planning entity.
     *
     * @Route("/{id}", name="planning_show")
     * @Method("GET")
     */
    public function showAction(Planning $planning)
    {
        $deleteForm = $this->createDeleteForm($planning);

        return $this->render('planning/show.html.twig', array(
            'planning' => $planning,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Planning entity.
     *
     * @Route("/update/{id}", name="planning_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Planning $planning)
    {
        $deleteForm = $this->createDeleteForm($planning);
        $editForm = $this->createForm('Loonins\NeguitBundle\Form\PlanningType', $planning);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted()) {

            if($planning->getDatePlanning()->format('Y-m-d') != date('Y-m-d')){
                $editForm->get('datePlanning')->addError(new FormError('Date incorrecte'));
            }

            if(empty($planning->getTypeAnim())){
                $editForm->get('typeAnim')->addError(new FormError('Type animation requis'));
            }

            if(empty($planning->getFantome())){
                $editForm->get('fantome')->addError(new FormError('Fantome requis'));
            }

            $heureDebut = $planning->getHeureDebut();
            $tabHeure = explode(":", $heureDebut);
            if(count($tabHeure) != 2){
                $editForm->get('heureDebut')->addError(new FormError('Format heure incorrect'));
            }
            else{
                if(intval($tabHeure[0] > 24) || intval($tabHeure[0] < 0 )){
                    $editForm->get('heureDebut')->addError(new FormError('Format heure incorrect'));
                }
                if(intval($tabHeure[1] > 59) || intval($tabHeure[1] < 0 )){
                    $editForm->get('heureDebut')->addError(new FormError('Format heure incorrect'));
                }
            }


            $heureFin = $planning->getHeureFin();
            $tabHeure = explode(":", $heureDebut);
            if(count($tabHeure) != 2){
                $editForm->get('heureFin')->addError(new FormError('Format heure incorrect'));
            }
            else{
                if(intval($tabHeure[0] > 24) || intval($tabHeure[0] < 0 )){
                    $editForm->get('heureFin')->addError(new FormError('Format heure incorrect'));
                }
                if(intval($tabHeure[1] > 59) || intval($tabHeure[1] < 0 )){
                    $editForm->get('heureFin')->addError(new FormError('Format heure incorrect'));
                }

                $dateHeureDebut = new \DateTime($planning->getDatePlanning()->format('Y-m-d') . " ". $heureDebut);
                $dateHeureFin = new \DateTime($planning->getDatePlanning()->format('Y-m-d') . " ". $heureFin);

                if($dateHeureFin < $dateHeureDebut){
                    $editForm->get('heureDebut')->addError(new FormError('Interval d\'heure incorrect incorrect'));
                }
            }






            if($editForm->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($planning);
                $em->flush();

                return $this->redirectToRoute('planning_index');
            }
        }

        return $this->render('LooninsNeguitBundle:planning:edit.html.twig', array(
            'planning' => $planning,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Planning entity.
     *
     * @Route("/{id}", name="planning_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Planning $planning)
    {
        $form = $this->createDeleteForm($planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($planning);
            $em->flush();
        }

        return $this->redirectToRoute('planning_index');
    }

    /**
     * Creates a form to delete a Planning entity.
     *
     * @param Planning $planning The Planning entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Planning $planning)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('planning_delete', array('id' => $planning->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
