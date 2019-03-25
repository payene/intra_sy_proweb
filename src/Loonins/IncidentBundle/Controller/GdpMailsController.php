<?php

namespace Loonins\IncidentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\IncidentBundle\Entity\GdpMails;
use Loonins\IncidentBundle\Form\GdpMailsType;

/**
 * GdpMails controller.
 *
 * @Route("/gdpmails")
 */
class GdpMailsController extends Controller
{

    /**
     * Lists all GdpMails entities.
     *
     * @Route("/", name="gdpmails")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsIncidentBundle:GdpMails')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new GdpMails entity.
     *
     * @Route("/", name="gdpmails_create")
     * @Method("POST")
     * @Template("LooninsIncidentBundle:GdpMails:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new GdpMails();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('gdpmails_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a GdpMails entity.
    *
    * @param GdpMails $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(GdpMails $entity)
    {
        $form = $this->createForm(new GdpMailsType(), $entity, array(
            'action' => $this->generateUrl('gdpmails_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new GdpMails entity.
     *
     * @Route("/new", name="gdpmails_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new GdpMails();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a GdpMails entity.
     *
     * @Route("/{id}", name="gdpmails_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:GdpMails')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GdpMails entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing GdpMails entity.
     *
     * @Route("/{id}/edit", name="gdpmails_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:GdpMails')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GdpMails entity.');
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
    * Creates a form to edit a GdpMails entity.
    *
    * @param GdpMails $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(GdpMails $entity)
    {
        $form = $this->createForm(new GdpMailsType(), $entity, array(
            'action' => $this->generateUrl('gdpmails_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing GdpMails entity.
     *
     * @Route("/{id}", name="gdpmails_update")
     * @Method("PUT")
     * @Template("LooninsIncidentBundle:GdpMails:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:GdpMails')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GdpMails entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('gdpmails_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a GdpMails entity.
     *
     * @Route("/{id}", name="gdpmails_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsIncidentBundle:GdpMails')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GdpMails entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('gdpmails'));
    }

    /**
     * Creates a form to delete a GdpMails entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('gdpmails_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
