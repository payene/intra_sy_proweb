<?php

namespace Loonins\IncidentBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\IncidentBundle\Entity\TypeIncident;
use Loonins\IncidentBundle\Form\TypeIncidentType;

use FOS\UserBundle\Util\LegacyFormHelper;

/**
 * TypeIncident controller.
 *
 * @Route("/typeincident")
 */
class TypeIncidentController extends Controller
{

    /**
     * Lists all TypeIncident entities.
     *
     * @Route("/", name="typeincident")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsIncidentBundle:TypeIncident')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new TypeIncident entity.
     *
     * @Route("/", name="typeincident_create")
     * @Method("POST")
     * @Template("LooninsIncidentBundle:TypeIncident:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new TypeIncident();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('typeincident_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a TypeIncident entity.
    *
    * @param TypeIncident $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(TypeIncident $entity)
    {
        $form = $this->createForm('Loonins\IncidentBundle\Form\TypeIncidentType', $entity, array(
            'action' => $this->generateUrl('typeincident_create'),
            'method' => 'POST',
        ));

        $form->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Displays a form to create a new TypeIncident entity.
     *
     * @Route("/new", name="typeincident_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TypeIncident();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a TypeIncident entity.
     *
     * @Route("/{id}", name="typeincident_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:TypeIncident')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeIncident entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TypeIncident entity.
     *
     * @Route("/{id}/edit", name="typeincident_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:TypeIncident')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeIncident entity.');
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
    * Creates a form to edit a TypeIncident entity.
    *
    * @param TypeIncident $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TypeIncident $entity)
    {
        $form = $this->createForm('Loonins\IncidentBundle\Form\TypeIncidentType', $entity, array(
            'action' => $this->generateUrl('typeincident_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing TypeIncident entity.
     *
     * @Route("/{id}", name="typeincident_update")
     * @Method("PUT")
     * @Template("LooninsIncidentBundle:TypeIncident:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsIncidentBundle:TypeIncident')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeIncident entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('typeincident_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a TypeIncident entity.
     *
     * @Route("/{id}", name="typeincident_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsIncidentBundle:TypeIncident')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TypeIncident entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('typeincident'));
    }

    /**
     * Creates a form to delete a TypeIncident entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typeincident_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
