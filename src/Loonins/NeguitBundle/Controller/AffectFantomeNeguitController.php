<?php

namespace Loonins\NeguitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\NeguitBundle\Entity\AffectFantomeNeguit;
use Loonins\NeguitBundle\Form\AffectFantomeNeguitType;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\Extension\Core\Type\DateType;
/**
 * AffectFantomeNeguit controller.
 *
 * @Route("/neguit/affectfantomeneguit")
 */
class AffectFantomeNeguitController extends Controller
{
    /**
     * Lists all AffectFantomeNeguit entities.
     *
     * @Route("/", name="affectfantomeneguit_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {

        $affect = new AffectFantomeNeguit();

        $form = $this->createForm('Loonins\NeguitBundle\Form\AffectFantomeNeguitType', $affect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

         $repository=$this->getDoctrine()->getRepository('Loonins\NeguitBundle\Entity\AffectFantomeNeguit');
         $affectation =$this->getDoctrine()
        ->getManager()
        ->createQuery('SELECT a FROM Loonins\NeguitBundle\Entity\AffectFantomeNeguit a WHERE a.profilVirtuel= :profil and a.finAffect is null')
        ->setParameter('profil',$form->get('profilVirtuel')->getdata())
        ->getResult();
         
         if(empty($affectation))
            {
                
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($affect);
                    $em->flush();

                return $this->redirectToRoute('affectfantomeneguit_index');
                 
            }else
            {   
                    $form->addError(new FormError('affectation deja existante'));
            }
        }

        $repository=$this->getDoctrine()
        ->getRepository('Loonins\NeguitBundle\Entity\AffectFantomeNeguit');
        $affectations = $repository->findBy(array('finAffect'=>null));
        $em = $this->getDoctrine()->getManager();


        return $this->render('LooninsNeguitBundle:affectfantomeneguit:index.html.twig', array(
            'form' => $form->createView(),
            'affectations'=>$affectations,
        ));
    }

    /**
     * Creates a new AffectFantomeNeguit entity.
     *
     * @Route("/new", name="affectfantomeneguit_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $affectFantomeNeguit = new AffectFantomeNeguit();
        $form = $this->createForm('Loonins\NeguitBundle\Form\AffectFantomeNeguitType', $affectFantomeNeguit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($affectFantomeNeguit);
            $em->flush();

            return $this->redirectToRoute('affectfantomeneguit_show', array('id' => $affectFantomeNeguit->getId()));
        }

        return $this->render('LooninsNeguitBundle:affectfantomeneguit:new.html.twig', array(
            'affectFantomeNeguit' => $affectFantomeNeguit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a AffectFantomeNeguit entity.
     *
     * @Route("/{id}", name="affectfantomeneguit_show")
     * @Method("GET")
     */
    public function showAction(AffectFantomeNeguit $affectFantomeNeguit)
    {
        $deleteForm = $this->createDeleteForm($affectFantomeNeguit);

        return $this->render('LooninsNeguitBundle:affectfantomeneguit:show.html.twig', array(
            'affectFantomeNeguit' => $affectFantomeNeguit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing AffectFantomeNeguit entity.
     *
     * @Route("/{id}/edit", name="affectfantomeneguit_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, AffectFantomeNeguit $affectFantomeNeguit)
    {
        $deleteForm = $this->createDeleteForm($affectFantomeNeguit);
        $editForm = $this->createForm('Loonins\NeguitBundle\Form\AffectFantomeNeguitType', $affectFantomeNeguit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($affectFantomeNeguit);
            $em->flush();

            return $this->redirectToRoute('affectfantomeneguit_edit', array('id' => $affectFantomeNeguit->getId()));
        }

        return $this->render('LooninsNeguitBundle:affectfantomeneguit:edit.html.twig', array(
            'affectFantomeNeguit' => $affectFantomeNeguit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a AffectFantomeNeguit entity.
     *
     * @Route("/{id}", name="affectfantomeneguit_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, AffectFantomeNeguit $affectFantomeNeguit)
    {
        $form = $this->createDeleteForm($affectFantomeNeguit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($affectFantomeNeguit);
            $em->flush();
        }

        return $this->redirectToRoute('affectfantomeneguit_index');
    }

    /**
     * Creates a form to delete a AffectFantomeNeguit entity.
     *
     * @param AffectFantomeNeguit $affectFantomeNeguit The AffectFantomeNeguit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(AffectFantomeNeguit $affectFantomeNeguit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('affectfantomeneguit_delete', array('id' => $affectFantomeNeguit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


     /**
     * 
     *
     * @Route("/affectfantomeneguit_fin/{id}", name="affectfantomeneguit_fin")
     * @Method({"GET", "POST"})
     */
    public function finAction(Request $request, AffectFantomeNeguit $affectFantomeNeguit)
    {
        $form = $this->createFormBuilder()
        ->add('dateFin',DateType::class, array('widget' => 'single_text'))
         ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $affectFantomeNeguit->setfinAffect($form->get('dateFin')->getData());
                $em->flush();

            return $this->redirectToRoute('affectfantomeneguit_index');
        }

        return $this->render('LooninsNeguitBundle:affectfantomeneguit:finAffectLogin.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
