<?php

namespace Loonins\NeguitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\NeguitBundle\Entity\LoginAnimNeguit;
use Loonins\NeguitBundle\Form\LoginAnimNeguitType;

/**
 * LoginAnimNeguit controller.
 *
 * @Route("/pseudo")
 */
class LoginAnimNeguitController extends Controller
{
    /**
     * Lists all LoginAnimNeguit entities.
     *
     * @Route("/", name="pseudo_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $loginAnimNeguits = $em->getRepository('LooninsNeguitBundle:LoginAnimNeguit')->findAll();

        return $this->render('loginanimneguit/index.html.twig', array(
            'loginAnimNeguits' => $loginAnimNeguits,
        ));
    }

    /**
     * Creates a new LoginAnimNeguit entity.
     *
     * @Route("/new", name="pseudo_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $loginAnimNeguit = new LoginAnimNeguit();
        $form = $this->createForm('Loonins\NeguitBundle\Form\LoginAnimNeguitType', $loginAnimNeguit);
        $form->handleRequest($request);
        $today = new \DateTime();
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $loginAnimNeguit->setCreatedAt($today);
            $loginAnimNeguit->setDel(0);
            $em->persist($loginAnimNeguit);
            $em->flush();

            return $this->redirectToRoute('pseudo_new');
        }

        return $this->render('LooninsNeguitBundle:loginanimneguit:new.html.twig', array(
            'loginAnimNeguit' => $loginAnimNeguit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a LoginAnimNeguit entity.
     *
     * @Route("/{id}", name="pseudo_show")
     * @Method("GET")
     */
    public function showAction(LoginAnimNeguit $loginAnimNeguit)
    {
        $deleteForm = $this->createDeleteForm($loginAnimNeguit);

        return $this->render('loginanimneguit/show.html.twig', array(
            'loginAnimNeguit' => $loginAnimNeguit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing LoginAnimNeguit entity.
     *
     * @Route("/{id}/edit", name="pseudo_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, LoginAnimNeguit $loginAnimNeguit)
    {
        $deleteForm = $this->createDeleteForm($loginAnimNeguit);
        $editForm = $this->createForm('Loonins\NeguitBundle\Form\LoginAnimNeguitType', $loginAnimNeguit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($loginAnimNeguit);
            $em->flush();

            return $this->redirectToRoute('pseudo_edit', array('id' => $loginAnimNeguit->getId()));
        }

        return $this->render('loginanimneguit/edit.html.twig', array(
            'loginAnimNeguit' => $loginAnimNeguit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a LoginAnimNeguit entity.
     *
     * @Route("/{id}", name="pseudo_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, LoginAnimNeguit $loginAnimNeguit)
    {
        $form = $this->createDeleteForm($loginAnimNeguit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($loginAnimNeguit);
            $em->flush();
        }

        return $this->redirectToRoute('pseudo_index');
    }

    /**
     * Creates a form to delete a LoginAnimNeguit entity.
     *
     * @param LoginAnimNeguit $loginAnimNeguit The LoginAnimNeguit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(LoginAnimNeguit $loginAnimNeguit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pseudo_delete', array('id' => $loginAnimNeguit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
