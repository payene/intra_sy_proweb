<?php

namespace Loonins\SuiviBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Loonins\SuiviBundle\Entity\Journal;
use Loonins\SuiviBundle\Form\JournalType;
use Symfony\Component\HttpFoundation\Response;

/**
 * Journal controller.
 *
 * @Route("/journal")
 */
class JournalController extends Controller
{

    /**
     *End Journal estatistiuqe.
     *
     * @Route("/end/{stat}", name="tech_end")
     * @Method("GET")
     * @Template()
     */
    public function endAction($stat)
    {
        $em = $this->getDoctrine()->getManager();
        $dstat = new \DateTime($stat);
        $journal = $em->getRepository('LooninsSuiviBundle:Journal')->findOneBy(['dateStat'=>$dstat]);
        $journal->setDateFinModif(new \DateTime());
        // var_dump($journal);
        $em->merge($journal);
        $em->flush();

        return $this->redirect($this->generateUrl('stat_show', array('table'=> $journal->getTypeTable()->getId(), 'id'=>$stat)));
        // die();
        // return array(
        //     'entities' => $entities,
        // );
    }

    /**
     * Lists all Journal entities.
     *
     * @Route("/", name="journal")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsSuiviBundle:Journal')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Lists all Journal entities.
     *
     * @Route("/export", name="journal_export")
     * @Method("GET")
     * @Template()
     */
    public function exportAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('LooninsSuiviBundle:Journal')->findAll();
        $str = "Utilisateur;Action;Business Date;Debut intervention; Fin intervention \n";
        foreach ($entities as $key => $entity) {
            $str .= $entity->getAuteur()->getUsername() .";".
            $entity->getLigneStat()->getType() .";".
            $entity->getLigneStat()->getDateStat()->format('Ymd') .";" .
            $entity->getDateModif()->format('d/m/Y H:i:s') .";";
            if(!empty($entity->getDateFinModif())){
                $str .= $entity->getDateFinModif()->format('d/m/Y H:i:s');
            }
            $str .= "\n";
        }
        $response = new Response($str);
        $response->headers->set('Content-Type', 'application/xls');
        return $response;

    }


    /**
     * Creates a new Journal entity.
     *
     * @Route("/", name="journal_create")
     * @Method("POST")
     * @Template("LooninsSuiviBundle:Journal:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Journal();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('journal_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
    * Creates a form to create a Journal entity.
    *
    * @param Journal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createCreateForm(Journal $entity)
    {
        $form = $this->createForm(new JournalType(), $entity, array(
            'action' => $this->generateUrl('journal_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Journal entity.
     *
     * @Route("/new", name="journal_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Journal();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Journal entity.
     *
     * @Route("/{id}", name="journal_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Journal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Journal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Journal entity.
     *
     * @Route("/{id}/edit", name="journal_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Journal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Journal entity.');
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
    * Creates a form to edit a Journal entity.
    *
    * @param Journal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Journal $entity)
    {
        $form = $this->createForm(new JournalType(), $entity, array(
            'action' => $this->generateUrl('journal_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Journal entity.
     *
     * @Route("/{id}", name="journal_update")
     * @Method("PUT")
     * @Template("LooninsSuiviBundle:Journal:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('LooninsSuiviBundle:Journal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Journal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('journal_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Journal entity.
     *
     * @Route("/{id}", name="journal_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('LooninsSuiviBundle:Journal')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Journal entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('journal'));
    }

    /**
     * Creates a form to delete a Journal entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('journal_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
