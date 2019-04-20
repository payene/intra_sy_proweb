<?php

namespace Loonins\SuiviBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\SuiviBundle\Entity\Animatrice;
use Loonins\SuiviBundle\Form\AnimatriceType;

/**
 * Animatrice controller.
 *
 * @Route("/animatrice")
 */
class AnimatriceController extends Controller
{

    /**
     * Lists all Animatrice entities.
     *
     * @Route("/", name="animatrice")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsSuiviBundle:Animatrice')->findBy(['del'=> 0],['login'=>'asc']);

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Animatrice entity.
     *
     * @Route("/", name="animatrice_create")
     * @Method("POST")
     * @Template("LooninsSuiviBundle:Animatrice:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Animatrice();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setDel(0);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('animatrice'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Animatrice entity.
    *
    * @param Animatrice $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Animatrice $entity)
    {
        $form = $this->createForm(new AnimatriceType(), $entity, array(
            'action' => $this->generateUrl('animatrice_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Displays a form to create a new Animatrice entity.
     *
     * @Route("/new", name="animatrice_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Animatrice();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Animatrice entity.
     *
     * @Route("/{id}", name="animatrice_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Animatrice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Animatrice entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Animatrice entity.
     *
     * @Route("/{id}/edit", name="animatrice_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Animatrice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Animatrice entity.');
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
    * Creates a form to edit a Animatrice entity.
    *
    * @param Animatrice $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Animatrice $entity)
    {
        $form = $this->createForm(new AnimatriceType(), $entity, array(
            'action' => $this->generateUrl('animatrice_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Sauvgarder'));

        return $form;
    }
    /**
     * Edits an existing Animatrice entity.
     *
     * @Route("/{id}", name="animatrice_update")
     * @Method("PUT")
     * @Template("LooninsSuiviBundle:Animatrice:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Animatrice')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Animatrice entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('animatrice_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Animatrice entity.
     *
     * @Route("/{id}", name="animatrice_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsSuiviBundle:Animatrice')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Animatrice entity.');
            }
            $entity->setDel(1);
            $em->merge($entity);
            $em->flush();
            // var_dump($entity);
            // die();
        }

        return $this->redirect($this->generateUrl('animatrice'));
    }

    /**
     * Creates a form to delete a Animatrice entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('animatrice_delete', array('id' => $id)))
            ->setMethod('POST')
            ->add('submit', 'submit', array('label' => 'Je veux supprimer cette animatrice'))
            ->getForm()
        ;
    }
}
