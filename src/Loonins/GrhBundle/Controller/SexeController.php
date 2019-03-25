<?php

namespace Loonins\GrhBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\GrhBundle\Entity\Sexe;
use Loonins\GrhBundle\Form\SexeType;

/**
 * Sexe controller.
 *
 * @Route("/sexe")
 */
class SexeController extends Controller
{

    /**
     * Lists all Sexe entities.
     *
     * @Route("/", name="sexe")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsGrhBundle:Sexe')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Sexe entity.
     *
     * @Route("/", name="sexe_create")
     * @Method("POST")
     * @Template("LooninsGrhBundle:Sexe:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Sexe();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sexe_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Sexe entity.
    *
    * @param Sexe $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Sexe $entity)
    {
        $form = $this->createForm(new SexeType(), $entity, array(
            'action' => $this->generateUrl('sexe_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Sexe entity.
     *
     * @Route("/new", name="sexe_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Sexe();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Sexe entity.
     *
     * @Route("/{id}", name="sexe_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:Sexe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sexe entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Sexe entity.
     *
     * @Route("/{id}/edit", name="sexe_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:Sexe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sexe entity.');
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
    * Creates a form to edit a Sexe entity.
    *
    * @param Sexe $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Sexe $entity)
    {
        $form = $this->createForm(new SexeType(), $entity, array(
            'action' => $this->generateUrl('sexe_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Sexe entity.
     *
     * @Route("/{id}", name="sexe_update")
     * @Method("PUT")
     * @Template("LooninsGrhBundle:Sexe:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:Sexe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sexe entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sexe_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Sexe entity.
     *
     * @Route("/{id}", name="sexe_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsGrhBundle:Sexe')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Sexe entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sexe'));
    }

    /**
     * Creates a form to delete a Sexe entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sexe_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
