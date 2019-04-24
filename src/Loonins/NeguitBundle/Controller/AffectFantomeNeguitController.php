<?php

namespace Loonins\NeguitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\NeguitBundle\Entity\AffectFantomeNeguit;
use Loonins\NeguitBundle\Form\AffectFantomeNeguitType;

/**
 * AffectFantomeNeguit controller.
 *
 * @Route("/affectfantomeneguit")
 */
class AffectFantomeNeguitController extends Controller
{
    /**
     * Lists all AffectFantomeNeguit entities.
     *
     * @Route("/", name="affectfantomeneguit_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $affectFantomeNeguits = $em->getRepository('LooninsNeguitBundle:AffectFantomeNeguit')->findAll();

        return $this->render('LooninsNeguitBundle:affectfantomeneguit:index.html.twig', array(
            'affectFantomeNeguits' => $affectFantomeNeguits,
        ));
    }

    /**
     * Creates a new AffectFantomeNeguit entity.
     *
     * @Route("/new", name="affectfantomeneguit_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $affectFantomeNeguit = new AffectFantomeNeguit();
        $form = $this->createForm('Loonins\NeguitBundle\Form\AffectFantomeNeguitType', $affectFantomeNeguit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($affectFantomeNeguit);
            $em->flush();

            return $this->redirectToRoute('affectfantomeneguit_show', array('id' => $affectFantomeNeguit->getId()));
        }

        return $this->render('LooninsNeguitBundle:affectfantomeneguit:new.html.twig', array(
            'affectFantomeNeguit' => $affectFantomeNeguit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AffectFantomeNeguit entity.
     *
     * @Route("/{id}", name="affectfantomeneguit_show")
     * @Method("GET")
     */
    public function showAction(AffectFantomeNeguit $affectFantomeNeguit)
    {
        $deleteForm = $this->createDeleteForm($affectFantomeNeguit);

        return $this->render('LooninsNeguitBundle:affectfantomeneguit:show.html.twig', array(
            'affectFantomeNeguit' => $affectFantomeNeguit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AffectFantomeNeguit entity.
     *
     * @Route("/{id}/edit", name="affectfantomeneguit_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AffectFantomeNeguit $affectFantomeNeguit)
    {
        $deleteForm = $this->createDeleteForm($affectFantomeNeguit);
        $editForm = $this->createForm('Loonins\NeguitBundle\Form\AffectFantomeNeguitType', $affectFantomeNeguit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($affectFantomeNeguit);
            $em->flush();

            return $this->redirectToRoute('affectfantomeneguit_edit', array('id' => $affectFantomeNeguit->getId()));
        }

        return $this->render('LooninsNeguitBundle:affectfantomeneguit:edit.html.twig', array(
            'affectFantomeNeguit' => $affectFantomeNeguit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AffectFantomeNeguit entity.
     *
     * @Route("/{id}", name="affectfantomeneguit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AffectFantomeNeguit $affectFantomeNeguit)
    {
        $form = $this->createDeleteForm($affectFantomeNeguit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($affectFantomeNeguit);
            $em->flush();
        }

        return $this->redirectToRoute('affectfantomeneguit_index');
    }

    /**
     * Creates a form to delete a AffectFantomeNeguit entity.
     *
     * @param AffectFantomeNeguit $affectFantomeNeguit The AffectFantomeNeguit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AffectFantomeNeguit $affectFantomeNeguit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('affectfantomeneguit_delete', array('id' => $affectFantomeNeguit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
