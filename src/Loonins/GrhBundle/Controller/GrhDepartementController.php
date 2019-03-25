<?php

namespace Loonins\GrhBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\GrhBundle\Entity\GrhDepartement;
use Loonins\GrhBundle\Form\GrhDepartementType;
use FOS\UserBundle\Util\LegacyFormHelper;

/**
 * GrhDepartement controller.
 *
 * @Route("/grhdepartement")
 */
class GrhDepartementController extends Controller
{

    /**
     * Lists all GrhDepartement entities.
     *
     * @Route("/", name="grhdepartement")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsGrhBundle:GrhDepartement')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new GrhDepartement entity.
     *
     * @Route("/", name="grhdepartement_create")
     * @Method("POST")
     * @Template("LooninsGrhBundle:GrhDepartement:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new GrhDepartement();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('grhdepartement_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a GrhDepartement entity.
    *
    * @param GrhDepartement $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(GrhDepartement $entity)
    {
        $form = $this->createForm('Loonins\GrhBundle\Form\GrhDepartementType', $entity, array(
            'action' => $this->generateUrl('grhdepartement_create'),
            'method' => 'POST',
        ));

        $form->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Displays a form to create a new GrhDepartement entity.
     *
     * @Route("/new", name="grhdepartement_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new GrhDepartement();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a GrhDepartement entity.
     *
     * @Route("/{id}", name="grhdepartement_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhDepartement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhDepartement entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing GrhDepartement entity.
     *
     * @Route("/{id}/edit", name="grhdepartement_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhDepartement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhDepartement entity.');
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
    * Creates a form to edit a GrhDepartement entity.
    *
    * @param GrhDepartement $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(GrhDepartement $entity)
    {
        $form = $this->createForm('Loonins\GrhBundle\Form\GrhDepartementType', $entity, array(
            'action' => $this->generateUrl('grhdepartement_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer les modifications'));

        return $form;
    }
    /**
     * Edits an existing GrhDepartement entity.
     *
     * @Route("/{id}", name="grhdepartement_update")
     * @Method("PUT")
     * @Template("LooninsGrhBundle:GrhDepartement:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhDepartement')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhDepartement entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('grhdepartement_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a GrhDepartement entity.
     *
     * @Route("/{id}", name="grhdepartement_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsGrhBundle:GrhDepartement')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GrhDepartement entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('grhdepartement'));
    }

    /**
     * Creates a form to delete a GrhDepartement entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grhdepartement_delete', array('id' => $id)))
//            ->setMethod('')
            ->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Supprimer '))
            ->getForm()
        ;
    }
}
