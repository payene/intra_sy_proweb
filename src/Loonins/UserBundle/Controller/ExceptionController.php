<?php

namespace Loonins\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class ExceptionController extends Controller
{
    /**
     * @Route("/showException")
     */
    public function showExceptionAction()
    {
        return $this->render('Exception/error.html.twig', array(
        ));
    }

}
