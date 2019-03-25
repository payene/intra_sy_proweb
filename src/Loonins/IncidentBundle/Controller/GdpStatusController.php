<?php

namespace Loonins\IncidentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\IncidentBundle\Entity\GdpStatus;
use Loonins\IncidentBundle\Form\GdpStatusType;

/**
 * GdpStatus controller.
 *
 * @Route("/gdpstatus")
 */
class GdpStatusController extends Controller
{

    /**
     * Lists all GdpStatus entities.
     *
     * @Route("/", name="gdpstatus")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsIncidentBundle:GdpStatus')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new GdpStatus entity.
     *
     * @Route("/", name="gdpstatus_create")
     * @Method("POST")
     * @Template("LooninsIncidentBundle:GdpStatus:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new GdpStatus();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('gdpstatus_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a GdpStatus entity.
    *
    * @param GdpStatus $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(GdpStatus $entity)
    {
        $form = $this->createForm(new GdpStatusType(), $entity, array(
            'action' => $this->generateUrl('gdpstatus_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new GdpStatus entity.
     *
     * @Route("/new", name="gdpstatus_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new GdpStatus();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a GdpStatus entity.
     *
     * @Route("/{id}", name="gdpstatus_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:GdpStatus')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GdpStatus entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing GdpStatus entity.
     *
     * @Route("/{id}/edit", name="gdpstatus_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:GdpStatus')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GdpStatus entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a GdpStatus entity.
    *
    * @param GdpStatus $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(GdpStatus $entity)
    {
        $form = $this->createForm(new GdpStatusType(), $entity, array(
            'action' => $this->generateUrl('gdpstatus_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing GdpStatus entity.
     *
     * @Route("/{id}", name="gdpstatus_update")
     * @Method("PUT")
     * @Template("LooninsIncidentBundle:GdpStatus:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:GdpStatus')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GdpStatus entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('gdpstatus_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a GdpStatus entity.
     *
     * @Route("/{id}", name="gdpstatus_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsIncidentBundle:GdpStatus')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GdpStatus entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('gdpstatus'));
    }

    /**
     * Creates a form to delete a GdpStatus entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gdpstatus_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
