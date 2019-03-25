<?php

namespace Loonins\GrhBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Loonins\GrhBundle\Entity\GrhMail;
use Loonins\GrhBundle\Form\GrhMailType;

class GrhMailController extends Controller
{
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $grhmail = new GrhMail();        
        $grhmailForm = $this->createForm(GrhMailType::class, $grhmail);
        
        $grhmailForm->handleRequest($request);
        if( $grhmailForm->isValid() ){
            
            $em->persist($grhmail);
            $em->flush();
            return $this->redirectToRoute('grhmail_index');
        }
        
        $grhmails = $em->getRepository("LooninsGrhBundle:GrhMail")->findAll();
        
        return $this->render('LooninsGrhBundle:GrhMail:index.html.twig', array(
            "entities" => $grhmails,
            'form' => $grhmailForm->createView(),
        ));
    }
    
    public function editAction(GrhMail $grhmail, Request $request){
        
        $grhmail->setEmail($request->request->get('email'));
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($grhmail);
        $em->flush();
        
        return $this->redirectToRoute('grhmail_index');
        
    }


    public function deleteAction(GrhMail $grhmail){
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($grhmail);
        $em->flush();
        
        return $this->redirectToRoute('grhmail_index');
        
    }

}
