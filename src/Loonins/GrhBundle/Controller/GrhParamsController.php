<?php

namespace Loonins\GrhBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\GrhBundle\Entity\GrhParams;
use Loonins\GrhBundle\Form\GrhParamsType;

use FOS\UserBundle\Util\LegacyFormHelper;

/**
 * GrhParams controller.
 *
 * @Route("/grhparams")
 */
class GrhParamsController extends Controller
{

    /**
     * Lists all GrhParams entities.
     *
     * @Route("/", name="grhparams")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsGrhBundle:GrhParams')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new GrhParams entity.
     *
     * @Route("/", name="grhparams_create")
     * @Method("POST")
     * @Template("LooninsGrhBundle:GrhParams:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new GrhParams();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('grhparams_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a GrhParams entity.
    *
    * @param GrhParams $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(GrhParams $entity)
    {
        $form = $this->createForm('Loonins\GrhBundle\Form\GrhParamsType', $entity, array(
            'action' => $this->generateUrl('grhparams_create'),
            'method' => 'POST',
        ));

        $form->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Displays a form to create a new GrhParams entity.
     *
     * @Route("/new", name="grhparams_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new GrhParams();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a GrhParams entity.
     *
     * @Route("/{id}", name="grhparams_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhParams')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhParams entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing GrhParams entity.
     *
     * @Route("/{id}/edit", name="grhparams_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhParams')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhParams entity.');
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
    * Creates a form to edit a GrhParams entity.
    *
    * @param GrhParams $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(GrhParams $entity)
    {
        $form = $this->createForm('Loonins\GrhBundle\Form\GrhParamsType', $entity, array(
            'action' => $this->generateUrl('grhparams_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer les modifications'));

        return $form;
    }
    /**
     * Edits an existing GrhParams entity.
     *
     * @Route("/{id}", name="grhparams_update")
     * @Method("PUT")
     * @Template("LooninsGrhBundle:GrhParams:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhParams')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhParams entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('grhparams_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a GrhParams entity.
     *
     * @Route("/{id}", name="grhparams_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsGrhBundle:GrhParams')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GrhParams entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('grhparams'));
    }

    /**
     * Creates a form to delete a GrhParams entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('grhparams_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit',LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Supprimer'))
            ->getForm()
        ;
    }
}
