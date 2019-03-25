<?php

namespace Loonins\GrhBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LooninsGrhBundle:Default:index.html.twig');
    }
}
