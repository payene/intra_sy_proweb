<?php

namespace Loonins\NeguitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\NeguitBundle\Entity\ProfilVirtuel;
use Loonins\NeguitBundle\Form\ProfilVirtuelType;

/**
 * ProfilVirtuel controller.
 *
 * @Route("/neguit/pv")
 */
class ProfilVirtuelController extends Controller
{
    /**
     * Lists all ProfilVirtuel entities.
     *
     * @Route("/", name="pv_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $profilVirtuels = $em->getRepository('LooninsNeguitBundle:ProfilVirtuel')->findAll();

        return $this->render('LooninsNeguitBundle:profilvirtuel:index.html.twig', array(
            'profilVirtuels' => $profilVirtuels,
        ));
    }

    /**
     * Creates a new ProfilVirtuel entity.
     *
     * @Route("/new", name="pv_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $profilVirtuel = new ProfilVirtuel();
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm('Loonins\NeguitBundle\Form\ProfilVirtuelType', $profilVirtuel);
        $form->handleRequest($request);
        $pseudoArray = $em->getRepository('LooninsNeguitBundle:ProfilVirtuel')->findBy(['del' =>0], ['pseudo' => 'ASC']);
        if ($form->isSubmitted() && $form->isValid()) {
            $profilVirtuel->setCreatedAt(new \DateTime());
            $profilVirtuel->setDel(0);
            $em->persist($profilVirtuel);
            $em->flush();
            $this->get('session')->getFlashBag()->add('success', 'Login crée avec succes');
            return $this->redirectToRoute('pv_new');
        }

        return $this->render('LooninsNeguitBundle:profilvirtuel:new.html.twig', array(
            'profilVirtuel' => $profilVirtuel,
            'form' => $form->createView(),
            'pseudoArray' => $pseudoArray
        ));
    }

    /**
     * Finds and displays a ProfilVirtuel entity.
     *
     * @Route("/{id}", name="pv_show")
     * @Method("GET")
     */
    public function showAction(ProfilVirtuel $profilVirtuel)
    {
        $deleteForm = $this->createDeleteForm($profilVirtuel);

        return $this->render('profilvirtuel/show.html.twig', array(
            'profilVirtuel' => $profilVirtuel,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ProfilVirtuel entity.
     *
     * @Route("/{id}/edit", name="pv_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, ProfilVirtuel $profilVirtuel)
    {
        $deleteForm = $this->createDeleteForm($profilVirtuel);
        $editForm = $this->createForm('Loonins\NeguitBundle\Form\ProfilVirtuelType', $profilVirtuel);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($profilVirtuel);
            $em->flush();

            return $this->redirectToRoute('pv_edit', array('id' => $profilVirtuel->getId()));
        }

        return $this->render('profilvirtuel/edit.html.twig', array(
            'profilVirtuel' => $profilVirtuel,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ProfilVirtuel entity.
     *
     * @Route("/{id}", name="pv_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, ProfilVirtuel $profilVirtuel)
    {
        $form = $this->createDeleteForm($profilVirtuel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($profilVirtuel);
            $em->flush();
        }

        return $this->redirectToRoute('pv_index');
    }

    /**
     * Creates a form to delete a ProfilVirtuel entity.
     *
     * @param ProfilVirtuel $profilVirtuel The ProfilVirtuel entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(ProfilVirtuel $profilVirtuel)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('pv_delete', array('id' => $profilVirtuel->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
