<?php

namespace Loonins\NeguitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\NeguitBundle\Entity\AffectLoginNeguit;
use Loonins\NeguitBundle\Form\AffectLoginNeguitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormError;


/**
 * AffectFantomeNeguit controller.
 *
 * @Route("/neguit/affectLoginAnimLogin")
 */
class AffectLoginAnimController extends Controller
{
	 /**
     * 
     * @Route("/", name="affectLoginAnim")
     * 
     */
    public function affectLoginAnimLoginAction(Request $request)
    {

		$affectLogin = new AffectLoginNeguit();

        $form = $this->createForm('Loonins\NeguitBundle\Form\AffectLoginNeguitType', $affectLogin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

             $repository=$this->getDoctrine()->getRepository('Loonins\NeguitBundle\Entity\AffectLoginNeguit');
             $affectation =$this->getDoctrine()
            ->getManager()
            ->createQuery('SELECT a FROM Loonins\NeguitBundle\Entity\AffectLoginNeguit a WHERE a.loginAnimNeguit= :login and a.finAffectation is null')
            ->setParameter('login',$form['loginAnimNeguit']->getdata())
            ->getResult();

            if(empty($affectation))
            {
                
            $repository=$this->getDoctrine()
            ->getRepository('Loonins\GrhBundle\Entity\GrhEmployes');
            $employe = $repository->findOneBy(['id'=>$form['employe']->getdata()]);
            $em = $this->getDoctrine()->getManager();
            $affectLogin->setemploye($employe);
            $em->persist($affectLogin);
            $em->flush();

            return $this->redirectToRoute('affectLoginAnim');

            }else
            {   
                    $form->addError(new FormError('login deja affecte a un employe'));
            }
        }

        $repository=$this->getDoctrine()
        ->getRepository('Loonins\NeguitBundle\Entity\AffectLoginNeguit');
        $affectations = $repository->findBy(['finAffectation'=>null]);



     return $this->render('LooninsNeguitBundle:AffectLogin:AffectLogin.html.twig',
 						['form' => $form->createView(),
                        'affectations'=>$affectations,
 						]);
   
    }


     /**
     * 
     *
     * @Route("/affectLoginAnimLogin_fin/{id}", name="affectLoginAnimLogin_fin")
     * @Method({"GET", "POST"})
     */
    public function finAffectLoginAnimLoginAction(Request $request, AffectLoginNeguit $affectLoginNeguit)
    {
        $form = $this->createFormBuilder()
        ->add('dateFin',DateType::class, array('widget' => 'single_text'))
         ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $affectLoginNeguit->setfinAffectation($form->get('dateFin')->getData());
                $em->flush();

            return $this->redirectToRoute('affectLoginAnim');
        }

        return $this->render('LooninsNeguitBundle:AffectLogin:finAffectation.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
