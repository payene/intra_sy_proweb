<?php

namespace Loonins\GrhBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\GrhBundle\Entity\GrhFicheSalarie;
use Loonins\GrhBundle\Form\GrhFicheSalarieType;
use FOS\UserBundle\Util\LegacyFormHelper;

/**
 * GrhFicheSalarie controller.
 *
 * @Route("/grhfichesalarie")
 */
class GrhFicheSalarieController extends Controller {

    /**
     * Lists all GrhFicheSalarie entities.
     *
     * @Route("/", name="grhfichesalarie")
     * @Method("GET")
     * @Template()
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsGrhBundle:GrhFicheSalarie')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Lists all GrhFicheSalarie entities.
     *
     * @Route("/fichesalarie/show/fiche/{id}", name="grhfichesalarie_show")
     * @Method("GET")
     * @Template()
     */
    public function ficheAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhFicheSalarie')->find($id);
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Fiche Salarie entity.');
        }
        return array(
            'entity' => $entity,
        );
    }

    /**
     * Creates a new GrhFicheSalarie entity.
     *
     * @Route("/", name="grhfichesalarie_create")
     * @Method("POST")
     * @Template("LooninsGrhBundle:GrhFicheSalarie:new.html.twig")
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $entity = new GrhFicheSalarie();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        $id = $this->get('session')->get('employe_id');
        if (!$employe = $em->getRepository('LooninsGrhBundle:GrhEmployes')->find($id)) {
            throw $this->createNotFoundException('Unable to find Employe entity.');
        }
        if ($form->isValid()) {

            $entity->setEmploye($employe);
            $entity->setDate(new \DateTime);
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('grhfichesalarie_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form' => $form->createView(),
        );
    }

    /**
     * Creates a form to create a GrhFicheSalarie entity.
     *
     * @param GrhFicheSalarie $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(GrhFicheSalarie $entity) {
        $form = $this->createForm('Loonins\GrhBundle\Form\GrhFicheSalarieType', $entity, array(
            'action' => $this->generateUrl('grhfichesalarie_create'),
            'method' => 'POST',
        ));

        $form->add('submit', LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('label' => 'Enregistrer', 'attr' => array('class' => 'btn btn-success')));

        return $form;
    }

    /**
     * Displays a form to create a new GrhFicheSalarie entity.
     *
     * @Route("/new", name="grhfichesalarie_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction() {
        $session = $this->get('session');
        $id = $session->get('employe_id');
        // var_dump($id);
        // die($id);

        $em = $this->getDoctrine()->getManager();
        $employe = $em->getRepository('LooninsGrhBundle:GrhEmployes')->find($id);
        if (!$employe) {
            throw $this->createNotFoundException('Unable to find Employe entity.');
        }

        $entity = new GrhFicheSalarie();
        $entity->setEmploye($employe);
        $form = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'employe' => $employe,
            'form' => $form->createView(),
        );
    }

    /**
     * Finds and displays a GrhFicheSalarie entity.
     *
     * @Route("/{id}", name="grhfichesalarie_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id) {
        $em = $this->getDoctrine()->getManager();
        $tab = array();
        $employe = $em->getRepository('LooninsGrhBundle:GrhEmployes')->find($id);
        $type_fiches = $em->getRepository('LooninsGrhBundle:GrhTypeFiche')->findAll();
//        var_dump($employe);
//        die();

        if (empty($employe)) {
            throw $this->createNotFoundException('Employé introuvable !');
        }
        $session = $this->get('session');
        $session->set('employe_id',$id);

        foreach ($type_fiches as $type) {
            $tab[$type->getId()] = $em->getRepository('LooninsGrhBundle:GrhFicheSalarie')->findBy(["employe" => $id, "type" => $type->getId()], ['date' => 'DESC']);
        }
        $deleteForm = $this->createDeleteForm($id);
//        var_dump($tab);
//        exit();
        return array(
            'fiches' => $tab,
            'type_fiche' => $type_fiches,
            'employe' => $employe,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing GrhFicheSalarie entity.
     *
     * @Route("/{id}/edit", name="grhfichesalarie_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhFicheSalarie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhFicheSalarie entity.');
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
     * Creates a form to edit a GrhFicheSalarie entity.
     *
     * @param GrhFicheSalarie $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(GrhFicheSalarie $entity) {
        $form = $this->createForm('Loonins\GrhBundle\Form\GrhFicheSalarieType', $entity, array(
            'action' => $this->generateUrl('grhfichesalarie_update', array('id' => $entity->getId())),
            'method' => 'POST',
        ));

        $form->add('submit',  LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('attr' => array('class' => 'btn btn-warning'), 'label' => 'Enregistrer les modifications'));

        return $form;
    }

    /**
     * Edits an existing GrhFicheSalarie entity.
     *
     * @Route("/{id}", name="grhfichesalarie_update")
     * @Method("PUT")
     * @Template("LooninsGrhBundle:GrhFicheSalarie:edit.html.twig")
     */
    public function updateAction(Request $request, $id) {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsGrhBundle:GrhFicheSalarie')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find GrhFicheSalarie entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);
        
//         echo $editForm->isSubmitted();
//        echo $editForm->getErrorsAsString();
//        
//        die("-");
        
        if ($editForm->isValid()) {
            $em->flush();
            return $this->redirect($this->generateUrl('grhfichesalarie_show', array('id' => $entity->getId())));
        }
        
       

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a GrhFicheSalarie entity.
     *
     * @Route("/{id}", name="grhfichesalarie_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id) {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsGrhBundle:GrhFicheSalarie')->find($id);
            
            if (!$entity) {
                throw $this->createNotFoundException('Unable to find GrhFicheSalarie entity.');
            }
            $employeId = $entity->getEmploye()->getId();
            $em->remove($entity);
            $em->flush();
            return $this->redirect($this->generateUrl('grhemployes_fiche', array('id' => $employeId)));
        }



        return $this->redirect($this->generateUrl('grhfichesalarie'));
    }

    /**
     * Creates a form to delete a GrhFicheSalarie entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('grhfichesalarie_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->add('submit',  LegacyFormHelper::getType('Symfony\Component\Form\Extension\Core\Type\SubmitType'), array('attr' => ['class' => 'btn btn-danger'], 'label' => 'Supprimer'))
                        ->getForm()
        ;
    }

}
