<?php

namespace Loonins\NeguitBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Loonins\NeguitBundle\Entity\AffectLoginNeguit;
use Loonins\NeguitBundle\Form\AffectLoginNeguitType;

class AffectLoginAnimController extends Controller
{
	 /**
     * 
     * @Route("/affectLoginAnimLogin", name="affectLoginAnim")
     * 
     */
    public function affectLoginAnimLoginAction(Request $request)
    {

		$affectLogin = new AffectLoginNeguit();

        $form = $this->createForm('Loonins\NeguitBundle\Form\AffectLoginNeguitType', $affectLogin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $repository=$this->getDoctrine()
            ->getRepository('Loonins\GrhBundle\Entity\GrhEmployes');
            $employe = $repository->findOneBy(['id'=>$form['employe']->getdata()]);
            $em = $this->getDoctrine()->getManager();
            $affectLogin->setemploye($employe);
            $em->persist($affectLogin);
            $em->flush();

            return $this->redirectToRoute('affectLoginAnim');
        }

        $repository=$this->getDoctrine()
        ->getRepository('Loonins\NeguitBundle\Entity\AffectLoginNeguit');
        $affectations = $repository->findAll();



     return $this->render('LooninsNeguitBundle:AffectLogin:AffectLogin.html.twig',
 						['form' => $form->createView(),
                        'affectations'=>$affectations,
 						]);
   
    }
}
