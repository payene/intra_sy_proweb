<?php

namespace Loonins\SuiviBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\SuiviBundle\Entity\TypeTable;
use Loonins\SuiviBundle\Entity\AliasActivite;
use Loonins\SuiviBundle\Form\TypeTableType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
/**
 * TypeTable controller.
 *
 * @Route("/typetable")
 */
class TypeTableController extends Controller
{

    /**
     * Lists all TypeTable entities.
     *
     * @Route("/", name="typetable")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsSuiviBundle:TypeTable')->findAll();

        return array(
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'entities' => $entities,
        );
    }
    /**
     * Creates a new TypeTable entity.
     *
     * @Route("/", name="typetable_create")
     * @Method("POST")
     * @Template("LooninsSuiviBundle:TypeTable:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new TypeTable();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Table sauvegardée avec success');
            return $this->redirect($this->generateUrl('typetable'));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a TypeTable entity.
    *
    * @param TypeTable $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(TypeTable $entity)
    {
        $form = $this->createForm('Loonins\SuiviBundle\Form\TypeTableType', $entity, array(
            'action' => $this->generateUrl('typetable_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Enregistrer'));

        return $form;
    }

    /**
     * Displays a form to create a new TypeTable entity.
     *
     * @Route("/new", name="typetable_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new TypeTable();
        $form   = $this->createCreateForm($entity);

        return array(
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * @Template()
     */
    public function aliasAction(TypeTable $type, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        
        $aliasActivite = new AliasActivite();
        $aliasActivite->setType($type);

        $form   = $this->createForm('Loonins\SuiviBundle\Form\AliasActiviteType', $aliasActivite);

        $aliasActivites = $em->getRepository('LooninsSuiviBundle:AliasActivite')->findByType($type);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($aliasActivite);
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Alias sauvegardé avec success');
            return $this->redirect($this->generateUrl('typetable_alias', array('id' => $type->getId())));
        }

        return array(
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'entity' => $type,
            'aliasActivites' => $aliasActivites,
            'form'   => $form->createView(),
        );
    }

    public function aliasDeleteAction(AliasActivite $aliasActivite)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($aliasActivite);
        $em->flush();
        $this->get('session')->getFlashBag()->add('info', 'Alias supprimé avec success');
        return $this->redirect($this->generateUrl('typetable_alias', array('id' => $aliasActivite->getType()->getId())));
    }

    /**
     * Finds and displays a TypeTable entity.
     *
     * @Route("/{id}", name="typetable_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:TypeTable')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeTable entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing TypeTable entity.
     *
     * @Route("/{id}/edit", name="typetable_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:TypeTable')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeTable entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a TypeTable entity.
    *
    * @param TypeTable $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(TypeTable $entity)
    {
        $form = $this->createForm('Loonins\SuiviBundle\Form\TypeTableType', $entity, array(
            'action' => $this->generateUrl('typetable_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Sauvegarder'));

        return $form;
    }
    /**
     * Edits an existing TypeTable entity.
     *
     * @Route("/{id}", name="typetable_update")
     * @Method("POST")
     * @Template("LooninsSuiviBundle:TypeTable:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:TypeTable')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find TypeTable entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();
            $this->get('session')->getFlashBag()->add('info', 'Type table modifiée avec success');
            return $this->redirect($this->generateUrl('typetable_edit', array('id' => $id)));
        }

        return array(
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a TypeTable entity.
     *
     * @Route("/{id}", name="typetable_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsSuiviBundle:TypeTable')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find TypeTable entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('typetable'));
    }

    /**
     * Creates a form to delete a TypeTable entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typetable_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', SubmitType::class, array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
