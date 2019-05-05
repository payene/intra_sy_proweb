<?php

namespace Loonins\CalendarBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\CalendarBundle\Entity\PlanningConge;
use Loonins\CalendarBundle\Form\PlanningCongeType;

/**
 * PlanningConge controller.
 *
 * @Route("/planningconge")
 */
class PlanningCongeController extends Controller
{
    /**
     * Lists all PlanningConge entities.
     *
     * @Route("/", name="planningconge_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $planning = new PlanningConge();

        $form = $this->createForm('Loonins\CalendarBundle\Form\PlanningCongeType', $planning);

        $dir = $this->getParameter('fichier_directory').'/planningConge';


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $file= $form["source"]->getData();
            $filename= "fichier_".uniqid().".".$file->guessExtension();
            $file->move($dir,$filename);
            $em = $this->getDoctrine()->getManager();
            $planning->setSource($filename);
            $planning->setCreatedAt(new \Datetime());
            $planning->setCreatedBy($this->getUser());
            $em->persist($planning);
            $em->flush();

            return $this->redirectToRoute('planningconge_index');

        }

        $repository = $this->getDoctrine()->getRepository('LooninsCalendarBundle:planningconge');
        $plannings = $repository->findBy(['del'=>null]);

         return $this->render('LooninsCalendarBundle:planningconge:index.html.twig', array(
            'form' => $form->createView(),
            'plannings' => $plannings,
            'dir' => $dir,
           
        ));
    }

    /**
     * Creates a new PlanningConge entity.
     *
     * @Route("/new", name="planningconge_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $planningConge = new PlanningConge();
        $form = $this->createForm('Loonins\CalendarBundle\Form\PlanningCongeType', $planningConge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($planningConge);
            $em->flush();

            return $this->redirectToRoute('planningconge_show', array('id' => $planningConge->getId()));
        }

        return $this->render('planningconge/new.html.twig', array(
            'planningConge' => $planningConge,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a PlanningConge entity.
     *
     * @Route("/{id}", name="planningconge_show")
     * @Method("GET")
     */
    public function showAction(PlanningConge $planningConge)
    {
        $deleteForm = $this->createDeleteForm($planningConge);

        return $this->render('planningconge/show.html.twig', array(
            'planningConge' => $planningConge,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing PlanningConge entity.
     *
     * @Route("/{id}/edit", name="planningconge_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PlanningConge $planningConge)
    {
        $deleteForm = $this->createDeleteForm($planningConge);
        $editForm = $this->createForm('Loonins\CalendarBundle\Form\PlanningCongeType', $planningConge);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($planningConge);
            $em->flush();

            return $this->redirectToRoute('planningconge_edit', array('id' => $planningConge->getId()));
        }

        return $this->render('planningconge/edit.html.twig', array(
            'planningConge' => $planningConge,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a PlanningConge entity.
     *
     * @Route("/{id}", name="planningconge_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PlanningConge $planningConge)
    {
        $form = $this->createDeleteForm($planningConge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($planningConge);
            $em->flush();
        }

        return $this->redirectToRoute('planningconge_index');
    }

    /**
     * Creates a form to delete a PlanningConge entity.
     *
     * @param PlanningConge $planningConge The PlanningConge entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PlanningConge $planningConge)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('planningconge_delete', array('id' => $planningConge->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
