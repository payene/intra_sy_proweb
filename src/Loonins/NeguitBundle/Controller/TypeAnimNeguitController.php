<?php

namespace Loonins\NeguitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\NeguitBundle\Entity\TypeAnimNeguit;
use Loonins\NeguitBundle\Form\TypeAnimNeguitType;

/**
 * TypeAnimNeguit controller.
 *
 * @Route("/neguit/typeanimneguit")
 */
class TypeAnimNeguitController extends Controller
{
    /**
     * Lists all TypeAnimNeguit entities.
     *
     * @Route("/", name="typeanimneguit_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $typeAnimNeguits = $em->getRepository('LooninsNeguitBundle:TypeAnimNeguit')->findAll();

        return $this->render('LooninsNeguitBundle:typeanimneguit:index.html.twig', array(
            'typeAnimNeguits' => $typeAnimNeguits,
        ));
    }

    /**
     * Creates a new TypeAnimNeguit entity.
     *
     * @Route("/new", name="typeanimneguit_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $typeAnimNeguit = new TypeAnimNeguit();
        $form = $this->createForm('Loonins\NeguitBundle\Form\TypeAnimNeguitType', $typeAnimNeguit);
        $form->handleRequest($request);
        $typeAnimNeguits = $em->getRepository('LooninsNeguitBundle:TypeAnimNeguit')->findBy(['del' =>0], ['libelle' => 'ASC']);

        if ($form->isSubmitted() && $form->isValid()) {
            $typeAnimNeguit->setCreatedAt(new \DateTime());
            $typeAnimNeguit->setDel(0);
            $em->persist($typeAnimNeguit);
            $em->flush();

            return $this->redirectToRoute('typeanimneguit_new');
        }

        return $this->render('LooninsNeguitBundle:typeanimneguit:new.html.twig', array(
            'typeAnimNeguit' => $typeAnimNeguit,
            'typeAnimNeguits' => $typeAnimNeguits,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TypeAnimNeguit entity.
     *
     * @Route("/{id}", name="typeanimneguit_show")
     * @Method("GET")
     */
    public function showAction(TypeAnimNeguit $typeAnimNeguit)
    {
        $deleteForm = $this->createDeleteForm($typeAnimNeguit);

        return $this->render('LooninsNeguitBundle:typeanimneguit:show.html.twig', array(
            'typeAnimNeguit' => $typeAnimNeguit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TypeAnimNeguit entity.
     *
     * @Route("/{id}/edit", name="typeanimneguit_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeAnimNeguit $typeAnimNeguit)
    {
        $deleteForm = $this->createDeleteForm($typeAnimNeguit);
        $editForm = $this->createForm('Loonins\NeguitBundle\Form\TypeAnimNeguitType', $typeAnimNeguit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeAnimNeguit);
            $em->flush();

            return $this->redirectToRoute('typeanimneguit_edit', array('id' => $typeAnimNeguit->getId()));
        }

        return $this->render('LooninsNeguitBundle:typeanimneguit:edit.html.twig', array(
            'typeAnimNeguit' => $typeAnimNeguit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a TypeAnimNeguit entity.
     *
     * @Route("/{id}", name="typeanimneguit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypeAnimNeguit $typeAnimNeguit)
    {
        $form = $this->createDeleteForm($typeAnimNeguit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeAnimNeguit);
            $em->flush();
        }

        return $this->redirectToRoute('typeanimneguit_index');
    }

    /**
     * Creates a form to delete a TypeAnimNeguit entity.
     *
     * @param TypeAnimNeguit $typeAnimNeguit The TypeAnimNeguit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeAnimNeguit $typeAnimNeguit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typeanimneguit_delete', array('id' => $typeAnimNeguit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
