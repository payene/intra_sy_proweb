<?php

namespace Loonins\SuiviBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Loonins\SuiviBundle\Entity\RespMail;
use Loonins\SuiviBundle\Form\RespMailType;

class RespMailController extends Controller
{
    public function indexAction(Request $request)
    {
        $respMail = new RespMail();
        $form = $this->createForm(RespMailType::class, $respMail);        
        $em = $this->getDoctrine()->getManager();
        $respMails = $em->getRepository("LooninsSuiviBundle:RespMail")->findAll();
        
        if( $request->isMethod('POST') ){
            $form->handleRequest($request);
            if( $form->isValid() ){
                $em->persist($respMail);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', 'Responsable enregistré avec success');
                return $this->redirectToRoute('respmail_index');
            }
            
        }
        
        return $this->render('LooninsSuiviBundle:RespMail:index.html.twig', array(
            "respMails" => $respMails,
            "form" => $form->createView(),
            'addStatForm' => (StatController::newStatForm($this->createFormBuilder()))->createView(),
        ));
    }

    public function editAction(Request $request, RespMail $respMail)
    {
        
        $em = $this->getDoctrine()->getManager();
        
        if( $request->isMethod('POST') ){
//            $form->handleRequest($request);
//            if( $form->isValid() ){
            $respMail->setNom($request->request->get('nom'));
            $respMail->setEmail($request->request->get('email'));
                $em->persist($respMail);
                $em->flush();
                $this->get('session')->getFlashBag()->add('info', 'Responsable modifié avec success');
            //}
            
        }
        
        return $this->redirectToRoute('respmail_index');
    }

    public function deleteAction(Request $request, RespMail $respMail)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($respMail);
        $em->flush();
        $this->get('session')->getFlashBag()->add('info', 'Responsable supprimé avec succès');
        return $this->redirectToRoute('respmail_index');
    }

}
