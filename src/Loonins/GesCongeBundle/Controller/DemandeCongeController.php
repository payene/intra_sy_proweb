<?php

namespace Loonins\GesCongeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\GesCongeBundle\Entity\DemandeConge;
use Loonins\GesCongeBundle\Form\DemandeCongeType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

/**
 * DemandeConge controller.
 *
 * @Route("/demandeconge")
 */
class DemandeCongeController extends Controller
{
    /**
     * Lists all DemandeConge entities.
     *
     * @Route("/", name="demandeconge_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {


        $demandeConge = new DemandeConge();

        $form = $this->createForm('Loonins\GesCongeBundle\Form\DemandeCongeType', $demandeConge)
                        ->add('duree',IntegerType::class,['mapped'=>false,'required'=>true]);
        $form->handleRequest($request);

        $repository=$this->getDoctrine()->getRepository('Loonins\GrhBundle\Entity\GrhEmployes');
        $employe = $repository->findOneBy(['user'=>$this->getUser()->getId()]);

        if ($form->isSubmitted() && $form->isValid()) {

            $repository=$this->getDoctrine()->getRepository('Loonins\GesCongeBundle\Entity\DemandeConge');
            $demande = $repository->findOneBy(['employe'=>$employe,'statut'=>0,'typeDemande'=>$form['typeDemande']->getdata() ]);

            if(empty($demande))
            {
            $duree = $form['duree']->getdata();
            $em = $this->getDoctrine()->getManager();
            $demandeConge->setEmploye($employe);
            $demandeConge->setStatut(0);
            $demandeConge->setDebut( $form['debut']->getdata() ) ;
            $em->persist($demandeConge);
            $em->flush();
            $demandeConge->setFin( $demandeConge->getDebut()->modify('+'.$duree.'days') ) ;
            $em->flush();

            return $this->redirectToRoute('demandeconge_index');
            }else
            {   
                $form->addError(new FormError('Vous avez deja une demande de congé de ce type de congé en attente de traitement'));
            }
        }

       
        $em = $this->getDoctrine()->getManager();
        $demandeConges = $em->getRepository('LooninsGesCongeBundle:DemandeConge')->findBy(['del'=>null,'employe'=>$employe],['id'=>'DESC']);

        return $this->render('LooninsGesCongeBundle:demandeconge:index.html.twig', array(
            'form' => $form->createView(),
            'demandeConges' => $demandeConges,
            'module'=>'conge'
        ));
    }

    /**
     * @Route("/demandeconge_validation/{id}/{valeur}", name="demandeconge_validation", defaults={"id"=0,"valeur"=0})
     * @Method({"GET", "POST"})
     */
    public function validationAction(Request $request,$id,$valeur)
    {

        $em = $this->getDoctrine()->getManager();
        $demandeConges = $em->getRepository('LooninsGesCongeBundle:DemandeConge')->findBy(['del'=>null,'statut'=>0],['id'=>'DESC']);

        if($id!=0)
        {
            $repository=$this->getDoctrine()->getRepository('Loonins\GesCongeBundle\Entity\DemandeConge');
            $demande = $repository->findOneBy(['id'=>$id ]);

            if($valeur==1)
            {
                $em = $this->getDoctrine()->getManager();
                $demande->setStatut(1);
                $em->flush();
            }
            if($valeur==2)
            {
                $em = $this->getDoctrine()->getManager();
                $demande->setStatut(2);
                $em->flush();
            }

            return $this->redirectToRoute('demandeconge_validation');
        }

        return $this->render('LooninsGesCongeBundle:demandeconge:validation.html.twig', array(
            'demandeConges' => $demandeConges,
            'module'=>'conge'

        ));
    }

    /**
     * Creates a new DemandeConge entity.
     *
     * @Route("/new", name="demandeconge_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $demandeConge = new DemandeConge();
        $form = $this->createForm('Loonins\GesCongeBundle\Form\DemandeCongeType', $demandeConge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($demandeConge);
            $em->flush();

            return $this->redirectToRoute('demandeconge_show', array('id' => $demandeConge->getId()));
        }

        return $this->render('LooninsGesCongeBundle:demandeconge:new.html.twig', array(
            'demandeConge' => $demandeConge,
            'form' => $form->createView(),
            'module'=>'conge'

        ));
    }

    /**
     * Finds and displays a DemandeConge entity.
     *
     * @Route("/{id}", name="demandeconge_show")
     * @Method("GET")
     */
    public function showAction(DemandeConge $demandeConge)
    {
        $deleteForm = $this->createDeleteForm($demandeConge);

        return $this->render('LooninsGesCongeBundle:demandeconge:show.html.twig', array(
            'demandeConge' => $demandeConge,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing DemandeConge entity.
     *
     * @Route("/{id}/edit", name="demandeconge_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, DemandeConge $demandeConge)
    {
        $deleteForm = $this->createDeleteForm($demandeConge);
        $editForm = $this->createForm('Loonins\GesCongeBundle\Form\DemandeCongeType', $demandeConge);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($demandeConge);
            $em->flush();

            return $this->redirectToRoute('demandeconge_edit', array('id' => $demandeConge->getId()));
        }

        return $this->render('LooninsGesCongeBundle:demandeconge:edit.html.twig', array(
            'demandeConge' => $demandeConge,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a DemandeConge entity.
     *
     * @Route("/{id}", name="demandeconge_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, DemandeConge $demandeConge)
    {
        $form = $this->createDeleteForm($demandeConge);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($demandeConge);
            $em->flush();
        }

        return $this->redirectToRoute('demandeconge_index');
    }

    /**
     * Creates a form to delete a DemandeConge entity.
     *
     * @param DemandeConge $demandeConge The DemandeConge entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(DemandeConge $demandeConge)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('demandeconge_delete', array('id' => $demandeConge->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


  
}
