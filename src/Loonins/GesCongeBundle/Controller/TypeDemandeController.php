<?php

namespace Loonins\GesCongeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\GesCongeBundle\Entity\TypeDemande;
use Loonins\GesCongeBundle\Form\TypeDemandeType;
use Symfony\Component\Form\FormError;

/**
 * TypeDemande controller.
 *
 * @Route("/typedemande")
 */
class TypeDemandeController extends Controller
{
    /**
     * 
     *
     * @Route("/", name="typedemande_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {


        $typeDemande = new TypeDemande();

        $form = $this->createForm('Loonins\GesCongeBundle\Form\TypeDemandeType', $typeDemande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repository=$this->getDoctrine()->getRepository('Loonins\GesCongeBundle\Entity\TypeDemande');
            $type= $repository->findOneBy(['libelle'=>$form['libelle']->getData(),'del'=>null]);

            if(empty($type))
            {
                
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeDemande);
            $em->flush();

            return $this->redirectToRoute('typedemande_index');

            }else
            {   
                $form->addError(new FormError('type de demande deja existant'));
            }
        }

       
        $em = $this->getDoctrine()->getManager();
        $typeDemandes = $em->getRepository('LooninsGesCongeBundle:TypeDemande')->findBy(['del'=>null]);

        return $this->render('LooninsGesCongeBundle:typedemande:index.html.twig', array(
            'form' => $form->createView(),
            'typeDemandes' => $typeDemandes,
        ));
    }

    /**
     * Creates a new TypeDemande entity.
     *
     * @Route("/new", name="typedemande_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $typeDemande = new TypeDemande();
        $form = $this->createForm('Loonins\GesCongeBundle\Form\TypeDemandeType', $typeDemande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeDemande);
            $em->flush();

            return $this->redirectToRoute('typedemande_show', array('id' => $typeDemande->getId()));
        }

        return $this->render('typedemande/new.html.twig', array(
            'typeDemande' => $typeDemande,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a TypeDemande entity.
     *
     * @Route("/{id}", name="typedemande_show")
     * @Method("GET")
     */
    public function showAction(TypeDemande $typeDemande)
    {
        $deleteForm = $this->createDeleteForm($typeDemande);

        return $this->render('typedemande/show.html.twig', array(
            'typeDemande' => $typeDemande,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing TypeDemande entity.
     *
     * @Route("/{id}/edit", name="typedemande_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, TypeDemande $typeDemande)
    {
        $deleteForm = $this->createDeleteForm($typeDemande);
        $editForm = $this->createForm('Loonins\GesCongeBundle\Form\TypeDemandeType', $typeDemande);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($typeDemande);
            $em->flush();

            return $this->redirectToRoute('typedemande_edit', array('id' => $typeDemande->getId()));
        }

        return $this->render('typedemande/edit.html.twig', array(
            'typeDemande' => $typeDemande,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a TypeDemande entity.
     *
     * @Route("/{id}", name="typedemande_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, TypeDemande $typeDemande)
    {
        $form = $this->createDeleteForm($typeDemande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($typeDemande);
            $em->flush();
        }

        return $this->redirectToRoute('typedemande_index');
    }

    /**
     * Creates a form to delete a TypeDemande entity.
     *
     * @param TypeDemande $typeDemande The TypeDemande entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(TypeDemande $typeDemande)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('typedemande_delete', array('id' => $typeDemande->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
