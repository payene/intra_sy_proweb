<?php

namespace Loonins\CalendarBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\CalendarBundle\Entity\PlanningHebdo;
use Loonins\CalendarBundle\Form\PlanningHebdoType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
/**
 * PlanningHebdo controller.
 *
 * @Route("/calendar/planninghebdo")
 */
class PlanningHebdoController extends Controller
{
    /**
     * Lists all PlanningHebdo entities.
     *
     * @Route("/", name="planninghebdo_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {

        $planning = new PlanningHebdo();

        $form = $this->createForm('Loonins\CalendarBundle\Form\PlanningHebdoType', $planning)
                        ->add('fichier',FileType::class,array("required"=>true,'mapped'=>false));

        $dir = $this->getParameter('fichier_directory');

        $form->handleRequest($request);

       //var_dump($dir);

       $today=new \Datetime();
        if ($form->isSubmitted() && $form->isValid()) {

            $repository = $this->getDoctrine()->getRepository('LooninsCalendarBundle:PlanningHebdo');
            $importation = $repository->findOneBy(['mois'=>$today->format('m'),'annee'=>$today->format('Y'),'numWeek'=>$form['numWeek']->getData(),'del'=>null]);
            
            if(empty($importation))
            {

            $file= $form["fichier"]->getData();
            $filename= "fichier_".uniqid().".".$file->guessExtension();
            $file->move($dir,$filename);
            $em = $this->getDoctrine()->getManager();
            $planning->setSource($filename);
            $planning->setMois($today->format('m'));
            $planning->setAnnee($today->format('Y'));
            $planning->setCreatedAt(new \Datetime());
            $planning->setCreatedBy($this->getUser());
            $em->persist($planning);
            $em->flush();


            return $this->redirectToRoute('planninghebdo_index');


            }
            else
            {
                $form->addError(new FormError('Planning deja existante'));
            }
        }


        $repository = $this->getDoctrine()->getRepository('LooninsCalendarBundle:PlanningHebdo');
        $tab=[];

        for ($i=(int) $today->format('m'); $i >=1 ; $i--) { 

            $planningHebdos = $this->getDoctrine()
            ->getManager()
            ->createQuery('SELECT p FROM Loonins\CalendarBundle\Entity\PlanningHebdo p WHERE p.del is null and p.annee = :anneeActuelle HAVING p.mois = :position')
            ->setParameter('anneeActuelle',$today->format('Y'))
            ->setParameter('position',$i)
            ->getResult();

            $tab[$i]=$planningHebdos;
        }
       

         //dump($tab);
         //exit();

        return $this->render('LooninsCalendarBundle:planninghebdo:index.html.twig', array(
            'form' => $form->createView(),
            'tab' => $tab,
            'dir' => $dir,
            'today' => $today,
           
        ));
    }

    /**
     * Creates a new PlanningHebdo entity.
     *
     * @Route("/new", name="planninghebdo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $planningHebdo = new PlanningHebdo();
        $form = $this->createForm('Loonins\CalendarBundle\Form\PlanningHebdoType', $planningHebdo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($planningHebdo);
            $em->flush();

            return $this->redirectToRoute('planninghebdo_show', array('id' => $planningHebdo->getId()));
        }

        return $this->render('LooninsCalendarBundle:planninghebdo:new.html.twig', array(
            'planningHebdo' => $planningHebdo,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a PlanningHebdo entity.
     *
     * @Route("/{id}", name="planninghebdo_show")
     * @Method("GET")
     */
    public function showAction(PlanningHebdo $planningHebdo)
    {
        $deleteForm = $this->createDeleteForm($planningHebdo);

        return $this->render('LooninsCalendarBundle:planninghebdo:show.html.twig', array(
            'planningHebdo' => $planningHebdo,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PlanningHebdo entity.
     *
     * @Route("/{id}/edit", name="planninghebdo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PlanningHebdo $planningHebdo)
    {
        $deleteForm = $this->createDeleteForm($planningHebdo);
        $editForm = $this->createForm('Loonins\CalendarBundle\Form\PlanningHebdoType', $planningHebdo);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($planningHebdo);
            $em->flush();

            return $this->redirectToRoute('planninghebdo_edit', array('id' => $planningHebdo->getId()));
        }

        return $this->render('LooninsCalendarBundle:planninghebdo:edit.html.twig', array(
            'planningHebdo' => $planningHebdo,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a PlanningHebdo entity.
     *
     * @Route("/{id}", name="planninghebdo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PlanningHebdo $planningHebdo)
    {
        $form = $this->createDeleteForm($planningHebdo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($planningHebdo);
            $em->flush();
        }

        return $this->redirectToRoute('planninghebdo_index');
    }

    /**
     * Creates a form to delete a PlanningHebdo entity.
     *
     * @param PlanningHebdo $planningHebdo The PlanningHebdo entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PlanningHebdo $planningHebdo)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('planninghebdo_delete', array('id' => $planningHebdo->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
