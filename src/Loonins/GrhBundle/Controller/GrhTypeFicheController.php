<?php

namespace Loonins\GrhBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\GrhBundle\Entity\GrhTypeFiche;
use Loonins\GrhBundle\Form\GrhTypeFicheType;
use FOS\UserBundle\Util\LegacyFormHelper;


/**
 * GrhTypeFiche controller.
 *
 * @Route("/grhtypefiche")
 */
class GrhTypeFicheController extends Controller {

    /**
     * Lists all GrhTypeFiche entities.
     *
     * @Route("/", name="grhtypefiche")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsGrhBundle:GrhTypeFiche')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new GrhTypeFiche entity.
     *
     * @Route("/", name="grhtypefiche_create")
     * @Method("POST")
     * @Template("LooninsGrhBundle:GrhTypeFiche:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new GrhTypeFiche();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('grhtypefiche'));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a GrhTypeFiche entity.
     *
     * @param GrhTypeFiche $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(GrhTypeFiche $entity) {
        $form = $this->createForm('Loonins\GrhBundle\Form\GrhTypeFicheType', $entity, array(
            'action' => $this->generateUrl('grhtypefiche_create'),
            'method' => 'POST',
        ));

        $form->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Displays a form to create a new GrhTypeFiche entity.
     *
     * @Route("/new", name="grhtypefiche_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new GrhTypeFiche();
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a GrhTypeFiche entity.
     *
     * @Route("/{id}", name="grhtypefiche_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhTypeFiche')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhTypeFiche entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing GrhTypeFiche entity.
     *
     * @Route("/{id}/edit", name="grhtypefiche_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhTypeFiche')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhTypeFiche entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Creates a form to edit a GrhTypeFiche entity.
     *
     * @param GrhTypeFiche $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(GrhTypeFiche $entity) {
        $form = $this->createForm('Loonins\GrhBundle\Form\GrhTypeFicheType', $entity, array(
            'action' => $this->generateUrl('grhtypefiche_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer les modifications'));

        return $form;
    }

    /**
     * Edits an existing GrhTypeFiche entity.
     *
     * @Route("/{id}", name="grhtypefiche_update")
     * @Method("PUT")
     * @Template("LooninsGrhBundle:GrhTypeFiche:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhTypeFiche')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhTypeFiche entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('grhtypefiche', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a GrhTypeFiche entity.
     *
     * @Route("/{id}", name="grhtypefiche_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsGrhBundle:GrhTypeFiche')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GrhTypeFiche entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('grhtypefiche'));
    }

    /**
     * Creates a form to delete a GrhTypeFiche entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('grhtypefiche_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Supprimer ce type de fiche salarie','attr' =>array('class'=> 'btn btn-danger')))
                        ->getForm()
        ;
    }

}
