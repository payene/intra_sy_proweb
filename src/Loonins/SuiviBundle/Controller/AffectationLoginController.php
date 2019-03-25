<?php

namespace Loonins\SuiviBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\SuiviBundle\Entity\AffectationLogin;
use Loonins\SuiviBundle\Form\AffectationLoginType;

/**
 * AffectationLogin controller.
 *
 * @Route("/affectationlogin")
 */
class AffectationLoginController extends Controller
{
    /**
     * Lists all AffectationLogin entities.
     *
     * @Route("/", name="affectationlogin_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $affectationLogins = $em->getRepository('LooninsSuiviBundle:AffectationLogin')->findAll();

        return $this->render('affectationlogin/index.html.twig', array(
            'affectationLogins' => $affectationLogins,
        ));
    }

    /**
     * Creates a new AffectationLogin entity.
     *
     * @Route("/new", name="affectationlogin_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $affectationLogin = new AffectationLogin();
        $form = $this->createForm('Loonins\SuiviBundle\Form\AffectationLoginType', $affectationLogin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($affectationLogin);
            $em->flush();

            return $this->redirectToRoute('affectationlogin_show', array('id' => $affectationLogin->getId()));
        }

        return $this->render('affectationlogin/new.html.twig', array(
            'affectationLogin' => $affectationLogin,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AffectationLogin entity.
     *
     * @Route("/{id}", name="affectationlogin_show")
     * @Method("GET")
     */
    public function showAction(AffectationLogin $affectationLogin)
    {
        $deleteForm = $this->createDeleteForm($affectationLogin);

        return $this->render('affectationlogin/show.html.twig', array(
            'affectationLogin' => $affectationLogin,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AffectationLogin entity.
     *
     * @Route("/{id}/edit", name="affectationlogin_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AffectationLogin $affectationLogin)
    {
        $deleteForm = $this->createDeleteForm($affectationLogin);
        $editForm = $this->createForm('Loonins\SuiviBundle\Form\AffectationLoginType', $affectationLogin);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($affectationLogin);
            $em->flush();

            return $this->redirectToRoute('affectationlogin_edit', array('id' => $affectationLogin->getId()));
        }

        return $this->render('affectationlogin/edit.html.twig', array(
            'affectationLogin' => $affectationLogin,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AffectationLogin entity.
     *
     * @Route("/{id}", name="affectationlogin_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AffectationLogin $affectationLogin)
    {
        $form = $this->createDeleteForm($affectationLogin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($affectationLogin);
            $em->flush();
        }

        return $this->redirectToRoute('affectationlogin_index');
    }

    /**
     * Creates a form to delete a AffectationLogin entity.
     *
     * @param AffectationLogin $affectationLogin The AffectationLogin entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AffectationLogin $affectationLogin)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('affectationlogin_delete', array('id' => $affectationLogin->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
