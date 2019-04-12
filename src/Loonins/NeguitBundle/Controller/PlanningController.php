<?php

namespace Loonins\NeguitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\NeguitBundle\Entity\Planning;
use Loonins\NeguitBundle\Form\PlanningType;

/**
 * Planning controller.
 *
 * @Route("/planning")
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
        $planning->setDatePlanning( new \DateTime());
        $planning->setHeureDebut("10:00");
        $form = $this->createForm('Loonins\NeguitBundle\Form\PlanningType', $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($planning);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Login crée avec succes');
            return $this->redirectToRoute('planning_show', array('id' => $planning->getId()));
        }

        $plannings = $em->getRepository('LooninsNeguitBundle:Planning')->findAll();

        return $this->render('LooninsNeguitBundle:Default:index.html.twig', array(
            'plannings' => $plannings,
            'form' => $form->createView(),
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

            return $this->redirectToRoute('planning_show', array('id' => $planning->getId()));
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
     * @Route("/{id}/edit", name="planning_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Planning $planning)
    {
        $deleteForm = $this->createDeleteForm($planning);
        $editForm = $this->createForm('Loonins\NeguitBundle\Form\PlanningType', $planning);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($planning);
            $em->flush();

            return $this->redirectToRoute('planning_edit', array('id' => $planning->getId()));
        }

        return $this->render('planning/edit.html.twig', array(
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
