<?php

namespace Loonins\NeguitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        //$planning  = new 
        
    	// $form   = $this->createCreateForm($planing, "Loonins\NeguitBundle\Form\StatType");
        return $this->render('LooninsNeguitBundle:Default:index.html.twig');
    }
}
