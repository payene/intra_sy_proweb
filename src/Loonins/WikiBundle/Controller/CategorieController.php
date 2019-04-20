<?php

namespace Loonins\WikiBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\WikiBundle\Entity\Categorie;
use Loonins\WikiBundle\Entity\Rubrique;
use Loonins\WikiBundle\Form\CategorieType;
use Loonins\WikiBundle\Entity\User as WUser;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Categorie controller.
 *
 * @Route("/categorie")
 */
class CategorieController extends Controller {

    /**
     * Lists all Categorie entities.
     *
     * @Route("/", name="categorie")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsWikiBundle:Categorie')->findBy(array(), array('cat'=>'ASC'));

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Creates a new Categorie entity.
     *
     * @Route("categorie/create", name="categorie_create")
     * @Method("POST")
     * @Template("LooninsWikiBundle:Categorie:new.html.twig")
     */
    public function createAction(Request $request) {
        $entity = new Categorie();
//        $entity->setCatDate(new \DateTime());
        $form = $this->createCreateForm($entity);
        //$request = $this->get('request');
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            //$em->persist($entity);

            $rub = new Rubrique();
            $rub->setTitre('Proweb');
            $rub->setRubCreator($this->getUser());
            $rub->setRubCat($entity);
            $entity->addRubrique($rub);
            $entity->setCatDate(new \DateTime());
            
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('categorie_show', array('id' => $entity->getId())));
        } else {
            
        }
        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Categorie entity.
     *
     * @param Categorie $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Categorie $entity) {
        $form = $this->createForm('Loonins\WikiBundle\Form\CategorieType', $entity, array(
            'action' => $this->generateUrl('categorie_create'),
            'method' => 'POST',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Creéer'));

        return $form;
    }

    /**
     * Displays a form to create a new Categorie entity.
     *
     * @Route("/new", name="categorie_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $entity = new Categorie();
//        $entity->setCatDate('' . date('Y-m-d H:i:s', time()) . '');
        $user = $this->getUser();
//        $w_user = new WUser();
////        $w_user = $user;
//        $w_user = $user;
        $entity->setCatCreator($user);
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a Categorie entity.
     *
     * @Route("/{id}", name="categorie_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('LooninsWikiBundle:Categorie')->find($id);
        $list = $em->getRepository('LooninsWikiBundle:Rubrique')->findBy(array('rubCat' => $entity));
        foreach ($list as $rub) {
            $entity->addRubrique($rub);
        }
//        var_dump($entity->getRubriques());
//        foreach($list as $rb){
//            echo $rb->getTitre();
//        }
//        die('--');
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Categorie entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Categorie entity.
     *
     * @Route("/{id}/edit", name="categorie_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsWikiBundle:Categorie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Categorie entity.');
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
     * Creates a form to edit a Categorie entity.
     *
     * @param Categorie $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(Categorie $entity) {
        $form = $this->createForm( 'Loonins\WikiBundle\Form\CategorieType', $entity, array(
            'action' => $this->generateUrl('categorie_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', SubmitType::class, array('label' => 'Enregistrer les modifications'));

        return $form;
    }

    /**
     * Edits an existing Categorie entity.
     *
     * @Route("/{id}", name="categorie_update")
     * @Method("PUT")
     * @Template("LooninsWikiBundle:Categorie:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsWikiBundle:Categorie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Categorie entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('categorie_show', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Categorie entity.
     *
     * @Route("/{id}", name="categorie_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsWikiBundle:Categorie')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Categorie entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('categorie'));
    }

    /**
     * Creates a form to delete a Categorie entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('categorie_delete', array('id' => $id)))
                        //->setMethod('DELETE')
                        ->add('submit', SubmitType::class, array('label' => 'Supprimer la catégorie courante'))
                        ->getForm()
        ;
    }

}
