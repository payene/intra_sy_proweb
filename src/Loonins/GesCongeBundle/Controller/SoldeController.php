<?php

namespace Loonins\GesCongeBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\GesCongeBundle\Entity\Solde;
use Loonins\GesCongeBundle\Form\SoldeType;
use FOS\UserBundle\Util\LegacyFormHelper;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Solde controller.
 *
 * @Route("/solde")
 */
class SoldeController extends Controller
{
    /**
     * Lists all Solde entities.
     *
     * @Route("/", name="solde_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {

        $solde = new Solde();

        $form = $this->createForm('Loonins\GesCongeBundle\Form\SoldeType', $solde);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $repository=$this->getDoctrine()->getRepository('Loonins\GesCongeBundle\Entity\Solde');
            $solD = $repository->findOneBy(['typeDemande'=>$form['typeDemande']->getdata(),'employe'=>$form['employe']->getdata() ]);

            
            if(empty($solD))
            {
                $em = $this->getDoctrine()->getManager();
                $solde->setDerniereMaj(new \Datetime());
                $em->persist($solde);
                $em->flush();

                return $this->redirectToRoute('solde_index');
            }else
            {
                $em = $this->getDoctrine()->getManager();
                $solD->setDerniereMaj(new \Datetime());
                $solD->setSolde($form['solde']->getdata());
                $em->flush();
                return $this->redirectToRoute('solde_index');

            }

        }


        /****Second formulaire pour la creation par type ******/

        $formpartype=$this->createFormBuilder()
            ->add('solde')
            ->add('typeDemande',EntityType::class,array(
                'class'=>'Loonins\GesCongeBundle\Entity\TypeDemande',
                'mapped'=>'false',
                'choice_label'=>'libelle',
                ))
            ->getform();
        $formpartype->handleRequest($request);

        if ($formpartype->isSubmitted() && $formpartype->isValid()) {

            $repository=$this->getDoctrine()->getRepository('Loonins\GrhBundle\Entity\GrhEmployes');
            $employes = $repository->findAll();

            foreach ($employes as $key => $employe) {

                $solde = new Solde();

                $repository=$this->getDoctrine()->getRepository('Loonins\GesCongeBundle\Entity\Solde');
                $solD = $repository->findOneBy(['typeDemande'=>$formpartype['typeDemande']->getdata(),'employe'=>$employe]);

                
                if(empty($solD))
                {
                    $em = $this->getDoctrine()->getManager();
                    $solde->setDerniereMaj(new \Datetime());
                    $solde->setEmploye($employe);
                    $solde->setTypeDemande($formpartype['typeDemande']->getdata());
                    $solde->setSolde($formpartype['solde']->getdata());
                    $em->persist($solde);
                    $em->flush();

                }else
                {
                    $em = $this->getDoctrine()->getManager();
                    $solD->setDerniereMaj(new \Datetime());
                    $solD->setSolde($formpartype['solde']->getdata());
                    $em->flush();

                }
            }
           

           return $this->redirectToRoute('solde_index');
        }



        /****Second formulaire pour la creation par type ******/

        $allform=$this->createFormBuilder()
            ->add('solde')
            ->add('enregistrer', SubmitType::class)
            ->getform();
        $allform->handleRequest($request);

        $repository =$this->getDoctrine()->getRepository('Loonins\GesCongeBundle\Entity\TypeDemande');
        $typeDemandes = $repository->findAll();

        $repository=$this->getDoctrine()->getRepository('Loonins\GrhBundle\Entity\GrhEmployes');
        $employes = $repository->findAll();

        if ($allform->isSubmitted() && $allform->isValid()) {
                 
            foreach ($typeDemandes as $key => $typeDemande) 
            {

                    foreach ($employes as $key => $employe) {

                        $solde = new Solde();

                        $repository=$this->getDoctrine()->getRepository('Loonins\GesCongeBundle\Entity\Solde');
                        $solD = $repository->findOneBy(['typeDemande'=>$typeDemande,'employe'=>$employe]);
                        
                        if(empty($solD))
                        {
                            $em = $this->getDoctrine()->getManager();
                            $solde->setDerniereMaj(new \Datetime());
                            $solde->setEmploye($employe);
                            $solde->setTypeDemande($typeDemande);
                            $solde->setSolde($allform['solde']->getdata());
                            $em->persist($solde);
                            $em->flush();

                        }else
                        {
                            $em = $this->getDoctrine()->getManager();
                            $solD->setDerniereMaj(new \Datetime());
                            $solD->setSolde($allform['solde']->getdata());
                            $em->flush();

                        }
                    }

            }
            
           return $this->redirectToRoute('solde_index');
        }




        $em = $this->getDoctrine()->getManager();

        $soldes = $em->getRepository('LooninsGesCongeBundle:Solde')->findAll();

        return $this->render('LooninsGesCongeBundle:solde:index.html.twig', array(
            'form' => $form->createView(),
            'formpartype' => $formpartype->createView(),
            'allform' => $allform->createView(),
            'soldes' => $soldes,
        ));
    }

    /**
     * Creates a new Solde entity.
     *
     * @Route("/new", name="solde_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $solde = new Solde();
        $form = $this->createForm('Loonins\GesCongeBundle\Form\SoldeType', $solde);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($solde);
            $em->flush();

            return $this->redirectToRoute('solde_show', array('id' => $solde->getId()));
        }

        return $this->render('LooninsGesCongeBundle:solde:new.html.twig', array(
            'solde' => $solde,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Solde entity.
     *
     * @Route("/{id}", name="solde_show")
     * @Method("GET")
     */
    public function showAction(Solde $solde)
    {
        $deleteForm = $this->createDeleteForm($solde);

        return $this->render('LooninsGesCongeBundle:Solde:show.html.twig', array(
            'solde' => $solde,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Solde entity.
     *
     * @Route("/{id}/edit", name="solde_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Solde $solde)
    {
        $deleteForm = $this->createDeleteForm($solde);
        $editForm = $this->createForm('Loonins\GesCongeBundle\Form\SoldeType', $solde);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($solde);
            $em->flush();

            return $this->redirectToRoute('solde_edit', array('id' => $solde->getId()));
        }

        return $this->render('LooninsGesCongeBundle:solde:edit.html.twig', array(
            'solde' => $solde,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Solde entity.
     *
     * @Route("/{id}", name="solde_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Solde $solde)
    {
        $form = $this->createDeleteForm($solde);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($solde);
            $em->flush();
        }

        return $this->redirectToRoute('solde_index');
    }

    /**
     * Creates a form to delete a Solde entity.
     *
     * @param Solde $solde The Solde entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Solde $solde)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('solde_delete', array('id' => $solde->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
