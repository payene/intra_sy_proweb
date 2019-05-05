<?php

namespace Loonins\GesCongeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LooninsGesCongeBundle:Default:index.html.twig');
    }
}
