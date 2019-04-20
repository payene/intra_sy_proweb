<?php

namespace Loonins\GrhBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\GrhBundle\Entity\Matrimonial;
use Loonins\GrhBundle\Form\MatrimonialType;

/**
 * Matrimonial controller.
 *
 * @Route("/matrimonial")
 */
class MatrimonialController extends Controller
{

    /**
     * Lists all Matrimonial entities.
     *
     * @Route("/", name="matrimonial")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsGrhBundle:Matrimonial')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Matrimonial entity.
     *
     * @Route("/", name="matrimonial_create")
     * @Method("POST")
     * @Template("LooninsGrhBundle:Matrimonial:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Matrimonial();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('matrimonial_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Matrimonial entity.
    *
    * @param Matrimonial $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Matrimonial $entity)
    {
        $form = $this->createForm(new MatrimonialType(), $entity, array(
            'action' => $this->generateUrl('matrimonial_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Matrimonial entity.
     *
     * @Route("/new", name="matrimonial_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Matrimonial();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Matrimonial entity.
     *
     * @Route("/{id}", name="matrimonial_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:Matrimonial')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Matrimonial entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Matrimonial entity.
     *
     * @Route("/{id}/edit", name="matrimonial_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:Matrimonial')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Matrimonial entity.');
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
    * Creates a form to edit a Matrimonial entity.
    *
    * @param Matrimonial $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Matrimonial $entity)
    {
        $form = $this->createForm(new MatrimonialType(), $entity, array(
            'action' => $this->generateUrl('matrimonial_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Matrimonial entity.
     *
     * @Route("/{id}", name="matrimonial_update")
     * @Method("PUT")
     * @Template("LooninsGrhBundle:Matrimonial:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:Matrimonial')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Matrimonial entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('matrimonial_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Matrimonial entity.
     *
     * @Route("/{id}", name="matrimonial_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsGrhBundle:Matrimonial')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Matrimonial entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('matrimonial'));
    }

    /**
     * Creates a form to delete a Matrimonial entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('matrimonial_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
