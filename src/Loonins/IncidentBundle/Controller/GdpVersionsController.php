<?php

namespace Loonins\IncidentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\IncidentBundle\Entity\GdpVersions;
use Loonins\IncidentBundle\Form\GdpVersionsType;
use FOS\UserBundle\Util\LegacyFormHelper;

/**
 * GdpVersions controller.
 *
 * @Route("/gdpversions")
 */
class GdpVersionsController extends Controller {

    /**
     * Lists all GdpVersions entities.
     *
     * @Route("/", name="gdpversions")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsIncidentBundle:GdpVersions')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new GdpVersions entity.
     *
     * @Route("/", name="gdpversions_create")
     * @Method("POST")
     * @Template("LooninsIncidentBundle:GdpVersions:new.html.twig")
     */
    public function createAction(Request $request, $incid) {
        $entity = new GdpVersions();
        $form = $this->createCreateForm($entity, $incid);
        $form->handleRequest($request);
        $incident = $this->getDoctrine()->getManager()->getRepository('LooninsIncidentBundle:GdpIncident')->find($incid);
        $user = $this->getUser();
        $entity->setIncident($incident);
        $entity->setProprio($user);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $em->refresh($entity);
            return $this->redirect($this->generateUrl('gdpversions_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'incident' => $incident,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a GdpVersions entity.
     *
     * @param GdpVersions $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(GdpVersions $entity, $incid) {
        $form = $this->createForm('Loonins\IncidentBundle\Form\GdpVersionsType', $entity, array(
            'action' => $this->generateUrl('gdpversions_create', array('incid' => $incid)),
            'method' => 'POST',
        ));

        $form->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer l\'évolution'));

        return $form;
    }

    /**
     * Displays a form to create a new GdpVersions entity.
     *
     * @Route("/new", name="gdpversions_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction($incid) {
        $entity = new GdpVersions();
        $form = $this->createCreateForm($entity,$incid);
        $incident = $this->getDoctrine()->getManager()->getRepository('LooninsIncidentBundle:GdpIncident')->find($incid);
        return array(
            'entity' => $entity,
            'incident' => $incident,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a GdpVersions entity.
     *
     * @Route("/{id}", name="gdpversions_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:GdpVersions')->find($id);
        $user = $this->getUser();
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GdpVersions entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing GdpVersions entity.
     *
     * @Route("/{id}/edit", name="gdpversions_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:GdpVersions')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GdpVersions entity.');
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
     * Creates a form to edit a GdpVersions entity.
     *
     * @param GdpVersions $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(GdpVersions $entity) {
        $form = $this->createForm('Loonins\IncidentBundle\Form\GdpVersionsType', $entity, array(
            'action' => $this->generateUrl('gdpversions_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer les modifications'));

        return $form;
    }

    /**
     * Edits an existing GdpVersions entity.
     *
     * @Route("/{id}", name="gdpversions_update")
     * @Method("PUT")
     * @Template("LooninsIncidentBundle:GdpVersions:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:GdpVersions')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GdpVersions entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $em->refresh($entity);
            return $this->redirect($this->generateUrl('gdpversions_show', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a GdpVersions entity.
     *
     * @Route("/{id}", name="gdpversions_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

//        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsIncidentBundle:GdpVersions')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GdpVersions entity.');
            }
            $incident = $entity->getIncident();
            $em->remove($entity);
            $em->flush();
//        }

        return $this->redirect($this->generateUrl('gdpincident_show', array('id'=>$incident)));
    }

    /**
     * Creates a form to delete a GdpVersions entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('gdpversions_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Supprimer l\'évolution courante'))
                        ->getForm()
        ;
    }

}
