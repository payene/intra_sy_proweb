<?php

namespace Loonins\WikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\WikiBundle\Entity\Rubrique;
use Loonins\WikiBundle\Form\RubriqueType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Rubrique controller.
 *
 * @Route("/rubrique")
 */
class RubriqueController extends Controller {

    /**
     * Lists all Rubrique entities.
     *
     * @Route("/", name="rubrique")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsWikiBundle:Rubrique')->findBy(array(), array('titre'=>'ASC'));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Rubrique entity.
     *
     * @Route("/", name="rubrique_create")
     * @Method("POST")
     * @Template("LooninsWikiBundle:Rubrique:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new Rubrique();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity->setRubDate(new \DateTime());
            $entity->setRubCreator($this->getUser());
//            $listRub = $entity->getRubCat();
//            var_dump($listRub);
//            $listRub->add($entity);
//            $entity->getRubCat()->setRubriques($listRub);
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('rubrique_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Rubrique entity.
     *
     * @param Rubrique $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Rubrique $entity) {
        $form = $this->createForm('Loonins\WikiBundle\Form\RubriqueType', $entity, array(
            'action' => $this->generateUrl('rubrique_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Créer'));

        return $form;
    }

    /**
     * Displays a form to create a new Rubrique entity.
     *
     * @Route("/new", name="rubrique_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new Rubrique();
        $user = $this->getUser();
        $entity->setRubCreator($user);
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Rubrique entity.
     *
     * @Route("/{id}", name="rubrique_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsWikiBundle:Rubrique')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rubrique entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Rubrique entity.
     *
     * @Route("/{id}/edit", name="rubrique_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsWikiBundle:Rubrique')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rubrique entity.');
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
     * Creates a form to edit a Rubrique entity.
     *
     * @param Rubrique $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Rubrique $entity) {
        $form = $this->createForm('Loonins\WikiBundle\Form\RubriqueType', $entity, array(
            'action' => $this->generateUrl('rubrique_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Enregistrer les modifications'));

        return $form;
    }

    /**
     * Edits an existing Rubrique entity.
     *
     * @Route("/{id}", name="rubrique_update")
     * @Method("PUT")
     * @Template("LooninsWikiBundle:Rubrique:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsWikiBundle:Rubrique')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Rubrique entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('rubrique_show', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Rubrique entity.
     *
     * @Route("/{id}", name="rubrique_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsWikiBundle:Rubrique')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Rubrique entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('rubrique'));
    }

    /**
     * Creates a form to delete a Rubrique entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('rubrique_delete', array('id' => $id)))
//            ->setMethod('DELETE')
                        ->add('submit', SubmitType::class, array('label' => 'Supprimer la rubrique courante'))
                        ->getForm()
        ;
    }

}
